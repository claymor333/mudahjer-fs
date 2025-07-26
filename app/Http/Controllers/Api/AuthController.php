<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;

class AuthController
{
    // Register a new user and return a token.
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token,
        ]);
    }

    // Login a user and return a token.
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials','status' => 'error']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $player = $user->player; 
        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'token' => $token,
            'player' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'level' => $player->level,
                'exp' => $player->exp,
                'avatar' => $player->avatar,
                // 'streak' => $player->streak,
            ]
        ])->setStatusCode(200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'An error occurred during login'], 500);
        }
        
    }

    // Logout user and revoke token.
    public function logout(Request $request): JsonResponse
    {
        // Revoke the current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }
}
