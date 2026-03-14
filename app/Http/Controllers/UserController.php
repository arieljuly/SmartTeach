<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userAdministration()
    {
        return view('admins.userAdministration');
    }
    public function showUser($id)
    {
        // Logic for showing user details

    }
    public function storeUser(Request $request)
    {
        // Logic for storing user

    }
    public function updateUser(Request $request, $id)
    {
        // Logic for updating user

    }
    public function archiveUser($id)
    {
        // Logic for archiving user

    }
    public function restoreUser($id)
    {
        // Logic for restoring user

    }
    public function showAuditLog($id)
    {
        // Logic for showing audit log for a specific user

    }
    public function auditLogs()
    {
        return view('admins.audit');
    }
    
}
