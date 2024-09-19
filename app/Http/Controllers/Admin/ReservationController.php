<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json([
            "data" => $reservations
        ]);
    }

    public function getReservationsForUser(User $user)
    {
        $reservations = $user->reservations()->get();
        return response()->json([
            "data" => $reservations
        ]);
    }
}
