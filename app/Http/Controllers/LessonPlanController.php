<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    public function index()
    {
        return view('admins.lessonPlan');
    }
}
