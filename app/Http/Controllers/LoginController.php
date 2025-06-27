<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['error' => 'Invalid credentials']);
        }

        $user = Auth::user();
        $token = $user->createToken('sso')->plainTextToken;


        return redirect("http://localhost:8001/sso-login?token={$token}");
    }

}
