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