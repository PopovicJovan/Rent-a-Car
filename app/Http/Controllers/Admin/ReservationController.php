<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
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
        $reservations = Reservation::with('rate')->get();
        $reservations = ReservationResource::collection($reservations);
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
        $reservations = $user->reservations()->with('rate')->get();
        $reservations = ReservationResource::collection($reservations);
        return response()->json([
            "data" => $reservations
        ]);
    }
}
