<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $validate = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        
        if(!Auth::attempt($validate)) {
            return response()->json(['message' => 'Invalid credentials'], 403);
        }

        return response()->json([
            'message' => 'Login successful', 
            'token' => $request->user()->createToken('user')->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
