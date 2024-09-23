<?php

namespace App\Http\Controllers\Admin;

use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function websocket(Request $request)
    {
        $request->validate([
            'carId' => 'required|exists:cars,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        event(new LocationUpdated([
            'carId' => $request->carId,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]));

        return response()->json();
    }

}
