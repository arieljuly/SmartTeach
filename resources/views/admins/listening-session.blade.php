{{-- resources/views/admins/listening-session.blade.php --}}
@extends('layout.adminLayout')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Listening Practice: {{ $lesson->file_name }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ $audios->count() }} Audio Files
                    </span>
                </div>
                
                @if($audios->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500">No audio files generated yet.</p>
                    <a href="{{ route('admin.pdf-to-audio', $lesson->lesson_id) }}" 
                       class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800">
                        Generate audio first
                    </a>
                </div>
                @else
                <div class="space-y-6">
                    @foreach($audios as $audio)
                    <div class="border rounded-lg p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-800 capitalize flex items-center">
                                @php
                                    $icons = [
                                        'script' => 'text-blue-500',
                                        'vocabulary' => 'text-green-500',
                                        'dialogue' => 'text-purple-500',
                                        'story' => 'text-orange-500',
                                        'full_lesson' => 'text-red-500'
                                    ];
                                    $iconColor = $icons[$audio->audio_type] ?? 'text-gray-500';
                                @endphp
                                <svg class="w-5 h-5 mr-2 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($audio->audio_type == 'script')
                                    <path stroke-linecap="round" stroke-linecap="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                    @elseif($audio->audio_type == 'vocabulary')
                                    <path stroke-linecap="round" stroke-linecap="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    @elseif($audio->audio_type == 'dialogue')
                                    <path stroke-linecap="round" stroke-linecap="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                    @else
                                    <path stroke-linecap="round" stroke-linecap="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    @endif
                                </svg>
                                {{ str_replace('_', ' ', $audio->audio_type) }}
                            </h3>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                {{ $audio->metadata['word_count'] ?? '0' }} words
                            </span>
                        </div>
                        
                        <div class="mb-4">
                            <audio controls class="w-full">
                                <source src="{{ $audio->audio_url ?? '#' }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                        
                        @if($audio->audio_type == 'vocabulary' && !empty($audio->metadata['words']))
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700 mb-2">Vocabulary Words:</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @foreach($audio->metadata['words'] as $word)
                                <div class="flex items-center p-2 bg-gray-50 rounded hover:bg-gray-100">
                                    <button onclick="speakWord('{{ $word }}')" class="mr-2 text-blue-500 hover:text-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                        </svg>
                                    </button>
                                    <span class="text-sm">{{ $word }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if(in_array($audio->audio_type, ['script', 'dialogue', 'story']))
                        <div class="mt-3">
                            <details class="text-sm">
                                <summary class="text-blue-600 cursor-pointer hover:text-blue-800">Show transcript</summary>
                                <div class="mt-2 bg-yellow-50 p-3 rounded max-h-40 overflow-y-auto">
                                    <p class="text-gray-700 whitespace-pre-line">{{ $audio->original_text }}</p>
                                </div>
                            </details>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-8 flex justify-center space-x-4">
                    <a href="{{ route('admin.pdf-to-audio', $lesson->lesson_id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Audio Management
                    </a>
                    <button onclick="generateAllAudio()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Generate More Audio
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function speakWord(word) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(word);
        utterance.lang = 'en-US';
        utterance.rate = 0.8;
        window.speechSynthesis.speak(utterance);
    }
}

function generateAllAudio() {
    @if(isset($lesson))
    if(confirm('Generate all audio types for this lesson?')) {
        fetch(`/admin/generate-all-audio/{{ $lesson->lesson_id }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(`Generated ${data.job_count} new audio files!`);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
    }
    @endif
}
</script>
@endsection