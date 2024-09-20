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
    public function store(CreateCarRequest $request)
    {
        $parameters = $request->only([
            "type", "brand", "price",
            "description", "fuelType",
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

    public function destroy(Car $car)
    {
        $path = "images/cars/$car->image.jpg";
        if(Storage::disk('public')->exists($path))
            Storage::disk('public')->delete($path);
        $car->delete();
        return response()->noContent();
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
      $parameters = $request->only([
            "type", "brand", "price",
            "description", "fuelType",
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

      return response()->noContent();
    }
}
