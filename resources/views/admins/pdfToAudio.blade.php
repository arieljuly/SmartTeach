{{-- resources/views/admins/pdfToAudio.blade.php --}}
@extends('layout.adminLayout')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with title only -->
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">PDF To Audio Output</h1>

        {{-- Lesson Selection Grid --}}
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Available Lessons
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Select a lesson to generate audio from its extracted content
                </p>
            </div>
            
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($lessons as $lessonItem)
                    <a href="{{ route('admin.pdf-to-audio', $lessonItem->lesson_id) }}" 
                       class="border rounded-lg p-5 hover:shadow-lg transition-all duration-200 {{ $selectedLessonId == $lessonItem->lesson_id ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-200 hover:border-blue-300' }}">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900">{{ $lessonItem->file_name }}</h4>
                                    @if($lessonItem->extractContent)
                                        <span class="ml-2 flex-shrink-0">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linecap="round" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Uploaded: {{ $lessonItem->created_at->format('M d, Y') }}
                                </p>
                                <div class="mt-3 flex items-center space-x-2">
                                    @php
                                        $audioCount = \App\Models\AudioListeningJobs::where('lesson_id', $lessonItem->lesson_id)->count();
                                        $hasContent = $lessonItem->extractContent ? true : false;
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $audioCount > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $audioCount }} audio files
                                    </span>
                                    @if($hasContent)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Extracted
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending Extraction
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @if($selectedLessonId == $lessonItem->lesson_id)
                            <svg class="w-6 h-6 text-blue-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="col-span-3 text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500">No lessons found.</p>
                        <a href="{{ route('admin.pdf.extraction') }}" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Upload a PDF to get started
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        {{-- Selected Lesson Content --}}
        @if(isset($lesson) && $lesson)
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $lesson->file_name }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Audio materials generated from extracted content
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        @if($extractedContent)
                        <button onclick="generateAllAudio()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Generate All Audio
                        </button>
                        <a href="{{ route('admin.listening-session', $lesson->lesson_id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                            </svg>
                            Listening Session
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200">
                <div class="bg-gray-50 px-4 py-5 sm:px-6">
                    @if(!$extractedContent)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-gray-500">No content extracted from this PDF yet.</p>
                        <a href="{{ route('admin.pdf.extraction') }}" class="mt-3 text-blue-600 hover:text-blue-800">
                            Extract content first
                        </a>
                    </div>
                    @else
                    <h4 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Extracted Content Preview
                    </h4>
                    
                    <div class="space-y-6">
                        {{-- Teacher Script --}}
                        <div class="border rounded-lg p-4 bg-white">
                            <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                                Teacher Script
                            </h5>
                            
                            @php $scriptAudio = $audioJobs->where('audio_type', 'script')->first(); @endphp
                            
                            @if($scriptAudio)
                                <div class="flex flex-col space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <button onclick="speakTextFromAttribute(this)" 
                                                data-text="{{ $scriptAudio->original_text }}"
                                                data-job-id="{{ $scriptAudio->job_id }}"
                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center play-audio-btn">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                            </svg>
                                            Play Script
                                        </button>
                                        <button onclick="stopSpeaking()" 
                                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Stop
                                        </button>
                                        <span class="text-xs text-gray-500" id="status-{{ $scriptAudio->job_id }}"></span>
                                    </div>
                                    
                                    {{-- Script Display --}}
                                    <div class="mt-3 bg-blue-50 p-4 rounded-lg border border-blue-100">
                                        <p class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linecap="round" d="M4 6h16M4 12h16M4 18h7"></path>
                                            </svg>
                                            Script:
                                        </p>
                                        <div class="text-sm text-gray-700 whitespace-pre-line max-h-60 overflow-y-auto p-3 bg-white rounded border border-blue-100">
                                            {{ $scriptAudio->original_text }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button onclick="generateAudio('script')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linecap="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Generate Script Audio
                                </button>
                            @endif
                        </div>
                        
                        {{-- Vocabulary Words --}}
                        <div class="border rounded-lg p-4 bg-white">
                            <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Vocabulary Words
                            </h5>
                            
                            @php $vocabAudio = $audioJobs->where('audio_type', 'vocabulary')->first(); @endphp
                            
                            @if($vocabAudio)
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <button onclick="speakTextFromAttribute(this)" 
                                                data-text="{{ $vocabAudio->original_text }}"
                                                data-job-id="{{ $vocabAudio->job_id }}"
                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded play-audio-btn">
                                            Play All Vocabulary
                                        </button>
                                        <button onclick="stopSpeaking()" 
                                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Stop
                                        </button>
                                        <span class="text-xs text-gray-500" id="status-{{ $vocabAudio->job_id }}"></span>
                                    </div>
                                    
                                    {{-- Vocabulary List Display --}}
                                    <div class="mt-3 bg-green-50 p-4 rounded-lg border border-green-100">
                                        <p class="text-sm font-medium text-green-800 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            Vocabulary List:
                                        </p>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                            @php
                                                $words = $vocabAudio->metadata['words'] ?? 
                                                        ['Backpack', 'Basketball', 'Laptop', 'Headphones', 'Bicycle', 'Breakfast'];
                                            @endphp
                                            @foreach($words as $word)
                                            <div class="flex items-center p-2 bg-white rounded border border-green-100 hover:bg-green-50">
                                                <button onclick="speakWord('{{ $word }}')" class="mr-2 text-green-600 hover:text-green-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                                    </svg>
                                                </button>
                                                <span class="text-sm font-medium text-gray-700">{{ $word }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    {{-- Full Vocabulary Script --}}
                                    <div class="mt-2">
                                        <details class="text-sm">
                                            <summary class="text-green-600 cursor-pointer hover:text-green-800 font-medium">
                                                View full vocabulary script
                                            </summary>
                                            <div class="mt-2 bg-green-50 p-3 rounded-lg border border-green-100 text-sm text-gray-700 whitespace-pre-line max-h-40 overflow-y-auto">
                                                {{ $vocabAudio->original_text }}
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            @else
                                <button onclick="generateAudio('vocabulary')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linecap="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Generate Vocabulary Audio
                                </button>
                            @endif
                        </div>
                        
                        {{-- Cafe Role Play / Dialogue --}}
                        <div class="border rounded-lg p-4 bg-white">
                            <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                                Dialogue / Role Play
                                <span class="ml-2 text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full">2 Voices</span>
                            </h5>
                            
                            @php $dialogueAudio = $audioJobs->where('audio_type', 'dialogue')->first(); @endphp
                            
                            @if($dialogueAudio)
                                <div class="flex items-center space-x-3 mb-4">
                                    <button onclick="playDialogue('{{ $dialogueAudio->job_id }}')" 
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                        </svg>
                                        Play Full Dialogue
                                    </button>
                                    <button onclick="stopSpeaking()" 
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                        Stop
                                    </button>
                                    <span class="text-xs text-gray-500" id="dialogue-status-{{ $dialogueAudio->job_id }}"></span>
                                </div>
                                
                                {{-- Individual dialogue lines with separate voice buttons --}}
                                <div class="space-y-2 mb-4">
                                    @php
                                        $lines = explode("\n", $dialogueAudio->original_text);
                                    @endphp
                                    
                                    @foreach($lines as $index => $line)
                                        @if(trim($line))
                                            @php
                                                // Determine speaker from line
                                                $speaker = 'Person A';
                                                $text = $line;
                                                $voiceType = 'male';
                                                $bgColor = 'bg-blue-50';
                                                $borderColor = 'border-blue-200';
                                                $textColor = 'text-blue-700';
                                                
                                                if (strpos($line, 'Waiter:') === 0) {
                                                    $speaker = 'Waiter';
                                                    $text = substr($line, 7);
                                                    $voiceType = 'male';
                                                    $bgColor = 'bg-blue-50';
                                                    $borderColor = 'border-blue-200';
                                                    $textColor = 'text-blue-700';
                                                } elseif (strpos($line, 'Customer:') === 0) {
                                                    $speaker = 'Customer';
                                                    $text = substr($line, 9);
                                                    $voiceType = 'female';
                                                    $bgColor = 'bg-pink-50';
                                                    $borderColor = 'border-pink-200';
                                                    $textColor = 'text-pink-700';
                                                } elseif (strpos($line, 'Person A:') === 0) {
                                                    $speaker = 'Person A';
                                                    $text = substr($line, 9);
                                                    $voiceType = 'male';
                                                    $bgColor = 'bg-blue-50';
                                                    $borderColor = 'border-blue-200';
                                                    $textColor = 'text-blue-700';
                                                } elseif (strpos($line, 'Person B:') === 0) {
                                                    $speaker = 'Person B';
                                                    $text = substr($line, 9);
                                                    $voiceType = 'female';
                                                    $bgColor = 'bg-pink-50';
                                                    $borderColor = 'border-pink-200';
                                                    $textColor = 'text-pink-700';
                                                } elseif ($index % 2 == 0) {
                                                    $speaker = 'Waiter';
                                                    $voiceType = 'male';
                                                    $bgColor = 'bg-blue-50';
                                                    $borderColor = 'border-blue-200';
                                                    $textColor = 'text-blue-700';
                                                } else {
                                                    $speaker = 'Customer';
                                                    $voiceType = 'female';
                                                    $bgColor = 'bg-pink-50';
                                                    $borderColor = 'border-pink-200';
                                                    $textColor = 'text-pink-700';
                                                }
                                            @endphp
                                            
                                            <div class="flex items-center space-x-2 {{ $bgColor }} p-3 rounded-lg border {{ $borderColor }}">
                                                <button onclick="playLine('{{ $text }}', '{{ $voiceType }}', this)" 
                                                        class="text-gray-600 hover:text-gray-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                                    </svg>
                                                </button>
                                                <span class="text-xs font-medium {{ $textColor }} bg-white px-2 py-1 rounded-full border {{ $borderColor }}">
                                                    {{ $speaker }}:
                                                </span>
                                                <span class="text-sm text-gray-700 flex-1">{{ $text }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                
                                {{-- Full Dialogue Script --}}
                                <div class="mt-3 bg-purple-50 p-4 rounded-lg border border-purple-100">
                                    <p class="text-sm font-medium text-purple-800 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linecap="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                        Full Dialogue Script:
                                    </p>
                                    <div class="text-sm text-gray-700 whitespace-pre-line max-h-60 overflow-y-auto p-3 bg-white rounded border border-purple-100">
                                        {{ $dialogueAudio->original_text }}
                                    </div>
                                </div>
                            @else
                                <button onclick="generateAudio('dialogue')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linecap="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Generate Dialogue Audio
                                </button>
                            @endif
                        </div>
                        
                        {{-- Story / Main Text --}}
                        <div class="border rounded-lg p-4 bg-white">
                            <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Story / Main Text
                            </h5>
                            
                            @php 
                                $storyAudio = $audioJobs->where('audio_type', 'story')->first() ?? 
                                              $audioJobs->where('audio_type', 'full_lesson')->first(); 
                            @endphp
                            
                            @if($storyAudio)
                                <div class="flex items-center space-x-3 mb-3">
                                    <button onclick="speakTextFromAttribute(this)" 
                                            data-text="{{ $storyAudio->original_text }}"
                                            data-job-id="{{ $storyAudio->job_id }}"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center play-audio-btn">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                        </svg>
                                        Play Story
                                    </button>
                                    <button onclick="stopSpeaking()" 
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                        Stop
                                    </button>
                                    <span class="text-xs text-gray-500" id="status-{{ $storyAudio->job_id }}"></span>
                                </div>
                                
                                {{-- Story Script Display --}}
                                <div class="mt-3 bg-orange-50 p-4 rounded-lg border border-orange-100">
                                    <p class="text-sm font-medium text-orange-800 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linecap="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        Story Text:
                                    </p>
                                    <div class="text-sm text-gray-700 whitespace-pre-line max-h-60 overflow-y-auto p-3 bg-white rounded border border-orange-100">
                                        {{ $storyAudio->original_text }}
                                    </div>
                                </div>
                            @else
                                <button onclick="generateAudio('story')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linecap="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Generate Story Audio
                                </button>
                            @endif
                        </div>
                        
                        {{-- Full Lesson (if not already shown) --}}
                        @if(!$storyAudio && !$audioJobs->where('audio_type', 'full_lesson')->isEmpty())
                        <div class="border rounded-lg p-4 bg-white">
                            <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Full Lesson
                            </h5>
                            
                            @php $fullAudio = $audioJobs->where('audio_type', 'full_lesson')->first(); @endphp
                            
                            @if($fullAudio)
                                <div class="flex items-center space-x-3 mb-3">
                                    <button onclick="speakTextFromAttribute(this)" 
                                            data-text="{{ $fullAudio->original_text }}"
                                            data-job-id="{{ $fullAudio->job_id }}"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center play-audio-btn">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linecap="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                        </svg>
                                        Play Full Lesson
                                    </button>
                                    <button onclick="stopSpeaking()" 
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                        Stop
                                    </button>
                                    <span class="text-xs text-gray-500" id="status-{{ $fullAudio->job_id }}"></span>
                                </div>
                                
                                {{-- Full Lesson Script Display --}}
                                <div class="mt-3 bg-red-50 p-4 rounded-lg border border-red-100">
                                    <p class="text-sm font-medium text-red-800 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linecap="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Complete Lesson Text:
                                    </p>
                                    <div class="text-sm text-gray-700 whitespace-pre-line max-h-60 overflow-y-auto p-3 bg-white rounded border border-red-100">
                                        {{ $fullAudio->original_text }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
let currentUtterance = null;
let utteranceQueue = [];
let isPlaying = false;

// Function to speak text from data attribute
function speakTextFromAttribute(button) {
    const text = button.getAttribute('data-text');
    const jobId = button.getAttribute('data-job-id');
    
    if (!text) {
        alert('No text found to speak');
        return;
    }
    
    const statusEl = document.getElementById(`status-${jobId}`);
    
    // Stop any current speech
    if (window.speechSynthesis) {
        window.speechSynthesis.cancel();
    }
    
    // Create new utterance with the text from data attribute
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'en-US';
    utterance.rate = 0.9;
    utterance.pitch = 1;
    utterance.volume = 1;
    
    // Use the getBestVoice function
    const bestVoice = getBestVoice();
    if (bestVoice) {
        utterance.voice = bestVoice;
    }
    
    utterance.onstart = function() {
        if (statusEl) statusEl.innerText = 'Playing...';
        button.classList.add('opacity-75');
        currentUtterance = utterance;
    };
    
    utterance.onend = function() {
        if (statusEl) statusEl.innerText = 'Done';
        button.classList.remove('opacity-75');
        currentUtterance = null;
    };
    
    utterance.onerror = function(event) {
        if (statusEl) statusEl.innerText = 'Error';
        button.classList.remove('opacity-75');
        console.error('Speech error:', event);
        currentUtterance = null;
    };
    
    window.speechSynthesis.speak(utterance);
}

// Function to play full dialogue with alternating voices
function playDialogue(jobId) {
    const button = event.currentTarget;
    const card = button.closest('.border');
    const lines = [];
    
    // Collect all dialogue lines with their voice types
    card.querySelectorAll('.rounded-lg').forEach((lineDiv, index) => {
        const speakerSpan = lineDiv.querySelector('span.font-medium');
        const textSpan = lineDiv.querySelector('span.text-sm');
        
        if (speakerSpan && textSpan) {
            const speaker = speakerSpan.innerText.replace(':', '');
            const text = textSpan.innerText;
            const voiceType = (speaker === 'Waiter' || speaker === 'Person A') ? 'male' : 'female';
            
            lines.push({
                text: text,
                voiceType: voiceType,
                speaker: speaker
            });
        }
    });
    
    if (lines.length === 0) {
        alert('No dialogue lines found');
        return;
    }
    
    const statusEl = document.getElementById(`dialogue-status-${jobId}`);
    
    // Stop any current speech
    if (window.speechSynthesis) {
        window.speechSynthesis.cancel();
    }
    
    // Play lines sequentially
    playDialogueLines(lines, 0, statusEl, button);
}

// Function to play dialogue lines sequentially
function playDialogueLines(lines, index, statusEl, button) {
    if (index >= lines.length) {
        statusEl.innerText = 'Done';
        button.classList.remove('opacity-75');
        isPlaying = false;
        return;
    }
    
    isPlaying = true;
    const line = lines[index];
    statusEl.innerText = `Playing: ${line.speaker}...`;
    
    // Create utterance for this line
    const utterance = new SpeechSynthesisUtterance(line.text);
    utterance.lang = 'en-US';
    utterance.rate = 0.9;
    utterance.pitch = 1;
    
    // Set voice based on speaker
    const voices = window.speechSynthesis.getVoices();
    if (line.voiceType === 'male') {
        // Prefer male voice
        const maleVoice = voices.find(v => 
            v.name.includes('David') || 
            v.name.includes('Google UK English Male') ||
            (v.lang.includes('en-US') && v.name.includes('Male'))
        ) || voices.find(v => v.name.includes('Google UK English Male'));
        
        if (maleVoice) utterance.voice = maleVoice;
    } else {
        // Prefer female voice
        const femaleVoice = voices.find(v => 
            v.name.includes('Zira') || 
            v.name.includes('Google UK English Female') ||
            v.name.includes('Samantha')
        ) || voices.find(v => v.name.includes('Google UK English Female'));
        
        if (femaleVoice) utterance.voice = femaleVoice;
    }
    
    utterance.onend = function() {
        // Play next line
        playDialogueLines(lines, index + 1, statusEl, button);
    };
    
    utterance.onerror = function() {
        console.error('Error playing line');
        playDialogueLines(lines, index + 1, statusEl, button);
    };
    
    currentUtterance = utterance;
    window.speechSynthesis.speak(utterance);
}

// Function to play a single line with specified voice type
function playLine(text, voiceType, button) {
    if (!window.speechSynthesis) {
        alert('Text-to-speech not supported');
        return;
    }
    
    window.speechSynthesis.cancel();
    
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'en-US';
    utterance.rate = 0.9;
    utterance.pitch = 1;
    
    const voices = window.speechSynthesis.getVoices();
    
    if (voiceType === 'male') {
        const maleVoice = voices.find(v => 
            v.name.includes('David') || 
            v.name.includes('Google UK English Male')
        );
        if (maleVoice) utterance.voice = maleVoice;
    } else {
        const femaleVoice = voices.find(v => 
            v.name.includes('Zira') || 
            v.name.includes('Google UK English Female') ||
            v.name.includes('Samantha')
        );
        if (femaleVoice) utterance.voice = femaleVoice;
    }
    
    utterance.onstart = function() {
        button.classList.add('opacity-75');
    };
    
    utterance.onend = function() {
        button.classList.remove('opacity-75');
    };
    
    window.speechSynthesis.speak(utterance);
}

// Get the best available voice
function getBestVoice() {
    const voices = window.speechSynthesis.getVoices();
    
    // Priority order for voices
    const voicePreferences = [
        'Google UK English Female',
        'Google UK English Male',
        'Microsoft David',
        'Microsoft Zira',
        'Samantha',
        'Karen'
    ];
    
    // Try to find preferred voices
    for (let pref of voicePreferences) {
        const found = voices.find(v => v.name.includes(pref));
        if (found) return found;
    }
    
    // Fallback to any English US voice
    return voices.find(v => v.lang.includes('en-US'));
}

function speakWord(word) {
    if (!window.speechSynthesis) {
        alert('Text-to-speech not supported in this browser');
        return;
    }
    
    window.speechSynthesis.cancel();
    
    const utterance = new SpeechSynthesisUtterance(word);
    utterance.lang = 'en-US';
    utterance.rate = 0.8;
    utterance.pitch = 1;
    
    const bestVoice = getBestVoice();
    if (bestVoice) utterance.voice = bestVoice;
    
    window.speechSynthesis.speak(utterance);
}

function stopSpeaking() {
    if (window.speechSynthesis) {
        window.speechSynthesis.cancel();
    }
    
    // Clear all status messages
    document.querySelectorAll('[id^="status-"], [id^="dialogue-status-"]').forEach(el => {
        el.innerText = '';
    });
    
    // Remove opacity from all buttons
    document.querySelectorAll('button').forEach(btn => {
        btn.classList.remove('opacity-75');
    });
    
    currentUtterance = null;
    isPlaying = false;
    utteranceQueue = [];
}

function generateAudio(type) {
    @if(isset($lesson) && $lesson)
    fetch(`/admin/generate-audio/{{ $lesson->lesson_id }}/${type}`, {
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
            alert('Audio ready! Click the play button to listen.');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating audio.');
    });
    @else
    alert('Please select a lesson first.');
    @endif
}

function generateAllAudio() {
    @if(isset($lesson) && $lesson)
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
                alert(`Successfully created ${data.job_count} audio files!`);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while generating audio.');
        });
    }
    @else
    alert('Please select a lesson first.');
    @endif
}

// Load voices when they become available
window.speechSynthesis.onvoiceschanged = function() {
    console.log('Voices loaded:', window.speechSynthesis.getVoices().length);
};

// Check browser support
if (!window.speechSynthesis) {
    alert('Your browser does not support text-to-speech. Please use Chrome, Edge, or Safari for the best experience.');
}
</script>
@endpush
@endsection