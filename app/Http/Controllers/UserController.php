<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

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
}
