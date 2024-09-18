<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            "data" => $users
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
