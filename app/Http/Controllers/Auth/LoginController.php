<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     * Rate limit: 5 attempts per 15 minutes (sesuai PRD)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Rate limiting: 5 attempts per 15 minutes
        $throttleKey = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->with('throttle', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit.");
        }

        // Check if user exists and is active
        $user = Pengguna::where('email', $request->email)->first();

        if ($user && $user->status !== Pengguna::STATUS_ACTIVE) {
            RateLimiter::hit($throttleKey, 900); // 15 minutes

            throw ValidationException::withMessages([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ]);
        }

        // Attempt login
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            // Log login activity
            AuditLog::log('login', null, null, null, 'User ' . Auth::user()->name . ' berhasil login');

            return redirect()->intended(route('dashboard'));
        }

        // Failed login
        RateLimiter::hit($throttleKey, 900); // 15 minutes

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout activity before actually logging out
        if ($user) {
            AuditLog::log('logout', null, null, null, 'User ' . $user->name . ' logout');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Redirect to Google OAuth
     * Placeholder for SSO implementation
     */
    public function redirectToGoogle()
    {
        // TODO: Implement Google SSO with Laravel Socialite
        return redirect()->route('login')->with('error', 'Google SSO belum dikonfigurasi.');
    }
}
