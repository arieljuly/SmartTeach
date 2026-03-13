<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userAdministration()
    {
        return view('admins.userAdministration');
    }
    public function auditLogs()
    {
        return view('admins.audit');
    }
    
}
