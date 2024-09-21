<?php

namespace App\Http\Controllers;

use App\Http\Resources\Car\CarCollection;
use App\Http\Resources\Car\CarResource;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Return a collection of cars based on search parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if(!$request->input("available")){
            $parameters = $request->only(['brand', 'fuelType', 'minPrice', 'maxPrice', 'type']);
            $cars = (new Car())->getSearchedCars($parameters);
        }else{
            $parameters = $request->only(['brand', 'fuelType', 'minPrice', 'maxPrice', 'type', "startDate", "endDate"]);
            $cars = (new Car())->getAvailableSearchedCars($parameters);
        }
        return response()->json([
            "data" => new CarCollection($cars)
        ]);
    }

    /**
     * Return the specified car.
     *
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Car $car)
    {
        return response()->json([
            "data" => new CarResource($car)
        ]);
    }

    /**
     * Check if the specified car is available for the given date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\JsonResponse
     */
    public function isCarAvailable(Request $request, Car $car)
    {
        $request->validate([
            "startDate" => "required|date|after:" . Carbon::now()->addHours(24),
            "endDate" => "required|date|after:startDate"
        ]);

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $available = $car->isAvailableCar($startDate, $endDate);
        return response()->json([
            "data" => [
                "available" => $available
            ]
        ]);
    }
}
