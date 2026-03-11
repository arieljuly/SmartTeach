<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Teach · AI lesson plan generator</title>
    <!-- Tailwind (via Vite placeholder but we include CDN for standalone) -->
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="antialiased bg-gradient-to-b from-indigo-50 via-white to-white font-sans">

<!-- Navigation (transparent / light) -->
<nav class="bg-white/80 backdrop-blur-sm shadow-sm fixed w-full z-20 border-b border-indigo-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo with smart teach flair -->
            <div class="flex items-center space-x-1">
                <a href="#" class="text-2xl font-bold bg-gradient-to-r from-indigo-700 to-purple-600 bg-clip-text text-transparent">Smart Teach</a>
                <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full font-medium ml-2">AI</span>
            </div>

            <!-- Desktop menu -->
            <div class="hidden md:flex space-x-8 font-medium">
                <a href="#home" class="text-gray-700 hover:text-indigo-600 transition">Home</a>
                <a href="#workflow" class="text-gray-700 hover:text-indigo-600 transition">How it works</a>
                <a href="#features" class="text-gray-700 hover:text-indigo-600 transition">Features</a>
                <a href="#pricing" class="text-gray-700 hover:text-indigo-600 transition">Pricing</a>
                <a href="#contact" class="text-gray-700 hover:text-indigo-600 transition">Contact</a>
            </div>

            <!-- Right side buttons: Login + Signup -->
            <div class="hidden md:flex items-center space-x-3">
               <a href="{{ route('show.login') }}" class="block py-2 text-indigo-600 font-medium"><i class="fas fa-sign-in-alt mr-2"></i>Log in</a>
                <a href="#try-now" class="bg-indigo-600 text-white px-5 py-2 rounded-full hover:bg-indigo-700 transition shadow-md hover:shadow-indigo-200 text-sm font-semibold flex items-center gap-2">
                    <i class="fas fa-chalkboard-teacher"></i> Sign up free
                </a>
            </div>

            <!-- mobile button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-700 hover:text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    <!-- mobile menu (with login link) -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-indigo-100">
        <div class="px-4 py-3 space-y-2">
            <a href="#home" class="block py-2 text-gray-700 hover:text-indigo-600">Home</a>
            <a href="#workflow" class="block py-2 text-gray-700 hover:text-indigo-600">How it works</a>
            <a href="#features" class="block py-2 text-gray-700 hover:text-indigo-600">Features</a>
            <a href="#pricing" class="block py-2 text-gray-700 hover:text-indigo-600">Pricing</a>
            <a href="#contact" class="block py-2 text-gray-700 hover:text-indigo-600">Contact</a>
            <!-- mobile login & signup -->
            <div class="border-t border-gray-100 pt-3 mt-2 space-y-2">
                <a href="{{ route('show.login') }}" class="block py-2 text-indigo-600 font-medium"><i class="fas fa-sign-in-alt mr-2"></i>Log in</a>
                <a href="#try-now" class="block py-2 bg-indigo-600 text-white text-center rounded-full">Sign up free</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO section – strong teacher value prop -->
<section id="home" class="pt-28 pb-20 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-14 items-center">
            <div class="space-y-6">
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-4 py-1.5 text-sm font-medium text-indigo-800 border border-indigo-200">
                    <i class="fas fa-robot mr-2"></i> AI-powered lesson design
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight">
                    From <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">PDF lesson plan</span> to complete teaching kit in seconds
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl">
                    Upload your existing plan or a blank template. Our AI extracts, enriches and generates learning objectives, activities, quizzes (MCQ, identification, essay), performance tasks, and rubrics — all ready to download as a structured PDF.
                </p>
                <!-- stats / teacher social proof -->
                <div class="flex flex-wrap items-center gap-6 pt-2">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/32.jpg" alt="teacher">
                        <img class="w-10 h-10 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/43.jpg" alt="teacher">
                        <img class="w-10 h-10 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/68.jpg" alt="teacher">
                        <img class="w-10 h-10 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/79.jpg" alt="teacher">
                    </div>
                    <div class="text-sm text-gray-600"><span class="font-bold text-gray-900 text-lg">4,200+</span> teachers saved <span class="font-semibold text-indigo-600">12h/week</span></div>
                </div>
                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="#try-now" class="bg-indigo-600 text-white px-8 py-4 rounded-full hover:bg-indigo-700 transition shadow-xl text-lg font-semibold flex items-center gap-3 shadow-indigo-200">
                        <i class="fas fa-cloud-upload-alt"></i> Upload your first PDF – it's free
                    </a>
                    <a href="#workflow" class="border-2 border-indigo-300 text-indigo-700 px-8 py-4 rounded-full hover:bg-indigo-50 transition text-lg font-semibold flex items-center gap-2">
                        <i class="fas fa-play-circle"></i> See how
                    </a>
                </div>
            </div>
            <!-- right side: visual of PDF upload + magic -->
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-200 to-purple-200 rounded-3xl rotate-3 scale-105 opacity-30 blur-xl"></div>
                <div class="relative bg-white p-5 rounded-3xl shadow-2xl border border-indigo-100 animate-float">
                    <div class="flex items-center gap-3 border-b border-indigo-100 pb-4 mb-4">
                        <i class="fa-regular fa-file-pdf text-red-500 text-2xl"></i>
                        <span class="font-medium text-gray-700">lesson_plan_biology.pdf</span>
                        <span class="ml-auto bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full">uploaded</span>
                    </div>
                    <div class="space-y-3">
                        <div class="h-3 bg-indigo-100 rounded-full w-11/12"></div>
                        <div class="h-3 bg-indigo-100 rounded-full w-8/12"></div>
                        <div class="h-3 bg-indigo-100 rounded-full w-10/12"></div>
                        <div class="flex items-center gap-2 text-indigo-600 text-sm mt-4">
                            <i class="fas fa-magic"></i><span>AI is analyzing objectives, standards...</span>
                        </div>
                        <!-- output pills -->
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-sm font-medium flex items-center gap-1"><i class="fas fa-check-circle"></i> 8 learning objectives</span>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-full text-sm font-medium flex items-center gap-1"><i class="fas fa-check-circle"></i> 5 activities</span>
                            <span class="bg-purple-100 text-purple-700 px-3 py-1.5 rounded-full text-sm font-medium flex items-center gap-1"><i class="fas fa-check-circle"></i> 12 quiz Q</span>
                            <span class="bg-amber-100 text-amber-700 px-3 py-1.5 rounded-full text-sm font-medium flex items-center gap-1"><i class="fas fa-check-circle"></i> rubric</span>
                        </div>
                    </div>
                </div>
                <!-- floating badge -->
                <div class="absolute -bottom-5 -left-5 bg-white p-4 rounded-xl shadow-lg flex items-center gap-3 border border-indigo-200">
                    <i class="fa-solid fa-download text-indigo-600 text-xl"></i>
                    <div><span class="font-bold text-indigo-700">Structured PDF</span><br><span class="text-xs text-gray-500">objectives + tasks + rubrics</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How it works (workflow) with upload & AI generation steps -->
<section id="workflow" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold tracking-wide">SMART WORKFLOW</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">Three clicks to a fully-fledged lesson</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mt-4">Stop wrestling with Word and rubrics. Let AI handle the heavy lifting.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 relative">
            <div class="bg-indigo-50/30 p-8 rounded-3xl border border-indigo-100 relative">
                <div class="bg-indigo-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold mb-5 shadow-md">1</div>
                <i class="fa-regular fa-file-pdf text-4xl text-indigo-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Upload PDF</h3>
                <p class="text-gray-600">Any lesson plan, outline, or even handwritten notes scanned to PDF. Smart Teach extracts text and structure.</p>
            </div>
            <div class="bg-indigo-50/30 p-8 rounded-3xl border border-indigo-100 relative mt-6 md:mt-0">
                <div class="bg-indigo-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold mb-5 shadow-md">2</div>
                <i class="fa-solid fa-brain text-4xl text-indigo-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">AI analysis & generation</h3>
                <p class="text-gray-600">Our LLM extracts learning objectives, suggests activities, creates quizzes (MCQ, ID, essay), performance tasks, and a detailed rubric.</p>
            </div>
            <div class="bg-indigo-50/30 p-8 rounded-3xl border border-indigo-100 relative mt-6 md:mt-0">
                <div class="bg-indigo-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold mb-5 shadow-md">3</div>
                <i class="fa-solid fa-file-pdf text-4xl text-indigo-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Download structured PDF</h3>
                <p class="text-gray-600">Get a polished, printable PDF with all sections: objectives, activities, quizzes (with answer keys), tasks, and standards-aligned rubric.</p>
            </div>
        </div>
        <!-- mini demo upload mock -->
        <div class="mt-16 bg-gradient-to-r from-indigo-100 to-purple-100 p-8 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-cloud-arrow-up text-4xl text-indigo-700 bg-white p-3 rounded-2xl shadow-md"></i>
                <span class="font-medium text-gray-800">Drop your lesson plan PDF here or <span class="text-indigo-700 underline">browse</span> — we support .pdf up to 50MB</span>
            </div>
            <a href="#try-now" class="whitespace-nowrap bg-indigo-700 text-white px-8 py-4 rounded-full hover:bg-indigo-800 transition shadow-lg flex items-center gap-2 text-lg">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Generate with AI
            </a>
        </div>
    </div>
</section>

<!-- Features section – exactly what the teacher gets (UPDATED with PDF to Video & PDF to Audio) -->
<section id="features" class="py-20 bg-gradient-to-b from-white to-indigo-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Everything you automatically get</h2>
            <p class="text-xl text-gray-600 mt-3">From objectives to rubrics — in one consistent PDF</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-7">
            <div class="bg-white p-7 rounded-2xl shadow-md hover:shadow-xl transition border border-indigo-100">
                <div class="bg-indigo-100 w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-indigo-700 text-2xl"><i class="fas fa-bullseye"></i></div>
                <h3 class="text-xl font-bold mb-2">Learning objectives</h3>
                <p class="text-gray-600">Bloom’s taxonomy aligned, clear, measurable outcomes extracted from your plan.</p>
            </div>
            <div class="bg-white p-7 rounded-2xl shadow-md hover:shadow-xl transition border border-indigo-100">
                <div class="bg-indigo-100 w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-indigo-700 text-2xl"><i class="fas fa-users"></i></div>
                <h3 class="text-xl font-bold mb-2">Classroom activities</h3>
                <p class="text-gray-600">Engaging, timed, group or individual — suggestions tailored to your topic.</p>
            </div>
            <div class="bg-white p-7 rounded-2xl shadow-md hover:shadow-xl transition border border-indigo-100">
                <div class="bg-indigo-100 w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-indigo-700 text-2xl"><i class="fas fa-clipboard-list"></i></div>
                <h3 class="text-xl font-bold mb-2">Quiz questions (MCQ, ID, essay)</h3>
                <p class="text-gray-600">Multiple choice, identification, and essay questions with model answers.</p>
            </div>
            <div class="bg-white p-7 rounded-2xl shadow-md hover:shadow-xl transition border border-indigo-100">
                <div class="bg-indigo-100 w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-indigo-700 text-2xl"><i class="fas fa-tasks"></i></div>
                <h3 class="text-xl font-bold mb-2">Performance tasks</h3>
                <p class="text-gray-600">Real-world projects or presentations that demonstrate mastery.</p>
            </div>
            <div class="bg-white p-7 rounded-2xl shadow-md hover:shadow-xl transition border border-indigo-100">
                <div class="bg-indigo-100 w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-indigo-700 text-2xl"><i class="fas fa-star"></i></div>
                <h3 class="text-xl font-bold mb-2">Rubrics (grid)</h3>
                <p class="text-gray-600">Detailed criteria with levels (exemplary, proficient, etc.) ready to print.</p>
            </div>
            <div class="bg-white p-7 rounded-2xl shadow-md hover:shadow-xl transition border border-indigo-100">
                <div class="bg-indigo-100 w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-indigo-700 text-2xl"><i class="fas fa-file-export"></i></div>
                <h3 class="text-xl font-bold mb-2">Structured PDF download</h3>
                <p class="text-gray-600">Clean, organised PDF with all elements — use it directly in class.</p>
            </div>

            <!-- NEW: PDF to Video feature -->
            <div class="bg-white p-7 rounded-2xl shadow-md hover:shadow-xl transition border border-indigo-100 group">
                <div class="bg-indigo-100 w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-indigo-700 text-2xl group-hover:scale-110 transition"><i class="fas fa-video"></i></div>
                <h3 class="text-xl font-bold mb-2">PDF to Video <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full ml-2">NEW</span></h3>
                <p class="text-gray-600">Turn your lesson plan into an engaging explainer video with AI-generated visuals and narration — perfect for flipped classrooms.</p>
            </div>

            <!-- NEW: PDF to Audio (podcast/lecture) -->
            <div class="bg-white p-7 rounded-2xl shadow-md hover:shadow-xl transition border border-indigo-100 group">
                <div class="bg-indigo-100 w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-indigo-700 text-2xl group-hover:scale-110 transition"><i class="fas fa-headphones-alt"></i></div>
                <h3 class="text-xl font-bold mb-2">PDF to Audio <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full ml-2">NEW</span></h3>
                <p class="text-gray-600">Convert any lesson into a crystal-clear audio summary or lecture. Students can listen on the go — great for revision and accessibility.</p>
            </div>
        </div>

        <!-- extra announcement banner for the new features -->
        <div class="mt-12 text-center">
            <div class="inline-flex items-center gap-3 bg-indigo-100 text-indigo-800 px-6 py-3 rounded-full text-sm font-medium">
                <i class="fa-regular fa-star text-yellow-500"></i>
                <span><span class="font-bold">New:</span> Now every generation includes optional video & audio versions of your lesson plan — engage every learning style.</span>
                <i class="fa-regular fa-star text-yellow-500"></i>
            </div>
        </div>
    </div>
</section>

<!-- Teacher time saving testimonial / mini callout -->
<section class="py-12 bg-indigo-700 text-white">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <i class="fa-solid fa-quote-right text-5xl text-indigo-300 mb-4"></i>
        <p class="text-2xl md:text-3xl font-light italic">“Smart Teach reduced my Sunday planning from 5 hours to 25 minutes. The rubric generator alone is magic.”</p>
        <div class="flex items-center justify-center gap-3 mt-6">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w-12 h-12 rounded-full border-2 border-white">
            <span class="font-semibold">— Maria R., 8th grade science</span>
        </div>
    </div>
</section>

<!-- Pricing (simple, teacher-friendly) -->
<section id="pricing" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Start for free, then choose</h2>
            <p class="text-xl text-gray-600 mt-3">Every teacher gets 3 free AI generations.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <!-- free tier -->
            <div class="border border-indigo-200 rounded-3xl p-8 bg-white shadow-lg">
                <h3 class="text-2xl font-bold mb-2">Starter</h3>
                <p class="text-gray-500 mb-6">For individual teachers</p>
                <div class="mb-6"><span class="text-5xl font-bold">$0</span> <span class="text-gray-500">/mo</span></div>
                <ul class="space-y-3 mb-8 text-gray-600">
                    <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> 3 PDF uploads / month</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> All generation features</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> PDF download</li>
                </ul>
                <a href="#try-now" class="block text-center border-2 border-indigo-600 text-indigo-700 py-3 rounded-full font-semibold hover:bg-indigo-50">Try free</a>
            </div>
            <!-- pro -->
            <div class="bg-indigo-600 text-white rounded-3xl p-8 shadow-2xl scale-105 border-4 border-indigo-300 relative">
                <span class="absolute -top-4 right-6 bg-yellow-400 text-indigo-900 px-4 py-1 rounded-full text-sm font-bold">MOST POPULAR</span>
                <h3 class="text-2xl font-bold mb-2">Educator Pro</h3>
                <p class="text-indigo-100 mb-6">Unlimited planning</p>
                <div class="mb-6"><span class="text-5xl font-bold">$12</span> <span class="text-indigo-200">/mo</span></div>
                <ul class="space-y-3 mb-8 text-indigo-100">
                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-white"></i> Unlimited PDF uploads</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-white"></i> Priority AI analysis</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-white"></i> Export to Word/PDF</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-white"></i> Standards alignment (Common Core)</li>
                </ul>
                <a href="#" class="block text-center bg-white text-indigo-700 py-3 rounded-full font-semibold hover:bg-indigo-50 transition shadow-lg">Choose Pro</a>
            </div>
            <!-- campus -->
            <div class="border border-indigo-200 rounded-3xl p-8 bg-white shadow-lg">
                <h3 class="text-2xl font-bold mb-2">Campus</h3>
                <p class="text-gray-500 mb-6">Up to 30 teachers</p>
                <div class="mb-6"><span class="text-5xl font-bold">$79</span> <span class="text-gray-500">/mo</span></div>
                <ul class="space-y-3 mb-8 text-gray-600">
                    <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Shared team workspace</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Admin dashboard</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check text-green-500"></i> Priority support</li>
                </ul>
                <a href="#" class="block text-center border-2 border-indigo-600 text-indigo-700 py-3 rounded-full font-semibold hover:bg-indigo-50">Contact sales</a>
            </div>
        </div>
    </div>
</section>

<!-- TRY NOW / upload zone – interactive callout -->
<section id="try-now" class="py-20 bg-indigo-50">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <span class="text-indigo-700 font-semibold">FIRST THREE GENERATIONS FREE</span>
        <h2 class="text-4xl font-bold text-gray-900 mt-3">Experience the time savings</h2>
        <p class="text-lg text-gray-600 mt-4 mb-10">Upload a real lesson plan (PDF) or use our sample.</p>
        <div class="bg-white rounded-3xl shadow-xl p-10 border-2 border-dashed border-indigo-300">
            <i class="fa-solid fa-cloud-arrow-up text-6xl text-indigo-400 mb-4"></i>
            <p class="text-2xl font-semibold text-gray-800 mb-2">Drag & drop your PDF here</p>
            <p class="text-gray-500 mb-6">or</p>
            <button class="bg-indigo-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-indigo-700 transition shadow-lg inline-flex items-center gap-3">
                <i class="fas fa-upload"></i> Browse files
            </button>
            <p class="text-sm text-gray-400 mt-6">Maximum size: 50MB · Secure, encrypted, deleted after analysis</p>
        </div>
        <div class="flex flex-wrap justify-center gap-3 mt-8 text-sm text-gray-500">
            <span class="bg-white px-4 py-2 rounded-full shadow-sm"><i class="fa-regular fa-file-pdf text-red-400 mr-1"></i> lesson_math.pdf</span>
            <span class="bg-white px-4 py-2 rounded-full shadow-sm"><i class="fa-regular fa-file-pdf text-red-400 mr-1"></i> history_week3.pdf</span>
            <span class="bg-white px-4 py-2 rounded-full shadow-sm"><i class="fa-regular fa-file-pdf text-red-400 mr-1"></i> science_template.pdf</span>
        </div>
    </div>
</section>

<!-- Contact / footer combined with final callout -->
<section id="contact" class="bg-gray-900 text-white pt-16 pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div>
                <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent mb-4">Smart Teach</h3>
                <p class="text-gray-400 text-sm">AI co-pilot for educators. Reduce prep time, increase impact.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Product</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#features" class="hover:text-white">Features</a></li>
                    <li><a href="#pricing" class="hover:text-white">Pricing</a></li>
                    <li><a href="#try-now" class="hover:text-white">Free trial</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Account</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ route('show.login') }}" class="hover:text-white flex items-center gap-2"><i class="fas fa-sign-in-alt text-indigo-400"></i> Log in</a></li>
                    <li><a href="{{ route('show.register') }}" class="hover:text-white">Sign up</a></li>
                    <li><a href="#" class="hover:text-white">Dashboard</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Contact</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><i class="fas fa-envelope mr-2 w-5"></i> hello@smartteach.ai</li>
                    <li><i class="fas fa-phone mr-2"></i> +1 (888) 789-TEACH</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-sm text-gray-500 flex flex-col md:flex-row justify-between items-center">
            <p>&copy; 2025 Smart Teach. All rights reserved.</p>
            <div class="flex space-x-5 mt-4 md:mt-0">
                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
            </div>
        </div>
    </div>
</section>

<script>
    // Mobile menu toggle
    const mobileBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileBtn) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close mobile menu when a link is clicked
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    }
    
    // Smooth scroll for anchor links only (not for regular links)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            // Only prevent default for actual anchor links on the same page
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            if (!mobileBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        }
    });

    // Close mobile menu on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>
</body>
</html>