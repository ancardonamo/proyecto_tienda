<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->question = $request->question;
        $user->answer = $request->answer;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['message' => 'User registered successfully']);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !password_verify($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function password_reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'answer' => 'required|string',
            'password' => 'required|string|min:6|confirmed', 
        ]);

        $user = User::where('email', $request->email)
                    ->where('answer', $request->answer)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'Incorrect email or security answer'], 401);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully']);
    }
}