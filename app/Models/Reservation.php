<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id', 'car_id',
        'start_date', 'end_date'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rate(): HasOne
    {
        return $this->hasOne(Rate::class);
    }

    /**
     * Calculate the rental price for a car based on the given date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return integer
     */
    public function calculatePrice(Request $request, Car $car): int
    {
        $carPrice = $car->price;
        $request->validate([
            "startDate" => "required|date|after:". Carbon::now()->addHours(24),
            "endDate" => "required|date|after:startDate"
        ]);
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        $hours = $startDate->diffInHours($endDate);

        $price = ($carPrice/24)*$hours;
        return $price;
    }
}
