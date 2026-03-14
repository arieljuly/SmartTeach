@extends('layout.adminLayout')
@section('content')
<!-- Header with improved success/error messages -->
@if(session('success'))
<div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between" role="alert">
    <div class="flex items-center">
        <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>
@endif

@if(session('error'))
<div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between" role="alert">
    <div class="flex items-center">
        <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>
@endif

<!-- Header with stats cards -->
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">User Administration</h1>
            <p class="text-sm text-gray-500 mt-1">Manage users, roles, and permissions</p>
        </div>
        <button onclick="openCreateModal()" class="px-4 py-2 border border-green-500 text-green-500 rounded-lg hover:bg-green-500 hover:text-white transition flex items-center gap-2 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
             New User
        </button>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Archived Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->where('status', 'archived')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Admins</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->where('role.value', 'admin')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-8">
        <button onclick="switchTab('users')" id="usersTab" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            User Management
        </button>
        <button onclick="switchTab('audit')" id="auditTab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            Audit Logs
        </button>
    </nav>
</div>

<!-- User Management Tab -->
<div id="usersPanel" class="mt-6">
    <!-- Search and Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-2">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search users by name, email, or ID..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <select id="roleFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div>
                <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table - Made Responsive -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 {{ $user->status === 'archived' ? 'opacity-75' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->first_name.'+'.$user->last_name).'&background=6366f1&color=fff' }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</div>
                                    <div class="text-sm text-gray-500">#{{ $user->user_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-red-100 text-red-800',
                                    'teacher' => 'bg-green-100 text-green-800',
                                    'user' => 'bg-blue-100 text-blue-800'
                                ];
                                $roleColor = $roleColors[$user->role->value] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColor }}">
                                {{ ucfirst($user->role->value) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Archived</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($user->status === 'active')
                                <button onclick="openEditModal({{ $user->user_id }})" class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                </button>
                                @if($user->user_id !== auth()->id())
                                <button onclick="confirmArchive({{ $user->user_id }})" class="text-red-600 hover:text-red-900 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>
                                </button>
                                @endif
                            @else
                                <button onclick="confirmRestore({{ $user->user_id }})" class="text-green-600 hover:text-green-900 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-lg">No users found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $users->firstItem() }}</span> 
                        to <span class="font-medium">{{ $users->lastItem() }}</span> 
                        of <span class="font-medium">{{ $users->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Audit Logs Tab -->
<div id="auditPanel" class="mt-6 hidden">
    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="col-span-2">
                <div class="relative">
                    <input type="text" id="auditSearchInput" placeholder="Search logs..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <select id="actionFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Actions</option>
                    <option value="create">Create</option>
                    <option value="update">Update</option>
                    <option value="delete">Delete</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="archive">Archive</option>
                    <option value="restore">Restore</option>
                </select>
            </div>
            <div>
                <select id="userFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->user_id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="date" id="dateFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
    </div>

    <!-- Audit Logs Table - Made Responsive -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="auditLogsBody">
                    <!-- Audit logs will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
        <div id="auditLogsPagination" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <!-- Pagination will be loaded here -->
        </div>
    </div>
    
    <div class="mt-4 flex justify-end">
        <button onclick="exportLogs()" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export Logs
        </button>
    </div>
</div>

<!-- Include Tailwind+ Elements -->
<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

<!-- Create User Modal (Centered) -->
<el-dialog>
    <dialog id="createUserModal" aria-labelledby="createUserModal-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
        <el-dialog-backdrop class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

        <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
            <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl outline -outline-offset-1 outline-gray-200 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                
                <!-- Header with close button -->
                <div class="flex items-center justify-between px-6 pt-5 pb-2 border-b border-gray-200">
                    <h3 id="createUserModal-title" class="text-lg font-semibold text-gray-900">New User</h3>
                    <button type="button" command="close" commandfor="createUserModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Content -->
                <form method="POST" action="{{ route('admin.users.store') }}" class="px-6 py-4">
                    @csrf
                    <div class="space-y-4">
                        <!-- First, Middle, Last Name Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- First Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="first_name" id="create_first_name" placeholder="John" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Middle Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" name="middle_name" id="create_middle_name" placeholder="Ken" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="create_last_name" placeholder="Doe" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="create_email" placeholder="johndoe@example.com" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"> Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="create_password" placeholder="***********" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 pr-10">
                                <button type="button" onclick="togglePasswordVisibility('create_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="far fa-eye eye-icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="create_password_confirmation" placeholder="***********" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 pr-10">
                                <button type="button" onclick="togglePasswordVisibility('create_password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="far fa-eye eye-icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="create_role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" command="close" commandfor="createUserModal" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Create User
                        </button>
                    </div>
                </form>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>

<!-- Edit User Modal (Centered) -->
<el-dialog>
    <dialog id="editUserModal" aria-labelledby="editUserModal-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
        <el-dialog-backdrop class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

        <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
            <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl outline -outline-offset-1 outline-gray-200 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                
                <!-- Header with close button -->
                <div class="flex items-center justify-between px-6 pt-5 pb-2 border-b border-gray-200">
                    <h3 id="editUserModal-title" class="text-lg font-semibold text-gray-900">Edit User</h3>
                    <button type="button" command="close" commandfor="editUserModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Content -->
                <form id="editUserForm" method="POST" action="" class="px-6 py-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <!-- First, Middle, Last Name Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- First Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="first_name" id="edit_first_name" placeholder="John" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Middle Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" name="middle_name" id="edit_middle_name" placeholder="Ken" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="edit_last_name" placeholder="Doe" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="edit_email" placeholder="johndoe@example.com" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"> New Password (Optional)</label>
                            <div class="relative">
                                <input type="password" name="password" id="edit_password" placeholder="***********" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 pr-10">
                                <button type="button" onclick="togglePasswordVisibility('edit_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="far fa-eye eye-icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="edit_password_confirmation" placeholder="***********" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 pr-10">
                                <button type="button" onclick="togglePasswordVisibility('edit_password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="far fa-eye eye-icon"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="edit_role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" command="close" commandfor="editUserModal" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Update User
                        </button>
                    </div>
                </form>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Toggle password visibility (Font Awesome version)
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('.eye-icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function switchTab(tabName) {
        document.getElementById('usersPanel').classList.add('hidden');
        document.getElementById('auditPanel').classList.add('hidden');
        
        document.getElementById('usersTab').classList.remove('border-indigo-500', 'text-indigo-600');
        document.getElementById('usersTab').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('auditTab').classList.remove('border-indigo-500', 'text-indigo-600');
        document.getElementById('auditTab').classList.add('border-transparent', 'text-gray-500');
        
        if (tabName === 'users') {
            document.getElementById('usersPanel').classList.remove('hidden');
            document.getElementById('usersTab').classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('usersTab').classList.add('border-indigo-500', 'text-indigo-600');
        } else {
            document.getElementById('auditPanel').classList.remove('hidden');
            document.getElementById('auditTab').classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('auditTab').classList.add('border-indigo-500', 'text-indigo-600');
            loadAuditLogs();
        }
    }

    function openCreateModal() {
        document.getElementById('createUserModal').showModal();
    }

    function openEditModal(userId) {
        fetch(`/admin/users/${userId}`) 
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(user => {
                document.getElementById('edit_first_name').value = user.first_name;
                document.getElementById('edit_middle_name').value = user.middle_name || '';
                document.getElementById('edit_last_name').value = user.last_name;
                document.getElementById('edit_email').value = user.email;
                document.getElementById('edit_role').value = user.role;
                
                document.getElementById('editUserForm').action = `/admin/users/${userId}`;
                document.getElementById('editUserModal').showModal();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Failed to load user data.', 'error');
            });
    }

    function confirmArchive(userId) {
        Swal.fire({
            title: 'Archive User?',
            text: "This user will be moved to archived status. They won't be able to log in.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, archive it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}/archive`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function confirmRestore(userId) {
        Swal.fire({
            title: 'Restore User?',
            text: "This user will be restored to active status.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, restore it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}/restore`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    function loadAuditLogs() {
    fetch('{{ route("admin.users.audit") }}') 
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(logs => {
            const tbody = document.getElementById('auditLogsBody');
            tbody.innerHTML = '';
            
            logs.forEach(log => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                
                let actionClass = '';
                switch(log.action) {
                    case 'CREATE':
                        actionClass = 'bg-green-100 text-green-800';
                        break;
                    case 'UPDATE':
                        actionClass = 'bg-blue-100 text-blue-800';
                        break;
                    case 'DELETE':
                    case 'ARCHIVE':
                        actionClass = 'bg-red-100 text-red-800';
                        break;
                    case 'LOGIN':
                        actionClass = 'bg-yellow-100 text-yellow-800';
                        break;
                    case 'LOGOUT':
                        actionClass = 'bg-gray-100 text-gray-800';
                        break;
                    case 'RESTORE':
                        actionClass = 'bg-purple-100 text-purple-800';
                        break;
                }
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.timestamp}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=${log.user.avatar}&background=6366f1&color=fff" alt="">
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">${log.user.name}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${actionClass}">${log.action}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.module}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.description}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.ip_address}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error loading audit logs:', error);
        });
}

    function exportLogs() {
        window.location.href = '/admin/audit/export';
    }

    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#usersPanel tbody tr');
        
        rows.forEach(row => {
            if (row.cells.length > 1) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            }
        });
    });

    document.getElementById('roleFilter')?.addEventListener('change', filterTable);
    document.getElementById('statusFilter')?.addEventListener('change', filterTable);

    function filterTable() {
        const roleFilter = document.getElementById('roleFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#usersPanel tbody tr');
        
        rows.forEach(row => {
            if (row.cells.length < 4) return;
            
            const role = row.cells[2].textContent.trim().toLowerCase();
            const status = row.cells[3].textContent.trim().toLowerCase();
            
            let show = true;
            
            if (roleFilter && !role.includes(roleFilter)) {
                show = false;
            }
            if (statusFilter && !status.includes(statusFilter)) {
                show = false;
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
</script>
@endsection