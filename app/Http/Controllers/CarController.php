<?php

namespace App\Http\Controllers;

use App\Http\Resources\Car\CarCollection;
use App\Http\Resources\Car\CarResource;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $parameters = $request->only(['brand', 'fuelType', 'minPrice', 'maxPrice', 'type']);
        $cars = (new Car())->getSearchedCars($parameters);
        return response()->json([
            "data" => new CarCollection($cars)
        ]);
    }

    public function show(Request $request, Car $car)
    {
        return response()->json([
            "data" => new CarResource($car)
        ]);
    }

    public function isCarAvailable(Request $request, Car $car)
    {
        $request->validate([
            "startDate" => "required|date|after:" . Carbon::today(),
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
