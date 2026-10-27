<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'स्वागत है! आप सफलतापूर्वक लॉगिन हो चुके हैं।');
        }

        throw ValidationException::withMessages([
            'email' => 'प्रदान किए गए क्रेडेंशियल्स हमारे रिकॉर्ड से मेल नहीं खाते हैं।',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'आप सफलतापूर्वक लॉग आउट हो गए हैं।');
    }
}
