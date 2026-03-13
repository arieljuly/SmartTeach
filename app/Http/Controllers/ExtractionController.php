<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExtractionController extends Controller
{
    public function index()
    {
        return view('admins.pdfExtraction');
    }
    public function pdfToVideo()
    {
        return view('admins.pdfToVideo');
    }
    public function pdfToAudio()
    {
        return view('admins.pdfToAudio');
    }
    public function output()
    {
        return view('admins.outputManagement');
    }
}
