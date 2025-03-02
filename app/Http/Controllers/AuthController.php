<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Password konfirmasi tidak sesuai.',
        ];
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], $messages);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pemesan',
        ]);
        $abilities = $user->role === 'admin' ? ['admin'] : ['pemesan'];
        $token = $user->createToken('TravelApp', $abilities)->plainTextToken;
        Auth::login($user);
       
        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ];

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ], $messages);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }
        Auth::login($user);
        $abilities = $user->role === 'admin' ? ['admin'] : ['pemesan'];
        $token = $user->createToken('TravelApp',$abilities)->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil',
            'token' => $token,
            'role' => $user->role,
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Logout dari session (untuk auth berbasis session)
        Auth::guard('web')->logout();
    
        // Hapus semua token pengguna (untuk API authentication)
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }
    
        // Hapus session Laravel
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return response()->json([
            'message' => 'Logout berhasil'
        ], 200);
    }
}
