<?php

namespace App\Http\Controllers;

use App\Http\Resources\RateResource;
use App\Models\Car;
use App\Models\Rate;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RateController extends Controller
{
    /**
     * Store a rating and optional comment for a reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Reservation $reservation)
    {
        $user = $request->user();
        if($user->id != $reservation->user_id){
            return response()->json([], 403);
        }

        if(!Carbon::parse($reservation->endDate)->isPast()){
            return response()->json([], 400);
        }
        $request->validate([
            "rate" => "required|integer|min:1|max:5",
            "comment" => "sometimes|string|max:255"
        ]);

        $comment = $request->input('comment');
        if($reservation->rate()->exists() and !$request->comment){
            $comment = $reservation->rate()->get()->first()->comment;
        }
        Rate::updateOrCreate(
            ["reservation_id" => $reservation->id,],
            ["rate" => $request->rate,
             "reservation_id" => $reservation->id,
             "comment" => $comment]
        );

        return response()->json([
            "message" => "Rate is successfully created/updated"
        ], 201);
    }

    /**
     * Return all reservations connected to specified car.
     *
     * @param \App\Models\User $car
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Car $car)
    {
        $rates = $car->load('reservations.rate')->reservations->map->rate;
        return response()->json([
            "data" => RateResource::collection($rates)
        ]);

    }
}
