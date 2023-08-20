<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login method
    public function login(Request $request)
    {
        // Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Get user email from database
        $user = \App\Models\User::where('email', $request->email)->first();

        // Check if user exists and throw error if not
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        // Check if password is correct and throw error if not (use email field for best practices)
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        // Create token for user
        $token = $user->createToken('api-token')->plainTextToken;

        // Return response with token
        return response()->json([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke token for user
        $request->user()->tokens()->delete();

        // Return response
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
