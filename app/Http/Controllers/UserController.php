<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enum\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userAdministration(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');
        
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $users = $query->paginate(10);
        return view('admins.userAdministration', compact('users'));
    }
    public function showUser(User $user) // Route model binding
    {
        return response()->json([
            'user_id' => $user->user_id,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'role' => $user->role->value,
            'status' => $user->status
        ]);
    }
    
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,teacher,user',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'active'
        ]);

        return redirect()->route('admin.users.administration');
    }
    
    public function updateUser(Request $request, User $user) // Route model binding
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'role' => 'required|in:admin,teacher,user',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.administration')
            ->with('success', 'User updated successfully.');
    }

    public function archiveUser(User $user) // Route model binding
    {
        if ($user->user_id === Auth::id()) {
            return back()->with('error', 'You cannot archive your own account.');
        }
        
        if ($user->status === 'archived') {
            return back()->with('error', 'User is already archived.');
        }
        
        $user->update([
            'status' => 'archived'
        ]);

        Log::info('User archived by admin', [
            'admin_id' => Auth::id(), 
            'archived_user_id' => $user->user_id
        ]);

        return redirect()->route('admin.users.administration')
            ->with('success', 'User archived successfully.');
    }
    
    public function restoreUser(User $user) // Route model binding
    {
        if ($user->status === 'active') {
            return back()->with('error', 'User is already active.');
        }

        $user->update([
            'status' => 'active'
        ]);

        Log::info('User restored by admin', [
            'admin_id' => Auth::id(), 
            'restored_user_id' => $user->user_id
        ]);

        return redirect()->route('admin.users.administration')
            ->with('success', 'User restored successfully.');
    }
    
    public function showAuditLog(User $user)
    {
        return view('admins.audit', compact('user'));
    }
    
    public function auditLogs()
    {
        $logs = collect([
            (object)[
                'timestamp' => now()->subHours(2)->format('Y-m-d H:i:s'),
                'user' => (object)[
                    'name' => 'John Doe', 
                    'avatar' => 'John+Doe'
                ],
                'action' => 'CREATE',
                'module' => 'User Management',
                'description' => 'Created new user Jane Smith',
                'ip_address' => '192.168.1.105'
            ],
            (object)[
                'timestamp' => now()->subHours(5)->format('Y-m-d H:i:s'),
                'user' => (object)[
                    'name' => 'Jane Smith', 
                    'avatar' => 'Jane+Smith'
                ],
                'action' => 'UPDATE',
                'module' => 'Lesson Plans',
                'description' => 'Modified lesson plan Biology 101',
                'ip_address' => '192.168.1.120'
            ],
            (object)[
                'timestamp' => now()->subDay()->format('Y-m-d H:i:s'),
                'user' => (object)[
                    'name' => 'Bob Johnson', 
                    'avatar' => 'Bob+Johnson'
                ],
                'action' => 'LOGIN',
                'module' => 'Authentication',
                'description' => 'Successful login from new device',
                'ip_address' => '203.45.67.89'
            ],
        ]);

        return response()->json($logs);
    }
    
}
