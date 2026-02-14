<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

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
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Auto-redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'terapis') {
                return redirect()->route('terapis.dashboard');
            } else {
                return redirect('/home');
            }
        }

        return back()->withErrors([
            'login' => 'Email/Nomor telepon atau password salah.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'nik'      => 'required|string|size:16|unique:users,nik',
            'address'  => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nik.required' => 'NIK/KTP wajib diisi',
            'nik.size' => 'NIK/KTP harus 16 digit',
            'nik.unique' => 'NIK/KTP sudah terdaftar',
            'phone.unique' => 'Nomor telepon sudah terdaftar',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'nik'      => $data['nik'],
            'address'  => $data['address'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user',
        ]);

        Auth::login($user);

        return redirect('/home')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
    }

    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // Redirect ke homepage, bukan login
    return redirect('/')->with('success', 'Anda telah logout');
}

    // Forgot Password Functions
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'login' => 'required'
        ], [
            'login.required' => 'Email atau nomor telepon wajib diisi.'
        ]);

        // Cek apakah menggunakan email atau phone
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        $user = User::where($loginType, $request->login)->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Email/Nomor telepon tidak terdaftar.']);
        }

        // Generate token 6 digit
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->reset_token = Hash::make($token);
        $user->reset_token_expires_at = Carbon::now()->addMinutes(30);
        $user->save();

        // Simpan di session
        session([
            'reset_identifier' => $request->login,
            'reset_token' => $token,
            'reset_type' => $loginType
        ]);

        return redirect()->route('password.verify')
                        ->with('success', 'Kode verifikasi Anda: ' . $token . ' (berlaku 30 menit)');
    }

    public function showVerifyToken()
    {
        if (!session('reset_identifier')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-token');
    }

    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|digits:6'
        ]);

        $identifier = session('reset_identifier');
        $loginType = session('reset_type');
        
        $user = User::where($loginType, $identifier)->first();

        if (!$user || !$user->reset_token || Carbon::now()->greaterThan($user->reset_token_expires_at)) {
            return back()->withErrors(['token' => 'Kode verifikasi tidak valid atau sudah kadaluarsa.']);
        }

        if (Hash::check($request->token, $user->reset_token)) {
            session(['verified_identifier' => $identifier, 'verified_type' => $loginType]);
            return redirect()->route('password.reset');
        }

        return back()->withErrors(['token' => 'Kode verifikasi salah.']);
    }

    public function showResetPassword()
    {
        if (!session('verified_identifier')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $identifier = session('verified_identifier');
        $loginType = session('verified_type');
        
        $user = User::where($loginType, $identifier)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['error' => 'Terjadi kesalahan.']);
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->save();

        // Clear sessions
        session()->forget(['reset_identifier', 'reset_token', 'reset_type', 'verified_identifier', 'verified_type']);

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.');
    }
}