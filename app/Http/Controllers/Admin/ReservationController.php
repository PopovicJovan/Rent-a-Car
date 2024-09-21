<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Return all reservations.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json([
            "data" => $reservations
        ]);
    }

    /**
     * Return all reservations connected to specified user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservationsForUser(User $user)
    {
        $reservations = $user->reservations()->get();
        return response()->json([
            "data" => $reservations
        ]);
    }
}
