<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Create a new reservation for a car.
     *
     * @param  \App\Http\Requests\CreateReservationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateReservationRequest $request)
    {
        $request->validated();

        if (!Car::find($request->carId)
                ->isAvailableCar(...$request->only(['startDate', 'endDate'])))
        {
            return response()->json([
                "message" => "Selected car is not available for that dates"
            ]);
        }

        Reservation::create([
            'user_id' => $request->user()->id,
            'car_id' => $request->carId,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate
        ]);

        return response()->json([
            "message" => "Reservation is successfully created"
        ], 201);

    }

    /**
     * Return a collection of the user's reservations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $reservations = $request->user()->reservations()->get();
        return response()->json([
            "data" => $reservations
        ]);
    }

    /**
     * Calculate the rental price for a car based on the given date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrice(Request $request, Car $car)
    {
        $carPrice = $car->price;
        $request->validate([
            "startDate" => "required|date|after:". Carbon::now()->addHours(24),
            "endDate" => "required|date|after:startDate"
        ]);
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        $hours = $startDate->diffInHours($endDate);

        return response()->json([
            "data" => [
                "price" => ($carPrice/24)*$hours
            ]
        ]);
    }

    /**
     * Cancel a reservation for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Reservation $reservation)
    {
        $user = $request->user();
        if($reservation->user_id != $user->id){
            return response()->json([], 403);
        }

        $reservationStartDate = Carbon::parse($reservation->start_date);
        $hourDiff = Carbon::now()->diffInHours($reservationStartDate, false);

        if($hourDiff <= 48){
            return response()->json([
                "message" => "Reservation can be cancelled at least 48h before"
            ]);
        }

        $reservation->delete();

        return response()->json([
            "message" => "Reservation is successfully cancelled"
        ]);
    }

}
