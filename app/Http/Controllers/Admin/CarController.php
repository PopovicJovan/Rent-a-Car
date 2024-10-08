<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\CreateCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Store a newly created car in the database.
     *
     * @param  \App\Http\Requests\Car\CreateCarRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCarRequest $request)
    {
        $request->validated();
        $parameters = $request->only([
            "type", "brand", "price",
            "description", "fuelType",
            "status", "fuelConsumption",
            "gear", "passengers"
        ]);
        $car = Car::create([
            ...$parameters,
            "image" => "abc"
        ]);

        if ($request->hasFile('image')){
            $image = $request->file('image');
            $id = uniqid('car_', true);
            $image->storeAs('images/cars', "$id.jpg", 'public');
            $car->image = $id;
            $car->save();
        }

        return response()->json(["message" => "Car is successfully created"], 201);
    }

    /**
     * Mark a car from the database as deleted.
     *
     * @param  \App\Models\Car $car
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Car $car)
    {
        $path = "images/cars/$car->image.jpg";
        if(Storage::disk('public')->exists($path))
            Storage::disk('public')->delete($path);
        $car->delete();
        return response()->json([], 204);
    }

    /**
     * Update a car from the database.
     *
     * @param \App\Http\Requests\Car\UpdateCarRequest $request
     * @param  \App\Models\Car $car
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
      $request->validated();
      $parameters = $request->only([
            "type", "brand", "price",
            "description", "fuelType",
            "status", "fuelConsumption",
            "gear", "passengers"
      ]);
      if($request->hasFile('image')){
          $path = "images/cars/$car->image.jpg";
          if(Storage::disk('public')->exists($path))
              Storage::disk('public')->delete($path);

          $image = $request->file('image');
          $image->storeAs('images/cars', "$car->image.jpg", 'public');
          $car->save();
      }
      $car->update($parameters);
      $car->save();

      return response()->json([], 204);
    }
}
