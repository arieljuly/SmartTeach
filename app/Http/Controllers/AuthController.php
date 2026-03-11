<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Enum\UserRole;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            Log::info('Starting Google callback');
            
            $googleUser = Socialite::driver('google')->user();
            
            Log::info('Google user retrieved', ['email' => $googleUser->email]);
            
            // Check if user exists with this email
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                // Split Google name into first and last name
                $nameParts = explode(' ', $googleUser->name, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';
                
                // Generate a random password for Google users
                $randomPassword = Hash::make(bin2hex(random_bytes(16)));
                
                // Create new user with default role (user)
                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => $randomPassword,
                    'role' => UserRole::USER->value,
                    'email_verified_at' => now(),
                ]);
                
                Log::info('New user created via Google', ['user_id' => $user->user_id]);
            } else {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                    Log::info('Existing user updated with Google ID', ['user_id' => $user->user_id]);
                }
            }
            
            // Log the user in
            Auth::login($user);
            
            // Redirect based on role
            return $this->redirectBasedOnRole();
            
        } catch (\Exception $e) {
            Log::error('Google authentication failed:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return redirect()->route('show.login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        Log::info('Showing login form');
        return view('auth.login');
    }

    /**
     * Handle login request
     */
        public function login(Request $request)
    {
        try {
            Log::info('Login attempt started', ['email' => $request->email]);
            
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            Log::info('Credentials validated');

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                
                $user = Auth::user();
                Log::info('Login successful', [
                    'user_id' => $user->user_id,
                    'role' => $user->role,
                    'email' => $user->email
                ]);
                
                return $this->redirectBasedOnRole();
            }

            Log::info('Login failed - invalid credentials', ['email' => $request->email]);
            
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
            
        } catch (\Exception $e) {
            Log::error('Login exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a user-friendly error
            return back()->with('error', 'An error occurred during login. Please try again.');
        }
    }

    

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        Log::info('Registration attempt started', ['email' => $request->email]);
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,teacher,user'],
        ]);

        Log::info('Registration validation passed');

        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Log::info('User created', ['user_id' => $user->user_id ?? $user->id]);

        Auth::login($user);

        // Redirect based on role
        return $this->redirectBasedOnRole();
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

   /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole()
    {
        $user = Auth::user();
        
        if (!$user) {
            Log::error('No user found in redirectBasedOnRole');
            return redirect()->route('show.login');
        }
        
        Log::info('Redirecting user based on role', [
            'user_id' => $user->user_id,
            'role' => $user->role
        ]);
        
        // Compare with enum values
        if ($user->role === UserRole::ADMIN->value) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === UserRole::TEACHER->value) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->role === UserRole::USER->value) {
            return redirect()->route('user.dashboard');
        }
        
        // Default fallback
        return redirect('/dashboard');
    }
}