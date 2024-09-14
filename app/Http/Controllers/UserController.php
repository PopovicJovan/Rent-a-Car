<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request, User $user)
    {
        $user = new UserResource($user);
        return response()->json(["data"  => $user]);
    }

    public function update(Request $request, User $user)
    {
        if (!$request->user()->admin and $user->id != $request->user()->id){
            return response()->json([], 401);
        }

        $request->validate([
            "name" => "sometimes|string|max:255",
            "admin" => "sometimes|boolean"
        ]);

        if ($request->input("name")) {
            $user->name = $request->input('name');
        }

        if($request->user()->admin and $request->input("admin") and !$user->admin){
            $user->admin = $request->input("admin");
        }

        $user->save();
        return response()->json([], 204);
    }
}
