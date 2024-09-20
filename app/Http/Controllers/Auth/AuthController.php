<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function store(LoginRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->input('email'))->where('deleted_at', NULL)->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)){
            return response()->json(["message" => "Invalid credentials"],401);
        }

        $token = $user->createToken('api-token');

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function destroy(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        $token->delete();

        return response()->json(["message" => "Successfully logged out"]);
    }
}
