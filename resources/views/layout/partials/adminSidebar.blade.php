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