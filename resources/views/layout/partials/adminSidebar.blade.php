<aside id="adminSidebar" 
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-gradient-to-b from-indigo-600 to-indigo-800 shadow-xl">
    
    <!-- Sidebar Header -->
    <div class="h-16 flex items-center justify-between px-4 bg-indigo-700 bg-opacity-50">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
            <span class="text-xl font-bold text-white">CE-PRC</span>
            <span class="px-2 py-1 bg-indigo-500 text-white text-xs rounded-lg">Admin</span>
        </a>
        
        <!-- Close button for mobile -->
        <button class="lg:hidden text-white hover:text-indigo-200" id="closeSidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Sidebar Content -->
    <div class="py-4 overflow-y-auto h-[calc(100vh-4rem)] scrollbar-thin scrollbar-thumb-indigo-500 scrollbar-track-indigo-700">
        <ul class="space-y-1 px-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </li>
            
            <!-- Users Management -->
            <li x-data="{ open: {{ request()->routeIs('admin.users*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="flex-1 text-sm font-medium text-left">Users</span>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4 transition-transform duration-200 text-indigo-200" 
                         :class="{ 'rotate-180': open }" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <ul x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="pl-10 pr-2 py-2 space-y-1">
                    <li>
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.users.index') ? 'bg-indigo-500 text-white' : '' }}">
                            All Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.create') }}" 
                           class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.users.create') ? 'bg-indigo-500 text-white' : '' }}">
                            Add New User
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Teachers Management -->
            <li x-data="{ open: {{ request()->routeIs('admin.teachers*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="flex-1 text-sm font-medium text-left">Teachers</span>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4 transition-transform duration-200 text-indigo-200" 
                         :class="{ 'rotate-180': open }" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <ul x-show="open" 
                    class="pl-10 pr-2 py-2 space-y-1">
                    <li>
                        <a href="{{ route('admin.teachers.index') }}" 
                           class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            All Teachers
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.teachers.create') }}" 
                           class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            Add Teacher
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Students Management -->
            <li x-data="{ open: {{ request()->routeIs('admin.students*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                    <span class="flex-1 text-sm font-medium text-left">Students</span>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4 transition-transform duration-200 text-indigo-200" 
                         :class="{ 'rotate-180': open }" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <ul x-show="open" 
                    class="pl-10 pr-2 py-2 space-y-1">
                    <li>
                        <a href="{{ route('admin.students.index') }}" 
                           class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            All Students
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.students.create') }}" 
                           class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            Add Student
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Courses Management -->
            <li x-data="{ open: {{ request()->routeIs('admin.courses*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="flex-1 text-sm font-medium text-left">Courses</span>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4 transition-transform duration-200 text-indigo-200" 
                         :class="{ 'rotate-180': open }" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <ul x-show="open" 
                    class="pl-10 pr-2 py-2 space-y-1">
                    <li>
                        <a href="{{ route('admin.courses.index') }}" 
                           class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            All Courses
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.courses.create') }}" 
                           class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            Add Course
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Schedule -->
            <li>
                <a href="{{ route('admin.schedule') }}" 
                   class="flex items-center px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group {{ request()->routeIs('admin.schedule') ? 'bg-indigo-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium">Schedule</span>
                </a>
            </li>
            
            <!-- Reports -->
            <li x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="flex-1 text-sm font-medium text-left">Reports</span>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4 transition-transform duration-200 text-indigo-200" 
                         :class="{ 'rotate-180': open }" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <ul x-show="open" 
                    class="pl-10 pr-2 py-2 space-y-1">
                    <li>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            Attendance Reports
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            Grade Reports
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-indigo-100 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors">
                            Financial Reports
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Settings -->
            <li>
                <a href="{{ route('settings') }}" 
                   class="flex items-center px-4 py-3 text-white hover:bg-indigo-500 rounded-lg transition-colors group {{ request()->routeIs('settings') ? 'bg-indigo-500' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-medium">Settings</span>
                </a>
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