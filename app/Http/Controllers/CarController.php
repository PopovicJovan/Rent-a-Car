<?php

namespace App\Http\Controllers;

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
        $base_parameters = ['brand', 'fuelType', 'minPrice', 'maxPrice', 'type', 'gear', 'passengers'];
        if(!$request->input("available")){
            $parameters = $request->only($base_parameters);
            $cars = ((new Car())->getSearchedCars($parameters))->paginate(6);
        }else{
            $parameters = $request->only([...$base_parameters, "startDate", "endDate"]);
            $cars = (new Car())->getAvailableSearchedCars($parameters);
        }
        return response()->json([
            "data" => CarResource::collection($cars),
            "lastPage" => $cars->lastPage()
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

    public function getSetColumns()
    {
        $brands = Car::distinct()->pluck('brand');
        $types = Car::distinct()->pluck('type');
        $gears = Car::distinct()->pluck('gear');
        $fuelTypes = Car::distinct()->pluck('fuelType');
        $passengers = Car::distinct()->pluck('passengers');

        return response()->json([
            "data" => [
                "brands" => $brands,
                "types" => $types,
                "gears" => $gears,
                "fuelTypes" => $fuelTypes,
                "passengers" => $passengers
            ]
        ], 200);
    }
}
