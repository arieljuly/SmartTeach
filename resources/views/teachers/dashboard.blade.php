@extends('layout.teacherLayout')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Teacher Dashboard</h1>
        
        <div class="mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Welcome, {{ auth()->user()->first_name }}! You are logged in as a teacher.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection