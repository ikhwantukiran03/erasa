<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function handle(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.'])->withInput();
        }

        // Generate a secure random password (8 chars, letters & numbers)
        $newPassword = $this->generatePassword(8);
        $user->password = Hash::make($newPassword);
        $user->save();

        // Send email with new password
        $emailService = new EmailService();
        $sent = $emailService->sendForgotPasswordEmail(
            $user->email,
            [
                'user' => $user,
                'password' => $newPassword
            ]
        );

        if (!$sent) {
            Log::error('Failed to send forgot password email to ' . $user->email);
            return back()->withErrors(['email' => 'Failed to send email. Please try again later.'])->withInput();
        }

        return redirect()->route('login')->with('status', 'A new password has been sent to your email. Please check your inbox.');
    }

    private function generatePassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
} 