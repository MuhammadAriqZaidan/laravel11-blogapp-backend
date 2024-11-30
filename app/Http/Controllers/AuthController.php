<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan namespace User benar sesuai struktur proyek

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        // Validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password']),
        ]);

        // Return user & token in response
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken,
        ], 201); // 201 for resource created
    }

    // login user
    public function login(Request $request)
    {
        // Validate fields
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Attempt login
        if (!Auth::attempt($attrs)) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 403);
        }

        // Return user & token in response
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken,
        ], 200);
    }

    // Logout user
    public function logout()
    {
        // Pastikan user ada
        if (auth()->user()) {
            // Hapus semua token user
            auth()->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logout successful.'
            ], 200); // 200 OK
        }

        // Jika user tidak ditemukan
        return response()->json([
            'message' => 'User not authenticated.'
        ], 401); // 401 Unauthorized
    }

    // Get user details
    public function user()
    {
        // Pastikan user yang login ada
        $user = auth()->user(); // Ambil user dari Sanctum
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated.',
            ], 401); // 401 Unauthorized
        }

        return response()->json([
            'user' => $user,
        ], 200); // 200 OK
    }
}
