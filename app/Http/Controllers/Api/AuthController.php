<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create($validated);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration Successful!',
            'user' => $user,
            'token' => $token
        ], 201
        );
    }
    
    public function login(Request $request): JsonResponse {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message'=> 'Email or Password Incorrect',
            ], 401
            );
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'user' => $user,
            'token' => $token,
        ], 200
        );
    }

    public function logout(Request $request): JsonResponse {
        $request->user()->tokens()->delete();

        return response()->json([
            'message'=>'Logout Success',
        ], 200
        );
    }
    
}
