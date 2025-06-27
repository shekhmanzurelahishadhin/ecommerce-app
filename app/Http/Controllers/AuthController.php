<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['error' => 'Invalid credentials']);
        }

        $user = Auth::user();
        $token = $user->createToken('sso')->plainTextToken;

        return response()->json([
            'token' => $token,
            'sso_url' => 'http://localhost:8001/sso-login?token=' . urlencode($token),
        ]);
    }


    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'You have been logged out.');
    }
}
