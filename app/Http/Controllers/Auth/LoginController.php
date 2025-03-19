<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    // app/Http/Controllers/Auth/LoginController.php
/**
 * Handle login request.
 */
public function login(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Redirect based on role
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        }
        
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}
    /**
     * Handle logout request.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}