<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Return all users.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();
        $users = UserResource::collection($users);
        return response()->json([
            "data" => $users
        ]);
    }

    /**
     * Mark a user from the database as deleted.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if($user->admin) return response()->json([], 403);
        $user->delete();
        return response()->json([], 204);
    }
}
