<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Return the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $user = new UserResource($user);
        return response()->json(["data"  => $user]);
    }

    /**
     * Return the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showProfile(Request $request)
    {
        $user = $request->user();
        $user = new UserResource($user);
        return response()->json(["data"  => $user]);
    }

    /**
     * Update the current user's information.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate(["name" => "sometimes|string|max:255"]);

        $name = $request->input("name");

        if($name != null)
            $user->name = $name;
            $user->save();

        return response()->json([], 204);
    }

    /**
     * Update the specified user's information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if (!$request->user()->admin and $user->id != $request->user()->id){
            return response()->json([], 401);
        }

        $request->validate([
            "name" => "sometimes|string|max:255",
            "admin" => "sometimes|boolean"
        ]);

        $name = $request->input("name");
        $admin = $request->input("admin");

        if($user->id == $request->user()->id and $name != null)
            $user->name = $name;


        if($request->user()->admin and $user->admin != 1 and $admin != null){
            $user->admin = $admin;
        }


        $user->save();
        return response()->json([], 204);
    }

    /**
     * Update the current user's password.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            "currentPassword" => "required",
            "newPassword" => "required|confirmed"
        ]);

        $user = $request->user();
        if(!Hash::check($request->input("currentPassword"), $user->password)){
            return response()->json(["message" => "Wrong password"], 400);
        }

        $user->password = Hash::make($request->input("newPassword"));
        $user->save();
        $user->tokens()->delete();
        return response()->json(["message" => "Password successfully updated"]);
    }

    /**
     * Sent reset token to user
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetToken(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->input("email"))->first();

        if (!$user) {
            return response()->json(['message' => 'No user found with this email'], 404);
        }

        $token = Str::random(60);

        $user->forceFill([
            'remember_token' => Hash::make($token),
        ])->save();

        Mail::raw("Your reset token is: {$token}", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset Token');
        });

        return response()->json(['message' => 'Reset token sent to your email.']);
    }

    /**
     * Update the current user's forgotten password.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setNewPassword(Request $request)
    {
        $request->validate([
            "email" => "required",
            "token" => "required",
            "newPassword" => "required|confirmed"
        ]);

        $user = User::where('email', $request->input("email"))->first();

        if (!$user) {
            return response()->json(['message' => 'No user found with this email'], 404);
        }
        if(!Hash::check($request->input("token"), $user->remember_token)){
            return response()->json(["message" => "Invalid token"], 422);
        }

        $user->forceFill([
            'password' => Hash::make($request->input("newPassword")),
            'remember_token' => Str::random(60)
        ])->save();
        $user->tokens()->delete();
        return response()->json(["message" => "Password successfully updated"]);
    }


}
