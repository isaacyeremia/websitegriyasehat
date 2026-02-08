<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TerapisAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->canManagePatients()) {
            return redirect()->route('terapis.dashboard');
        }
        
        return view('terapis.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone'    => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
            if (Auth::user()->canManagePatients()) {
                $request->session()->regenerate();
                return redirect()->route('terapis.dashboard');
            }
            
            Auth::logout();
            return back()->withErrors([
                'phone' => 'Akses ditolak. Anda bukan terapis atau admin.',
            ])->onlyInput('phone');
        }

        return back()->withErrors([
            'phone' => 'Nomor telepon atau password salah.',
        ])->onlyInput('phone');
    }

    public function showRegister()
    {
        return view('terapis.auth.register');
    }

    public function generateTerapisCode(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'phone'    => 'required|string|unique:users,phone',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ], [
                'phone.unique' => 'Nomor telepon sudah terdaftar.',
                'email.unique' => 'Email sudah terdaftar.',
            ]);

            $terapisCode = 'TRP-' . strtoupper(substr(md5(time() . $validated['email']), 0, 8));

            return response()->json([
                'success' => true,
                'terapis_code' => $terapisCode,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'address'  => 'nullable|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'terapis_code' => 'required|string',
        ]);

        if (!str_starts_with($data['terapis_code'], 'TRP-')) {
            return back()->withErrors([
                'terapis_code' => 'Kode terapis tidak valid.',
            ])->withInput();
        }

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'address'  => $data['address'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'terapis',
        ]);

        Auth::login($user);

        return redirect()->route('terapis.dashboard')->with('success', 'Akun terapis berhasil dibuat!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('terapis.login');
    }
}