@extends('layout.adminLayout')

@section('title', 'PDF Extraction & Analysis')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">PDF Extraction & Analysis</h1>
            <button type="button" onclick="document.getElementById('pdfUpload').click()" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload PDF
            </button>
        </div>

        <!-- Hidden file input -->
        <input type="file" id="pdfUpload" accept=".pdf" class="hidden" onchange="uploadPDF(this)">

        <!-- Progress indicator -->
        <div id="uploadProgress" class="hidden mt-4">
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-sm text-gray-700" id="progressMessage">Uploading and extracting PDF...</span>
                </div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" id="progressBar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Lesson Plans List -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($lessonPlans as $lesson)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-2 text-sm font-medium text-indigo-600">{{ $lesson->file_name }}</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($lesson->status == 'completed') bg-green-100 text-green-800
                                    @elseif($lesson->status == 'analyzed') bg-blue-100 text-blue-800
                                    @elseif($lesson->status == 'upload') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($lesson->status) }}
                                </span>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                Uploaded: {{ $lesson->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div class="ml-4 flex items-center space-x-3">
                            @if($lesson->status == 'upload')
                            <button onclick="confirmAnalyze({{ $lesson->lesson_id }})" 
                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Analyze
                            </button>
                            @endif

                            @if($lesson->status == 'analyzed' || $lesson->status == 'completed')
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Generate Document
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                    class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                    <div class="py-1">
                                        <button onclick="generateDocument({{ $lesson->lesson_id }}, 'complete')" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-file-pdf mr-2 text-indigo-500"></i> Complete Document
                                        </button>
                                        <button onclick="generateDocument({{ $lesson->lesson_id }}, 'activities')" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-gamepad mr-2 text-green-500"></i> Activities Only
                                        </button>
                                        <button onclick="generateDocument({{ $lesson->lesson_id }}, 'questions')" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-question-circle mr-2 text-yellow-500"></i> Questions Only
                                        </button>
                                        <button onclick="generateDocument({{ $lesson->lesson_id }}, 'tasks')" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-tasks mr-2 text-blue-500"></i> Performance Tasks
                                        </button>
                                        <button onclick="generateDocument({{ $lesson->lesson_id }}, 'rubrics')" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-list-alt mr-2 text-purple-500"></i> Rubrics Only
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($lesson->documents->count() > 0)
                            <a href="{{ Storage::url($lesson->documents->last()->file_path) }}" target="_blank"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                            @endif
                        </div>
                    </div>

                    @if($lesson->analysis)
                    <div class="mt-3 pl-7">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Summary:</span> {{ Str::limit($lesson->analysis->topic_summary, 100) }}
                        </div>
                    </div>
                    @endif
                </li>
                @empty
                <li class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No lesson plans</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by uploading a PDF lesson plan.</p>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
function uploadPDF(input) {
    console.log("1. uploadPDF function STARTED");
    console.log("2. Input element:", input);
    
    const file = input.files[0];
    console.log("3. Selected file:", file ? file.name : 'No file');
    
    if (!file) {
        alert('No file selected');
        return;
    }

    // Show progress
    const progressDiv = document.getElementById('uploadProgress');
    console.log("4. Progress div:", progressDiv);
    
    if (progressDiv) {
        progressDiv.classList.remove('hidden');
    } else {
        console.error('Progress div not found!');
    }

    const formData = new FormData();
    formData.append('pdf_file', file);
    console.log("5. FormData created");

    // Get CSRF token
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    console.log("6. CSRF meta tag:", metaTag);
    
    const csrfToken = metaTag ? metaTag.getAttribute('content') : null;
    console.log("7. CSRF token:", csrfToken ? 'Found' : 'Not found');

    // USE THE NAMED RUTE INSTEAD OF HARDCODED URL
    const url = '{{ route("admin.pdf.upload") }}';
    console.log("8. Fetch URL:", url);

    // Make the fetch request
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log("9. Response received:", response.status);
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        console.log("10. Response data:", data);
        if (data.success) {
            document.getElementById('progressMessage').textContent = 'Upload successful! Refreshing...';
            document.getElementById('progressBar').style.width = '100%';
            
            // Refresh the page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            alert('Upload failed: ' + data.message);
            document.getElementById('uploadProgress').classList.add('hidden');
        }
    })
    .catch(error => {
        console.error("11. Error:", error);
        alert('Error: ' + (error.message || JSON.stringify(error)));
        document.getElementById('uploadProgress').classList.add('hidden');
    });
}
function checkAnalysisProgress(lessonId) {
    const interval = setInterval(() => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/admin/analysis-progress/${lessonId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'rate_limited') {
                    document.getElementById('progressMessage').textContent = 
                        `⏳ ${data.message}`;
                } else if (data.status === 'analyzing') {
                    document.getElementById('progressBar').style.width = data.progress + '%';
                    document.getElementById('progressMessage').textContent = data.message;
                } else if (data.status === 'saving') {
                    document.getElementById('progressBar').style.width = '80%';
                    document.getElementById('progressMessage').textContent = 'Saving content...';
                } else if (data.status === 'completed') {
                    clearInterval(interval);
                }
            })
            .catch(() => {});
    }, 2000);
}

function confirmAnalyze(lessonId) {
    Swal.fire({
        title: 'Start AI Analysis?',
        html: `
            <div class="text-left">
                <p class="mb-3">This will analyze the lesson plan using AI and automatically generate:</p>
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    <li>📚 Topic summary and key concepts</li>
                    <li>🎯 Classroom activities with durations</li>
                    <li>❓ Multiple choice questions</li>
                    <li>✏️ Identification questions</li>
                    <li>📝 Essay questions</li>
                    <li>⚡ Performance tasks</li>
                    <li>📊 Assessment rubrics</li>
                </ul>
                <p class="mt-3 text-yellow-600 font-medium">This process may take a few minutes.</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, analyze it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                analyzeLesson(lessonId);
                resolve();
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}

function analyzeLesson(lessonId) {
    document.getElementById('uploadProgress').classList.remove('hidden');
    document.getElementById('progressBar').style.width = '10%';
    document.getElementById('progressMessage').textContent = 'Starting AI analysis...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('{{ route("admin.pdf.analyze") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ lesson_id: lessonId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('progressBar').style.width = '100%';
            document.getElementById('progressMessage').textContent = 'Analysis complete! Refreshing...';
            
            Swal.fire({
                icon: 'success',
                title: 'Analysis Complete!',
                text: 'Lesson plan analyzed successfully!',
                timer: 1500,
                showConfirmButton: false
            });
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Analysis Failed',
                text: data.message
            });
            document.getElementById('uploadProgress').classList.add('hidden');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error analyzing content: ' + error.message
        });
        document.getElementById('uploadProgress').classList.add('hidden');
    });
}

function generateDocument(lessonId, type) {
    let typeName = '';
    switch(type) {
        case 'complete': typeName = 'Complete Document'; break;
        case 'activities': typeName = 'Activities'; break;
        case 'questions': typeName = 'Questions'; break;
        case 'tasks': typeName = 'Performance Tasks'; break;
        case 'rubrics': typeName = 'Rubrics'; break;
    }

    Swal.fire({
        title: 'Generate Document',
        text: `Generate ${typeName}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, generate it!',
        showLoaderOnConfirm: true,
         preConfirm: () => {
            document.getElementById('uploadProgress').classList.remove('hidden');
            document.getElementById('progressBar').style.width = '30%';
            document.getElementById('progressMessage').textContent = 'Generating document...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            return fetch('{{ route("admin.pdf.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    lesson_id: lessonId,
                    document_type: type 
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('progressBar').style.width = '100%';
                    document.getElementById('progressMessage').textContent = 'Document generated!';
                    
                    // Open the document in new tab
                    window.open(data.file_url, '_blank');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Document Generated',
                        text: 'Your document has been generated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    setTimeout(() => {
                        document.getElementById('uploadProgress').classList.add('hidden');
                        window.location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Generation Failed',
                    text: error.message
                });
                document.getElementById('uploadProgress').classList.add('hidden');
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}

// Auto-refresh on page focus to update status
window.addEventListener('focus', function() {
    // Optional: refresh data when tab becomes active
    window.location.reload();
});
</script>
@endpush

@endsection