<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        // Validasi input; role hanya boleh 'guru'
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // cocok dengan input password_confirmation
            'role'     => 'required|in:guru',
        ]);

        // Simpan user baru, password di-hash dulu
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Cek email & password
        if (Auth::attempt($credentials)) {

            // Cek role: hanya guru yang boleh masuk
            if (Auth::user()->role !== 'guru') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akses ditolak. Hanya akun Guru yang bisa login.'])->withInput();
            }

            // Buat session baru (keamanan, mencegah session fixation)
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        // Email / password salah
        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
