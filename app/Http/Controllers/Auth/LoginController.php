<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Pastikan session dibuat dengan ID yang benar
            $user = Auth::user();
            session(['user_id' => $user->id]);  // Simpan ID sebagai string

            Log::info('Session created', [
                'user_id' => $user->id,
                'session_id' => $request->session()->getId()
            ]);

            return redirect()->intended(route('home'));
        }

        // Log kegagalan login
        Log::warning('Failed login attempt', ['username' => $request->username]);

        return back()->withErrors([
            'loginError' => 'Username atau password yang Anda masukkan salah.',
        ])->withInput($request->except('password'));
    }
    

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        // Log logout
        Log::info('User logged out', ['user_id' => Auth::id()]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // Redirect to login page
    }

    protected $redirectTo = '/home';
}
