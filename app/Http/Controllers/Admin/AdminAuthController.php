<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Tampilkan halaman login admin
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    // Proses login admin
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => 'required', // Bisa email atau phone
            'password' => 'required',
        ]);

        // Cek apakah login menggunakan email atau phone
        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $loginData = [
            $loginType => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($loginData)) {
            // Cek apakah user adalah admin
            if (Auth::user()->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            
            // Jika bukan admin, logout dan tampilkan error
            Auth::logout();
            return back()->withErrors([
                'login' => 'Akses ditolak. Anda bukan admin.',
            ])->onlyInput('login');
        }

        return back()->withErrors([
            'login' => 'Email/Nomor telepon atau password salah.',
        ])->onlyInput('login');
    }

    // Tampilkan halaman register admin
    public function showRegister()
    {
        return view('admin.auth.register');
    }

    // Generate kode admin otomatis
    public function generateAdminCode(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Generate kode admin unik berdasarkan waktu + random
        $adminCode = 'ADM-' . strtoupper(substr(md5(time() . $request->email), 0, 8));

        return response()->json([
            'success' => true,
            'admin_code' => $adminCode
        ]);
    }

    // Proses register admin
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'nik'      => 'required|string|size:16|unique:users,nik',
            'address'  => 'nullable|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'admin_code' => 'required|string',
        ], [
            'nik.required' => 'NIK/KTP wajib diisi',
            'nik.size' => 'NIK/KTP harus 16 digit',
            'nik.unique' => 'NIK/KTP sudah terdaftar',
        ]);

        // Validasi format kode admin (harus dimulai dengan ADM-)
        if (!str_starts_with($data['admin_code'], 'ADM-')) {
            return back()->withErrors([
                'admin_code' => 'Kode admin tidak valid. Harap generate kode terlebih dahulu.',
            ])->withInput();
        }

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'nik'      => $data['nik'],
            'address'  => $data['address'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'admin',
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('success', 'Akun admin berhasil dibuat!');
    }

    // Logout admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Admin telah logout');
    }
}