<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Déjà connecté en tant que prestataire : direction l'espace admin
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.appointments.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.appointments.index'));
        }

        return back()
            ->withErrors(['email' => 'Email ou mot de passe incorrect.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Après la déconnexion, on revient sur la page de connexion
        return redirect()->route('login')->with('success', 'Vous êtes bien déconnecté.');
    }
}
