<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email not found.',
            ])->onlyInput('email');
        }

        // Add role to credentials for authentication
        $credentials['role'] = $user->role;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on user role
            if ($user->role === 'customer') {
                return redirect()->intended('/');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'password' => 'The password is incorrect.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'customer', // Default role for new registrations
        ]);

        Auth::login($user);

        // Customers are redirected to home page
        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
