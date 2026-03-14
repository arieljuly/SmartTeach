<?php
// app/Http/Controllers/ExtractionController.php

namespace App\Http\Controllers;

use App\Models\LessonPlans;
use App\Models\AudioListeningJobs;
use App\Models\ExtractContents;
use App\Services\FreeTTSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExtractionController extends Controller
{
    protected $freeTTSService;

    public function __construct(FreeTTSService $freeTTSService)
    {
        $this->freeTTSService = $freeTTSService;
    }

    /**
     * Display the PDF to Audio page with all lessons
     */
    public function pdfToAudio($lessonId = null)
    {
        // Get all lessons for the current user
        $lessons = LessonPlans::where('user_id', Auth::id())
                    ->orWhereHas('user', function($query) {
                        $query->where('role', 'admin');
                    })
                    ->with('extractContent') // Eager load content
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        $lesson = null;
        $audioJobs = collect();
        $extractedContent = null;
        
        if ($lessonId) {
            $lesson = LessonPlans::with('extractContent')->find($lessonId);
            
            if ($lesson) {
                // Get the extracted content from the PDF
                $extractedContent = $lesson->extractContent;
                
                // Get existing audio jobs for this lesson
                $audioJobs = AudioListeningJobs::where('lesson_id', $lessonId)
                                ->orderBy('created_at', 'desc')
                                ->get();
            }
        }
        
        return view('admins.pdfToAudio', [
            'lessons' => $lessons,
            'lesson' => $lesson,
            'audioJobs' => $audioJobs,
            'extractedContent' => $extractedContent,
            'selectedLessonId' => $lessonId
        ]);
    }

    /**
     * Generate audio for a specific type using actual lesson content
     */
    public function generateAudioType(Request $request, $lessonId, $type)
    {
        try {
            $lesson = LessonPlans::with('extractContent')->findOrFail($lessonId);
            
            if (!in_array($type, ['script', 'vocabulary', 'dialogue', 'story', 'full_lesson'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid audio type'
                ], 400);
            }
            
            // Get content from the actual extracted PDF
            $content = $this->getContentFromLesson($lesson, $type);
            
            if (empty($content)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No content found for this audio type. Please ensure the PDF has been extracted.'
                ], 404);
            }
            
            // Create metadata with source info
            $metadata = [
                'section' => $type, 
                'generated_by' => 'manual',
                'lesson_name' => $lesson->file_name,
                'source' => 'extracted_content'
            ];
            
            // Create browser-based TTS job
            $audioJob = $this->freeTTSService->createBrowserAudioJob(
                $lessonId,
                Auth::id(),
                $type,
                $content,
                $metadata
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Audio ready for browser playback',
                'job_id' => $audioJob->job_id,
                'content_preview' => substr($content, 0, 100) . '...'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Audio generation failed', [
                'lesson_id' => $lessonId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate all audio types for a lesson
     */
    public function generateAllAudio(Request $request, $lessonId)
    {
        try {
            $lesson = LessonPlans::with('extractContent')->findOrFail($lessonId);
            
            // Get all content types
            $contentTypes = ['script', 'vocabulary', 'dialogue', 'story'];
            $jobs = [];
            
            foreach ($contentTypes as $type) {
                $content = $this->getContentFromLesson($lesson, $type);
                
                if (!empty($content)) {
                    $jobs[] = $this->freeTTSService->createBrowserAudioJob(
                        $lessonId,
                        Auth::id(),
                        $type,
                        $content,
                        [
                            'section' => $type,
                            'lesson_name' => $lesson->file_name,
                            'source' => 'extracted_content'
                        ]
                    );
                }
            }
            
            // Also generate full lesson audio
            $fullContent = $this->getContentFromLesson($lesson, 'full_lesson');
            if (!empty($fullContent)) {
                $jobs[] = $this->freeTTSService->createBrowserAudioJob(
                    $lessonId,
                    Auth::id(),
                    'full_lesson',
                    $fullContent,
                    [
                        'section' => 'full_lesson',
                        'lesson_name' => $lesson->file_name,
                        'source' => 'extracted_content'
                    ]
                );
            }
            
            return response()->json([
                'success' => true,
                'message' => 'All audio ready for browser playback',
                'job_count' => count($jobs)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Generate all audio failed', [
                'lesson_id' => $lessonId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get content from the actual lesson
     */
    protected function getContentFromLesson($lesson, $type)
    {
        $extractContent = $lesson->extractContent;
        
        if (!$extractContent) {
            return null;
        }
        
        // Try to get content from extracted data
        $content = '';
        
        switch ($type) {
            case 'script':
                // Look for teacher script or main text
                $content = $extractContent->teacher_script ?? 
                          $extractContent->main_text ?? 
                          $this->extractFirstParagraph($extractContent->full_text ?? '');
                break;
                
            case 'vocabulary':
                // Extract vocabulary words
                $content = $this->extractVocabulary($extractContent);
                break;
                
            case 'dialogue':
                // Look for dialogue or conversation sections
                $content = $extractContent->dialogue_text ?? 
                          $this->extractDialogue($extractContent->full_text ?? '');
                break;
                
            case 'story':
                // Look for story or narrative sections
                $content = $extractContent->story_text ?? 
                          $extractContent->narrative_text ?? 
                          $extractContent->full_text ?? '';
                break;
                
            case 'full_lesson':
                // Combine all content
                $content = $this->combineAllContent($extractContent);
                break;
        }
        
        // Clean up the content
        return $this->cleanContentForTTS($content);
    }

    /**
     * Extract vocabulary from content
     */
    protected function extractVocabulary($extractContent)
    {
        // Try to get from extracted vocabulary
        if (!empty($extractContent->vocabulary_words)) {
            $vocab = $extractContent->vocabulary_words;
            if (is_array($vocab)) {
                return $this->formatVocabularyForAudio($vocab);
            }
            return $vocab;
        }
        
        // If no vocabulary, create some from the text
        $text = $extractContent->full_text ?? '';
        $words = $this->extractKeyWords($text, 10);
        
        return $this->formatVocabularyForAudio($words);
    }

    /**
     * Extract dialogue from text
     */
    protected function extractDialogue($text)
    {
        // Look for dialogue patterns (lines with quotes or speaker indicators)
        preg_match_all('/[A-Za-z]+: [^.!?]+[.!?]/', $text, $matches);
        
        if (!empty($matches[0])) {
            return implode("\n", $matches[0]);
        }
        
        // If no dialogue found, return a default conversation based on content
        return $this->createDefaultDialogue($text);
    }

    /**
     * Combine all content for full lesson audio
     */
    protected function combineAllContent($extractContent)
    {
        $parts = [];
        
        if (!empty($extractContent->title)) {
            $parts[] = "Lesson: " . $extractContent->title;
        }
        
        if (!empty($extractContent->teacher_script)) {
            $parts[] = $extractContent->teacher_script;
        }
        
        if (!empty($extractContent->vocabulary_words)) {
            $vocab = is_array($extractContent->vocabulary_words) 
                ? implode(', ', $extractContent->vocabulary_words) 
                : $extractContent->vocabulary_words;
            $parts[] = "Vocabulary words: " . $vocab;
        }
        
        if (!empty($extractContent->main_text)) {
            $parts[] = $extractContent->main_text;
        }
        
        if (!empty($extractContent->full_text)) {
            $parts[] = $extractContent->full_text;
        }
        
        return implode("\n\n", array_filter($parts));
    }

    /**
     * Extract first paragraph from text
     */
    protected function extractFirstParagraph($text)
    {
        $paragraphs = explode("\n\n", $text);
        return $paragraphs[0] ?? $text;
    }

    /**
     * Extract key words from text for vocabulary
     */
    protected function extractKeyWords($text, $limit = 10)
    {
        // Simple word frequency extraction
        $words = str_word_count(strtolower($text), 1);
        $words = array_filter($words, function($word) {
            return strlen($word) > 4; // Only longer words
        });
        
        $wordFreq = array_count_values($words);
        arsort($wordFreq);
        
        $keyWords = array_slice(array_keys($wordFreq), 0, $limit);
        
        return $keyWords;
    }

    /**
     * Format vocabulary for audio
     */
    protected function formatVocabularyForAudio($words)
    {
        $formatted = "Let's learn new words. Listen and repeat.\n\n";
        
        foreach ($words as $word) {
            $cleanWord = trim($word);
            $formatted .= $cleanWord . ". " . $this->getExampleSentence($cleanWord) . "\n\n";
        }
        
        return $formatted;
    }

    /**
     * Get example sentence for vocabulary word
     */
    protected function getExampleSentence($word)
    {
        $examples = [
            'watch' => 'I watch TV in the evening.',
            'play' => 'Children play in the park.',
            'visit' => 'We visit grandma on Sundays.',
            'family' => 'My family is very kind.',
            'backpack' => 'I carry my books in my backpack.',
            'basketball' => 'He plays basketball after school.',
            'laptop' => 'She uses a laptop for homework.',
            'headphones' => 'I listen to music with headphones.',
            'bicycle' => 'He rides his bicycle to the park.',
            'breakfast' => 'We eat breakfast every morning.',
            'school' => 'I go to school every day.',
            'teacher' => 'My teacher is very helpful.',
            'student' => 'The student studies hard.',
            'homework' => 'I do my homework after school.',
            'friend' => 'My friend is kind and funny.'
        ];
        
        // Try to find the word in examples
        $lowerWord = strtolower($word);
        
        foreach ($examples as $key => $sentence) {
            if (strpos($lowerWord, $key) !== false) {
                return $sentence;
            }
        }
        
        // Default sentence
        return "Let's practice saying {$word}.";
    }

    /**
     * Create default dialogue based on content
     */
    protected function createDefaultDialogue($text)
    {
        // Extract sentences that might form a dialogue
        preg_match_all('/[^.!?]+[.!?]/', $text, $sentences);
        
        if (count($sentences[0]) >= 2) {
            return "Person A: " . $sentences[0][0] . "\nPerson B: " . ($sentences[0][1] ?? "That's interesting!");
        }
        
        return "Let's practice having a conversation about today's lesson.";
    }

    /**
     * Clean content for TTS
     */
    protected function cleanContentForTTS($content)
    {
        if (empty($content)) {
            return '';
        }
        
        // Remove markdown, HTML tags, etc.
        $content = strip_tags($content);
        
        // Remove extra whitespace
        $content = preg_replace('/\s+/', ' ', $content);
        
        // Ensure proper punctuation for TTS
        $content = preg_replace('/([.!?])\s*/', '$1 ', $content);
        
        return trim($content);
    }

    /**
     * Get listening session view
     */
    public function getListeningSession($lessonId)
    {
        $lesson = LessonPlans::findOrFail($lessonId);
        
        $audios = AudioListeningJobs::where('lesson_id', $lessonId)
                    ->where('status', 'completed')
                    ->orderBy('audio_type')
                    ->get();
        
        return view('admins.listening-session', [
            'lesson' => $lesson,
            'audios' => $audios
        ]);
    }

    /**
     * Get audio job status
     */
    public function getAudioStatus($audioId)
    {
        try {
            $audioJob = AudioListeningJobs::findOrFail($audioId);
            
            return response()->json([
                'success' => true,
                'job_id' => $audioJob->job_id,
                'status' => $audioJob->status,
                'audio_type' => $audioJob->audio_type,
                'metadata' => $audioJob->metadata
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the main extraction page
     */
    public function index()
    {
        return redirect()->route('admin.extraction.pdfToAudio');
    }

    /**
     * Display PDF to Video page
     */
    public function pdfToVideo()
    {
        return view('admins.pdfToVideo');
    }

    /**
     * Display output page
     */
    public function output()
    {
        return view('admins.outputManagement');
    }
    public function getAudioText($jobId)
    {
        try {
            $audioJob = AudioListeningJobs::findOrFail($jobId);
            
            return response()->json([
                'success' => true,
                'text' => $audioJob->original_text,
                'job_id' => $audioJob->job_id,
                'type' => $audioJob->audio_type
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}