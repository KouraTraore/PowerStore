<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([
            $loginField => $credentials['username'],
            'password' => $credentials['password'],
            'is_active' => 1          // ✅ compte actif seulement
        ])) {
            $request->session()->regenerate();

            $user = Auth::user();
            // ✅ Rôle corrigé
            if ($user->role === 'super_admin') {
                return redirect()->route('admin.super.dashboard');   // temporaire, on changera plus tard
            }

            return redirect()->route('admin.index');       // ✅ nom de route correct
        }

        return back()->withErrors([
            'username' => 'Identifiants incorrects.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
