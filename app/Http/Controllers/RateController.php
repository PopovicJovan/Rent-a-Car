<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rate;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function store(Request $request, Reservation $reservation)
    {
        $user = $request->user();
        if($user->id != $reservation->user_id){
            return response()->json([], 403);
        }

        if(!Carbon::parse($reservation->startDate)->isPast()){
            return response()->json([], 400);
        }
        $request->validate(["rate" => "sometimes|integer|min:1|max:5"]);

        Rate::updateOrCreate(
            ["reservation_id" => $reservation->id],
            ["rate" => $request->rate]
        );

        return response()->json([
            "message" => "Rate is successfully created/updated"
        ], 201);
    }
}
