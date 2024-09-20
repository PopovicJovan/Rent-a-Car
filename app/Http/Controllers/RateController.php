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

        if(!Carbon::parse($reservation->endDate)->isPast()){
            return response()->json([], 400);
        }
        $request->validate([
            "rate" => "required|integer|min:1|max:5",
            "comment" => "sometimes|string|max:255"
        ]);

        $comment = $request->input('comment');
        if($reservation->rate()->exists() and !$request->comment){
            $comment = $reservation->rate()->get('comment');
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
}
