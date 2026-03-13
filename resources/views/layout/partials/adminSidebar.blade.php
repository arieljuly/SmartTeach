<aside id="adminSidebar" 
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-gradient-to-b from-indigo-600 to-indigo-800 shadow-xl">
    
    <!-- Sidebar Header -->
    <div class="h-16 flex items-center justify-between px-4 bg-indigo-700 bg-opacity-50">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
            <span class="text-xl font-bold text-white">SmartTeach</span>
        </a>
        
        <!-- Close button for mobile -->
        <button class="lg:hidden text-white hover:text-indigo-200" id="closeSidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Sidebar Content -->
    <div class="py-4 overflow-y-auto h-[calc(100vh-4rem)] scrollbar-thin scrollbar-thumb-indigo-500 scrollbar-track-indigo-500">
        <ul class="space-y-1 px-2">
            <!-- Dashboard -->
            <li class="relative">
                @if(request()->routeIs('admin.dashboard'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full z-10"></span>
                @endif
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </li>
            
            <!-- User Administration -->
            <li class="relative">
                @if(request()->routeIs('admin.users*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full z-10"></span>
                @endif
                <a href="{{ route('admin.users.administration') }}" 
                   class="flex items-center px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group {{ request()->routeIs('admin.users*') ? 'bg-indigo-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm font-medium">User Administration</span>
                </a>
            </li>
            
            <!-- Lesson Plan Module -->
            <li class="relative">
                @if(request()->routeIs('admin.lesson-plans*') || request()->routeIs('admin.lessons*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full z-10"></span>
                @endif
                <a href="{{ route('admin.lesson-plans.index') }}" 
                class="flex items-center px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group {{ request()->routeIs('admin.lesson-plans*') || request()->routeIs('admin.lessons*') ? 'bg-indigo-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-sm font-medium">Lesson Plan Module</span>
                </a>
            </li>
                        
            <!-- AI Processing Module -->
            <li class="relative">
                @if(request()->routeIs('admin.ai*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full z-10"></span>
                @endif
                <a href="{{ route('admin.ai.aiProcess') }}" 
                   class="flex items-center px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group {{ request()->routeIs('admin.ai*') ? 'bg-indigo-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium">AI Processing Module</span>
                </a>
            </li>
            
            <!-- Output Management Module (with submenu) -->
            <li x-data="{ open: {{ request()->routeIs('admin.extraction.output*') ? 'true' : 'false' }} }" class="relative">
                <!-- Active Indicator for parent when any output route is active -->
                @if(request()->routeIs('admin.extraction.output*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full z-10"></span>
                @endif
                <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span class="flex-1 text-sm font-medium text-left">Output Management</span>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4 transition-transform duration-200 text-indigo-200" 
                         :class="{ 'rotate-180': open }" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <!-- Output Management Submenu (PDF to Video & PDF to Audio only) -->
                <div x-show="open" x-transition class="pl-11 pr-2 mt-1 space-y-1">
                    <a href="{{ route('admin.extraction.pdf') }}" class="block px-4 py-2 text-sm text-indigo-100 hover:text-white hover:bg-indigo-500 rounded-lg relative {{ request()->routeIs('admin.extraction.pdfToVideo') ? 'bg-indigo-500 text-white' : '' }}">
                        @if(request()->routeIs('admin.extraction.pdfToVideo'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r-full"></span>
                        @endif
                        <i class="fas fa-video mr-2"></i> PDF Extraction
                    </a>
                    <a href="{{ route('admin.extraction.pdfToVideo') }}" class="block px-4 py-2 text-sm text-indigo-100 hover:text-white hover:bg-indigo-500 rounded-lg relative {{ request()->routeIs('admin.extraction.pdfToVideo') ? 'bg-indigo-500 text-white' : '' }}">
                        @if(request()->routeIs('admin.extraction.pdfToVideo'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r-full"></span>
                        @endif
                        <i class="fas fa-video mr-2"></i> PDF to Video
                    </a>
                    <a href="{{ route('admin.extraction.pdfToAudio') }}" class="block px-4 py-2 text-sm text-indigo-100 hover:text-white hover:bg-indigo-500 rounded-lg relative {{ request()->routeIs('admin.extraction.pdfToAudio') ? 'bg-indigo-500 text-white' : '' }}">
                        @if(request()->routeIs('admin.extraction.pdfToAudio'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r-full"></span>
                        @endif
                        <i class="fas fa-headphones-alt mr-2"></i> PDF to Audio
                    </a>
                </div>
            </li>
        </ul>
    </div>
    
    <!-- Sidebar Footer -->
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-indigo-700 bg-opacity-50">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->first_name.' '.auth()->user()->last_name).'&background=ffffff&color=6366f1&bold=true' }}" 
                     class="w-8 h-8 rounded-full border-2 border-white"
                     alt="{{ auth()->user()->first_name }}">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                </p>
                <p class="text-xs text-indigo-200 truncate">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>
    </div>
</aside>

<!-- Include Alpine.js for dropdown functionality -->
<script src="//unpkg.com/alpinejs" defer></script>

<script>
    // Close sidebar button functionality
    document.addEventListener('DOMContentLoaded', function() {
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebar = document.getElementById('adminSidebar');
        
        if (closeSidebar) {
            closeSidebar.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                const backdrop = document.querySelector('.sidebar-backdrop');
                if (backdrop) backdrop.remove();
            });
        }
    });
</script>