<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\CreateCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Models\Car;

class CarController extends Controller
{
    public function store(CreateCarRequest $request)
    {
        $parameters = $request->only([
            "type", "brand", "price",
            "description", "fuelType",
        ]);
        Car::create([
            ...$parameters,
            "image" => "abc"
        ]);
        return response()->json(["message" => "Car is successfully created"], 201);
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return response()->noContent();
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $parameters = $request->only([
            "type", "brand", "price",
            "description", "fuelType",
        ]);

        $car->update($parameters);
        $car->save();

        return response()->noContent();
    }
}
