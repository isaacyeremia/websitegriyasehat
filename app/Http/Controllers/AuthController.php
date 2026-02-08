<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'phone'    => 'required',
        'password' => 'required',
    ]);

    if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
        $request->session()->regenerate();
        
        // Redirect admin ke dashboard admin
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect user biasa ke home
        return redirect()->intended('/home');
    }

    return back()->withErrors([
        'phone' => 'Nomor telepon atau password salah.',
    ])->onlyInput('phone');
}

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'phone'                 => 'required|string|unique:users,phone',
            'address'               => 'nullable|string',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'address'  => $data['address'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user', // Default role adalah user
        ]);

        Auth::login($user);

        return redirect('/home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}