<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LessonPlans;
use App\Models\ExtractContents;
use App\Models\Analysis;
use App\Models\Questions;
use App\Models\QuestionOptions;
use App\Models\QuestionTypes;
use App\Http\Controllers\LessonPlan;
use App\Models\Activities;
use App\Models\PerformanceTasks;
use App\Models\Rubrics;
use App\Models\RubricCriterias;
use App\Models\GeneratedDocuments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AI\GroqService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Helpers\Logger;
use Illuminate\Support\Str;

class PdfExtractionController extends Controller
{
    protected $groq;
    protected $currentLessonId = null;
    
    public function __construct()
    {
        try {
            $this->groq = new GroqService();
            
            Log::info('PdfExtractionController initialized with Groq', [
                'model' => config('services.groq.model', 'llama-3.1-8b-instant')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to initialize Groq service: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate content using Groq
     */
    private function generateWithGroq($prompt, $temperature = 0.7, $maxTokens = 4000)
    {
        try {

            $promptTokens = $this->estimateTokenCount($prompt);
            Log::info('Sending to Groq', [
                'prompt_tokens' => $promptTokens,
                'max_tokens' => $maxTokens,
                'total_estimated' => $promptTokens + $maxTokens
            ]);
            
            $response = $this->groq->generate($prompt, $temperature, $maxTokens);
            $response = $this->groq->generate($prompt, $temperature, $maxTokens);
            
            // Clean the response - remove markdown code blocks if present
            $response = preg_replace('/^```json\s*|\s*```$/m', '', $response);
            $response = trim($response);
            
            // Parse JSON response
            $decoded = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Groq response as JSON', [
                    'response' => $response,
                    'error' => json_last_error_msg()
                ]);
                
                // Try to extract JSON if there's extra text
                preg_match('/\{.*\}/s', $response, $matches);
                if (!empty($matches)) {
                    $decoded = json_decode($matches[0], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $decoded;
                    }
                }
                
                throw new \Exception('AI response was not in valid JSON format');
            }
            
            return $decoded;
            
        } catch (\Exception $e) {
            Log::error('Groq generation error: ' . $e->getMessage());
            throw $e;
        }
    }
    private function estimateTokenCount($text)
    {
        // More accurate estimation for mixed content
        $text = preg_replace('/\s+/', ' ', $text); // Normalize spaces
        $charCount = strlen($text);
        
        // Rough estimation: 4 chars per token for English
        $estimatedTokens = ceil($charCount / 4);
        
        return $estimatedTokens;
    }

    /**
     * Check if text exceeds token limit
     */
    private function checkTokenLimit($text, $limit = 8000, $buffer = 500)
    {
        $estimatedTokens = $this->estimateTokenCount($text);
        $isExceeded = $estimatedTokens > ($limit - $buffer);
        
        Log::info('Token estimation', [
            'characters' => strlen($text),
            'estimated_tokens' => $estimatedTokens,
            'limit' => $limit,
            'buffer' => $buffer,
            'exceeds_limit' => $isExceeded
        ]);
        
        return [
            'exceeds' => $isExceeded,
            'estimated_tokens' => $estimatedTokens,
            'characters' => strlen($text),
            'safe_limit' => $limit - $buffer
        ];
    }
    private function analyzeWithGroq($text)
    {
        $maxLength = 8000; 

         $tokenInfo = $this->checkTokenLimit($text, 8000, 1000);
    
        Log::info('Token analysis before processing', $tokenInfo);
        
        // If exceeds limit, use intelligent truncation
        if ($tokenInfo['exceeds']) {
            Log::warning('Text exceeds token limit, applying intelligent truncation', [
                'estimated_tokens' => $tokenInfo['estimated_tokens'],
                'characters' => $tokenInfo['characters']
            ]);
            
            // Calculate how much we need to truncate
            $targetChars = ($tokenInfo['safe_limit'] * 4); // Convert tokens back to chars
            $truncatedText = $this->intelligentTruncate($text, $targetChars);
            
            // Re-check token count after truncation
            $newTokenInfo = $this->checkTokenLimit($truncatedText, 8000, 1000);
            Log::info('After truncation', $newTokenInfo);
        } else {
            $truncatedText = $text;
        }
        if (strlen($text) > $maxLength) {
            Log::info('Truncating long lesson plan', [
                'original_length' => strlen($text),
                'max_length' => $maxLength
            ]);
            
            $start = substr($text, 0, 3000);
            $end = substr($text, -3000);
            
            $middleStart = (int)(strlen($text) / 2) - 1000;
            $middle = substr($text, max(3000, $middleStart), 2000);
            
            $truncatedText = "LESSON START (Introduction & Objectives):\n$start\n\n";
            $truncatedText .= "LESSON MIDDLE (Core Content):\n$middle\n\n";
            $truncatedText .= "LESSON END (Activities & Assessment):\n$end";
        } else {
            $truncatedText = $text;
        }

        // Log the size for monitoring
        Log::info('Analyzing lesson plan', [
            'original_length' => strlen($text),
            'truncated_length' => strlen($truncatedText),
            'estimated_tokens' => ceil(strlen($truncatedText) / 4)
        ]);

        $prompt = <<<EOT
        You are an expert curriculum developer. Analyze this lesson plan and generate comprehensive educational content in EXACT JSON format.

        LESSON PLAN CONTENT:
        {$truncatedText}

        Generate a JSON response with this EXACT structure. EXPAND all arrays to meet the REQUIREMENTS:

        {
            "summary": "Brief 2-3 sentence summary of the lesson",
            "key_concepts": ["concept1", "concept2", "concept3", "concept4", "concept5"],
            "activities": [
                {"name": "Activity 1 Name", "description": "Detailed step-by-step activity description", "duration": 15},
                {"name": "Activity 2 Name", "description": "Detailed step-by-step activity description", "duration": 20},
                {"name": "Activity 3 Name", "description": "Detailed step-by-step activity description", "duration": 25},
                {"name": "Activity 4 Name", "description": "Detailed step-by-step activity description", "duration": 15},
                {"name": "Activity 5 Name", "description": "Detailed step-by-step activity description", "duration": 20}
            ],
            "mcq_questions": [
                {"question": "Multiple choice question 1?", "options": ["Option A", "Option B", "Option C", "Option D"], "correct": "A", "points": 1},
                {"question": "Multiple choice question 2?", "options": ["Option A", "Option B", "Option C", "Option D"], "correct": "B", "points": 1},
                {"question": "Multiple choice question 3?", "options": ["Option A", "Option B", "Option C", "Option D"], "correct": "C", "points": 1},
                {"question": "Multiple choice question 4?", "options": ["Option A", "Option B", "Option C", "Option D"], "correct": "D", "points": 1},
                {"question": "Multiple choice question 5?", "options": ["Option A", "Option B", "Option C", "Option D"], "correct": "A", "points": 1}
            ],
            "identification_questions": [
                {"question": "Identification question 1?", "answer": "Correct answer", "points": 2},
                {"question": "Identification question 2?", "answer": "Correct answer", "points": 2},
                {"question": "Identification question 3?", "answer": "Correct answer", "points": 2}
            ],
            "essay_questions": [
                {"question": "Essay question 1?", "points": 10},
                {"question": "Essay question 2?", "points": 10}
            ],
            "performance_tasks": [
                {"name": "Task 1 Name", "description": "Detailed task description with objectives", "success_criteria": "Criteria for success"},
                {"name": "Task 2 Name", "description": "Detailed task description with objectives", "success_criteria": "Criteria for success"}
            ]
        }

        REQUIREMENTS:
        - Generate EXACTLY 5 activities
        - Generate EXACTLY 5 multiple choice questions (I've shown 5 examples)
        - Generate EXACTLY 3 identification questions
        - Generate EXACTLY 2 essay questions
        - Generate EXACTLY 2 performance tasks
        - Keep each question/activity concise but meaningful
        - All content must be educationally sound and appropriate for the lesson
        - Return ONLY valid JSON, no other text
        EOT;

        return $this->generateWithGroq($prompt, 0.7, 8000);
    }
    private function intelligentTruncate($text, $targetChars)
    {
        $length = strlen($text);
        
        if ($length <= $targetChars) {
            return $text;
        }
        
        // Calculate how much to keep from each section
        $keepRatio = $targetChars / $length;
        
        // Keep more from beginning and end, less from middle
        $startKeep = ceil($targetChars * 0.4); // 40% from start
        $endKeep = ceil($targetChars * 0.4);    // 40% from end
        $middleKeep = $targetChars - $startKeep - $endKeep; // 20% from middle
        
        $start = substr($text, 0, $startKeep);
        $end = substr($text, -$endKeep);
        
        // Get middle section around the center
        $middleStart = max($startKeep, floor($length / 2) - floor($middleKeep / 2));
        $middle = substr($text, $middleStart, $middleKeep);
        
        return "LESSON START:\n$start\n\nLESSON MIDDLE:\n$middle\n\nLESSON END:\n$end";
    }
    private function validateAnalysisStructure($analysis)
    {
        $required = ['summary', 'key_concepts', 'activities', 'mcq_questions', 
                    'identification_questions', 'essay_questions', 'performance_tasks'];
        
        foreach ($required as $field) {
            if (!isset($analysis[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
        
        // Validate counts
        if (count($analysis['activities']) < 5) {
            Log::warning("Only " . count($analysis['activities']) . " activities generated");
        }
        
        if (count($analysis['mcq_questions']) < 5) {
            Log::warning("Only " . count($analysis['mcq_questions']) . " MCQ questions generated");
        }
        
        return true;
    }

    /**
     * Main analyze method
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lesson_plans,lesson_id'
        ]);

        try {
            set_time_limit(300); // 5 minutes
            
            $this->currentLessonId = $request->lesson_id;
            
            $lessonPlan = LessonPlans::with('extractContent')->find($request->lesson_id);
            $extractedText = $lessonPlan->extractContent->extracted_text;

            if ($lessonPlan->analysis) {
                return response()->json([
                    'success' => false,
                    'message' => 'This lesson plan has already been analyzed.'
                ], 400);
            }

            // Update progress
            Cache::put('analysis_progress_' . $request->lesson_id, [
                'status' => 'analyzing',
                'progress' => 10,
                'message' => 'Starting AI analysis with Groq (using Llama 3)...'
            ], 300);

            // Generate all content with Groq
            $analysis = $this->analyzeWithGroq($extractedText);
            // Validate the structure
            $this->validateAnalysisStructure($analysis);
            // Update progress
            Cache::put('analysis_progress_' . $request->lesson_id, [
                'status' => 'saving',
                'progress' => 80,
                'message' => 'Saving generated content...'
            ], 300);

            // Save analysis
            $analysisModel = Analysis::create([
                'lesson_id' => $lessonPlan->lesson_id,
                'topic_summary' => $analysis['summary'] ?? '',
                'key_concepts' => json_encode($analysis['key_concepts'] ?? []),
                'analysis_result' => $analysis,
                'status' => 'completed'
            ]);

            // Save all generated content
            $this->saveAllGeneratedContent($analysisModel, $analysis);

            // Update lesson plan status
            $lessonPlan->update(['status' => 'analyzed']);

            // Clear progress
            Cache::forget('analysis_progress_' . $request->lesson_id);
            $this->currentLessonId = null;

            return response()->json([
                'success' => true,
                'message' => 'Content analyzed and generated successfully',
                'analysis_id' => $analysisModel->analysis_id
            ]);

        } catch (\Exception $e) {
            Log::error('Analysis Error: ' . $e->getMessage());
            
            if (isset($request->lesson_id)) {
                Cache::forget('analysis_progress_' . $request->lesson_id);
            }
            
            $this->currentLessonId = null;
            
            return response()->json([
                'success' => false,
                'message' => 'Error analyzing content: ' . $e->getMessage()
            ], 500);
        }
    }
   public function index()
    {
        $lessonPlans = LessonPlans::with(['analysis', 'documents'])
            ->where('user_id', Auth::id())
            ->orWhereHas('user', function($query) {
                $query->where('role', 'admin');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admins.pdfExtraction', compact('lessonPlans'));
    }
    /**
     * Handle PDF upload and text extraction
     */
    public function upload(Request $request)
    {
        // Log the request
        Logger::log('Upload method called', [
            'has_file' => $request->hasFile('pdf_file'),
            'all_files' => array_keys($request->allFiles()),
            'content_type' => $request->header('Content-Type'),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
        ]);

        try {
            if (!$request->hasFile('pdf_file')) {
                Logger::log('No file in request', [
                    'files' => $_FILES ?? 'No files',
                    'post' => $_POST ?? 'No post'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'No PDF file uploaded'
                ], 400);
            }

            $file = $request->file('pdf_file');
            
            Logger::log('File received', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'error' => $file->getError(),
                'is_valid' => $file->isValid(),
            ]);

            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload: ' . $file->getErrorMessage()
                ], 400);
            }

            // Validate file type
            if ($file->getClientOriginalExtension() !== 'pdf' && $file->getMimeType() !== 'application/pdf') {
                Logger::log('Invalid file type', [
                    'extension' => $file->getClientOriginalExtension(),
                    'mime' => $file->getMimeType()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'File must be a PDF'
                ], 400);
            }

            // Generate unique filename
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
            $path = 'lesson-plans/' . $filename;
            
            Logger::log('Attempting to store file', [
                'path' => $path,
                'disk' => 'public'
            ]);

            // Store the file
            $stored = Storage::disk('public')->put($path, file_get_contents($file));
            
            if (!$stored) {
                Logger::log('Failed to store file');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store file'
                ], 500);
            }

            // Create lesson plan record
            $lesson = LessonPlans::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'status' => 'upload',
                'user_id' => Auth::id()
            ]);

            Logger::log('Lesson plan created', [
                'lesson_id' => $lesson->lesson_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'PDF uploaded successfully',
                'lesson_id' => $lesson->lesson_id
            ]);

        } catch (\Exception $e) {
            Logger::log('Exception in upload', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Extract text from PDF file
     */
    private function extractPdfText($filePath)
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();
            
            // Clean up the text
            $text = preg_replace('/\s+/', ' ', $text); // Replace multiple spaces with single space
            $text = trim($text);
            
            return $text;
        } catch (\Exception $e) {
            Log::error('PDF Text Extraction Error: ' . $e->getMessage());
            throw new \Exception('Failed to extract text from PDF: ' . $e->getMessage());
        }
    }
    /**
     * Generate document from analyzed content
     */
    public function generateDocument(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lesson_plans,lesson_id',
            'document_type' => 'required|in:complete,activities,questions,tasks,rubrics'
        ]);

        try {
            $lessonPlan = LessonPlans::with([
                'analysis', 
                'analysis.questions.questionType',  // This loads the question type
                'analysis.questions.options',        // This loads the options
                'analysis.activities', 
                'analysis.performanceTasks',
                'analysis.rubrics.criterias'
            ])->find($request->lesson_id);

            $content = $this->generateDocumentContent($lessonPlan, $request->document_type);
            
            $pdf = Pdf::loadHTML($content);
            $fileName = 'generated_' . time() . '.pdf';
            $filePath = 'generated-documents/' . $fileName;
            
            Storage::disk('public')->put($filePath, $pdf->output());

            GeneratedDocuments::create([
                'lesson_id' => $lessonPlan->lesson_id,
                'document_name' => $fileName,
                'file_path' => $filePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document generated successfully',
                'file_url' => Storage::url($filePath)
            ]);

        } catch (\Exception $e) {
            Log::error('Document Generation Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error generating document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate document HTML content
     */
    private function generateDocumentContent($lessonPlan, $type)
    {
        return view('admins.pdf-document', compact('lessonPlan', 'type'))->render();
    }

    /**
     * Check analysis progress
     */
    public function checkProgress($lessonId)
    {
        $progress = Cache::get('analysis_progress_' . $lessonId, [
            'status' => 'unknown',
            'progress' => 0,
            'message' => 'Processing...'
        ]);
        
        return response()->json($progress);
    }
    /**
     * Save all generated content (same as before)
     */
    private function saveAllGeneratedContent($analysisModel, $analysis)
    {
        $questionTypes = QuestionTypes::pluck('question_type_id', 'type_name')->toArray();
    
        if (empty($questionTypes)) {
            Log::error('No question types found in database');
            throw new \Exception('Question types not configured. Please run database seeds first.');
        }
        // Save activities
        if (isset($analysis['activities']) && is_array($analysis['activities'])) {
            foreach ($analysis['activities'] as $activity) {
                Activities::create([
                    'analysis_id' => $analysisModel->analysis_id,
                    'activity_name' => $activity['name'] ?? 'Unnamed Activity',
                    'activity_description' => $activity['description'] ?? '',
                    'estimated_duration' => $activity['duration'] ?? 30,
                    'status' => 'active'
                ]);
            }
        }

        // Save multiple choice questions
        if (isset($analysis['mcq_questions']) && is_array($analysis['mcq_questions']) && isset($questionTypes['Multiple Choice'])) {
            foreach ($analysis['mcq_questions'] as $mcq) {
                $question = Questions::create([
                    'question_type_id' => $questionTypes['Multiple Choice'],
                    'analysis_id' => $analysisModel->analysis_id,
                    'question_text' => $mcq['question'] ?? '',
                    'points' => $mcq['points'] ?? 1,
                    'status' => 'active'
                ]);

                if (isset($mcq['options']) && is_array($mcq['options'])) {
                    foreach ($mcq['options'] as $index => $optionText) {
                        $isCorrect = false;
                        if (isset($mcq['correct'])) {
                            $correctLetter = strtoupper($mcq['correct']);
                            $optionLetter = chr(65 + $index);
                            $isCorrect = ($correctLetter === $optionLetter);
                        }
                        
                        QuestionOptions::create([
                            'question_id' => $question->question_id,
                            'option_text' => $optionText,
                            'is_correct' => $isCorrect
                        ]);
                    }
                }
            }
        }

        // Save identification questions
        if (isset($analysis['identification_questions']) && is_array($analysis['identification_questions']) && isset($questionTypes['Identification'])) {
            foreach ($analysis['identification_questions'] as $idq) {
                Questions::create([
                    'question_type_id' => $questionTypes['Identification'],
                    'analysis_id' => $analysisModel->analysis_id,
                    'question_text' => $idq['question'] ?? '',
                    'points' => $idq['points'] ?? 2,
                    'status' => 'active'
                ]);
            }
        }

        // Save essay questions
        if (isset($analysis['essay_questions']) && is_array($analysis['essay_questions']) && isset($questionTypes['Essay'])) {
            foreach ($analysis['essay_questions'] as $essay) {
                Questions::create([
                    'question_type_id' => $questionTypes['Essay'],
                    'analysis_id' => $analysisModel->analysis_id,
                    'question_text' => $essay['question'] ?? '',
                    'points' => $essay['points'] ?? 10,
                    'status' => 'active'
                ]);
            }
        }

        // Save performance tasks
        if (isset($analysis['performance_tasks']) && is_array($analysis['performance_tasks'])) {
            foreach ($analysis['performance_tasks'] as $taskData) {
                $task = PerformanceTasks::create([
                    'analysis_id' => $analysisModel->analysis_id,
                    'task_name' => $taskData['name'] ?? 'Performance Task',
                    'task_description' => ($taskData['description'] ?? '') . "\n\nSuccess Criteria: " . ($taskData['success_criteria'] ?? ''),
                    'status' => 'active'
                ]);

                // Create rubrics
                $this->createDefaultRubric($task, $analysisModel);
            }
        }
    }

    /**
     * Create default rubric
     */
    private function createDefaultRubric($task, $analysisModel)
    {
        // Create the rubric
        $rubric = Rubrics::create([
            'task_id' => $task->task_id,
            'analysis_id' => $analysisModel->analysis_id,
            'rubric_title' => $task->task_name . ' Rubric',
            'total_score' => 100,
            'status' => 'active'
        ]);

        // Create the criteria entries
        $defaultCriteria = [
            [
                'criteria_name' => 'Content',
                'criteria_description' => 'Understanding and accuracy of content',
                'score' => 25
            ],
            [
                'criteria_name' => 'Organization',
                'criteria_description' => 'Logical flow and structure',
                'score' => 25
            ],
            [
                'criteria_name' => 'Presentation',
                'criteria_description' => 'Clarity and delivery',
                'score' => 25
            ],
            [
                'criteria_name' => 'Accuracy',
                'criteria_description' => 'Correctness of information',
                'score' => 25
            ]
        ];

        foreach ($defaultCriteria as $criteria) {
            RubricCriterias::create([
                'rubric_id' => $rubric->rubric_id,
                'criteria_name' => $criteria['criteria_name'],
                'criteria_description' => $criteria['criteria_description'],
                'score' => $criteria['score']
            ]);
        }
        
        return $rubric;
    }


    // Add this temporary method to test your PDF
public function testPdfTokens(Request $request)
{
    try {
        $lessonPlan = LessonPlans::with('extractContent')->find($request->lesson_id);
        $extractedText = $lessonPlan->extractContent->extracted_text;
        
        $charCount = strlen($extractedText);
        $estimatedTokens = ceil($charCount / 4);
        
        $result = [
            'filename' => $lessonPlan->file_name,
            'characters' => $charCount,
            'estimated_tokens' => $estimatedTokens,
            'groq_limit' => 8000,
            'within_limit' => $estimatedTokens <= 7500, // with buffer
            'sections' => [
                'first_500_chars' => substr($extractedText, 0, 500),
                'last_500_chars' => substr($extractedText, -500)
            ]
        ];
        
        // If it exceeds, show truncation plan
        if ($estimatedTokens > 7500) {
            $result['truncation_plan'] = [
                'need_to_remove_tokens' => $estimatedTokens - 7500,
                'need_to_remove_chars' => ($estimatedTokens - 7500) * 4,
                'will_keep_start' => '40%',
                'will_keep_end' => '40%',
                'will_keep_middle' => '20%'
            ];
        }
        
        return response()->json($result);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}