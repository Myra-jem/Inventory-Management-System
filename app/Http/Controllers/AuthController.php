<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,staff',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('username', 'role'));
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login timestamp
            if (method_exists(Auth::user(), 'updateLastLogin')) {
                Auth::user()->updateLastLogin();
            }

            // Redirect based on role
            return $this->redirectToDashboard(Auth::user());
        }

        return back()
            ->withErrors(['login' => 'Invalid credentials or role mismatch.'])
            ->withInput($request->only('username', 'role'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        if ($user->isStaff()) {
            return redirect()->route('staff.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        Auth::logout();
        return redirect()->route('login')->with('error', 'Invalid role assigned to your account.');
    }
}
