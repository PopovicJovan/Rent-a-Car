<?php

namespace App\Http\Controllers\Auth;

use App\Events\Register;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(RegisterRequest $request)
    {
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

//        Mail::to($user->email)->send(new WelcomeEmail($user));
        event(new Register($user));

        return response()->json([
            "message" => "User is created"
        ], 201);
    }
}
