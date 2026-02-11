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
            'phone'    => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
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
            'name'     => 'required|string|max:100',
            'phone'    => 'required|string|unique:users,phone',
            'address'  => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'address'  => $data['address'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user', // Hanya user yang bisa register sendiri
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

    // Forgot Password Functions
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone'
        ], [
            'phone.exists' => 'Nomor telepon tidak terdaftar.'
        ]);

        $user = User::where('phone', $request->phone)->first();

        // Generate token 6 digit
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->reset_token = Hash::make($token);
        $user->reset_token_expires_at = Carbon::now()->addMinutes(30);
        $user->save();

        // Simpan token di session untuk verifikasi
        session(['reset_phone' => $request->phone, 'reset_token' => $token]);

        return redirect()->route('password.verify')->with('success', 'Kode verifikasi Anda: ' . $token);
    }

    public function showVerifyToken()
    {
        if (!session('reset_phone')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-token');
    }

    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|digits:6'
        ]);

        $phone = session('reset_phone');
        $user = User::where('phone', $phone)->first();

        if (!$user || !$user->reset_token || Carbon::now()->greaterThan($user->reset_token_expires_at)) {
            return back()->withErrors(['token' => 'Kode verifikasi tidak valid atau sudah kadaluarsa.']);
        }

        if (Hash::check($request->token, $user->reset_token)) {
            session(['verified_phone' => $phone]);
            return redirect()->route('password.reset');
        }

        return back()->withErrors(['token' => 'Kode verifikasi salah.']);
    }

    public function showResetPassword()
    {
        if (!session('verified_phone')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $phone = session('verified_phone');
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['error' => 'Terjadi kesalahan.']);
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->save();

        // Clear sessions
        session()->forget(['reset_phone', 'reset_token', 'verified_phone']);

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.');
    }
}