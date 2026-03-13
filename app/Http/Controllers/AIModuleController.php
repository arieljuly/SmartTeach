<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIModuleController extends Controller
{
    public function index()
    {
        return view('admins.aiProcess');
    }
}
