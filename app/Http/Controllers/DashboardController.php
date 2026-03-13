<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('userDashboard');
    }
    public function teacherDashboard()
    {
        return view('teachers.dashboard');
    }
    public function adminDashboard()
    {
        return view('admins.dashboard');
    }
}
