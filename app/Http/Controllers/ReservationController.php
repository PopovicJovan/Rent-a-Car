<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
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

    public function index(Request $request)
    {
        $reservations = $request->user()->reservations()->get();
        return response()->json([
            "data" => $reservations
        ]);
    }

    public function getPrice(Request $request)
    {
        $carPrice = Car::find($request->carId)->price;
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        $days = $startDate->diffInDays($endDate);

        return response()->json([
            "data" => [
                "price" => $carPrice*$days
            ]
        ]);
    }

}
