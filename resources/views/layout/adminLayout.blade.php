<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>@yield('title', 'CE-PRC Admin')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.18/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-['Poppins'] antialiased bg-gray-50">
    <!-- Mobile Menu Button -->
    <button class="lg:hidden fixed top-4 left-4 z-50 bg-white p-2 rounded-lg shadow-md hover:bg-gray-100 transition-colors" 
            id="mobileMenuToggle">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layout.partials.adminSidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64 transition-all duration-300" id="mainContent">
            <!-- Top Navigation -->
            <nav class="bg-white border-b border-gray-200 shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-3">
                    <div class="flex justify-end items-center">
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-2 hover:bg-gray-50 transition-colors">
                                <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->first_name.' '.auth()->user()->last_name).'&background=6366f1&color=fff&bold=true' }}" 
                                     class="w-8 h-8 rounded-full object-cover border-2 border-indigo-100"
                                     alt="{{ auth()->user()->first_name }}">
                                <span class="hidden sm:block text-sm font-medium text-gray-700">
                                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                                <a href="" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Settings
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                    @csrf
                                    <button type="button" 
                                            onclick="confirmLogout()"
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                <!-- Breadcrumbs -->
                @hasSection('breadcrumbs')
                    <div class="mb-4">
                        @yield('breadcrumbs')
                    </div>
                @endif
                <!-- Main Content -->
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Alpine.js for dropdowns and interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.8/dist/cdn.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('adminSidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebar.classList.toggle('lg:translate-x-0');
                    
                    // Create backdrop for mobile
                    if (window.innerWidth < 1024) {
                        let backdrop = document.querySelector('.sidebar-backdrop');
                        if (!backdrop) {
                            backdrop = document.createElement('div');
                            backdrop.className = 'sidebar-backdrop fixed inset-0 bg-black bg-opacity-50 z-30 transition-opacity';
                            document.body.appendChild(backdrop);
                            
                            backdrop.addEventListener('click', function() {
                                sidebar.classList.add('-translate-x-full');
                                sidebar.classList.remove('lg:translate-x-0');
                                backdrop.remove();
                            });
                        } else {
                            backdrop.remove();
                        }
                    }
                });
            }
            
            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth >= 1024) {
                        const backdrop = document.querySelector('.sidebar-backdrop');
                        if (backdrop) backdrop.remove();
                        sidebar.classList.remove('-translate-x-full');
                    }
                }, 250);
            });
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const backdrop = document.querySelector('.sidebar-backdrop');
                    if (backdrop) {
                        sidebar.classList.add('-translate-x-full');
                        backdrop.remove();
                    }
                }
            });
        });
        
        // Logout confirmation
        function confirmLogout() {
            Swal.fire({
                title: 'Logout Confirmation',
                text: 'Are you sure you want to logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, sign me out!',
                cancelButtonText: 'No, stay logged in',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>