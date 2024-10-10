<?php

namespace App\Http\Controllers;

use App\Events\InvoiceCreated;
use App\Http\Requests\CreateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Mail\InvoiceMail;
use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $car = Car::find($request->carId);

        if (!$car->isAvailableCar(...$request->only(['startDate', 'endDate'])))
        {
            return response()->json([
                "message" => "Selected car is not available for that dates"
            ]);
        }

        $reservation = Reservation::create([
            'user_id' => $request->user()->id,
            'car_id' => $request->carId,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate
        ]);

        $price = (new Reservation())->calculatePrice($request, $car);
        $hours = Carbon::parse($request->startDate)
                    ->diffInHours(Carbon::parse($request->endDate));
        $invoiceData = [
            "user" => $request->user(),
            "price" => $price,
            "reservation" => $reservation,
            "car" => $car,
            "hours" => $hours
        ];

        if($reservation){
            event(new InvoiceCreated($request->user(), $invoiceData));
        }
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
        $reservations = $request->user()->reservations()->with('rate')->get();
        $reservations = ReservationResource::collection($reservations);
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
        $price = (new Reservation())->calculatePrice($request, $car);
        return response()->json([
            "data" => [
                "price" => $price
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
