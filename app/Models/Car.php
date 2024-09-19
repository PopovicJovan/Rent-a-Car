<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'type',
        'brand', 'price',
        'description', 'fuelType',
        'image'
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function getSearchedCars(array $parameters): Collection
    {
        $type = $parameters["type"] ?? null;
        $minPrice = $parameters["minPrice"] ?? 0;
        $maxPrice = $parameters["maxPrice"] ?? PHP_INT_MAX;
        $brand = $parameters["brand"] ?? null;
        $fuelType = $parameters["fuelType"] ?? null;

        return $this->when($type, function ($q) use ($type){
            $q->where('type', $type);
        })->when($fuelType, function ($q) use ($fuelType){
            $q->where('fuelType', $fuelType);
        })->whereBetween('price',[$minPrice, $maxPrice])
          ->when($brand, function ($q) use ($brand){
                $q->where('brand', $brand);
            })->get();
    }

    public function getAvailableSearchedCars(array $parameters): Collection
    {
        request()->validate([
            'startDate' => 'required|date|after:'. Carbon::today(),
            'endDate' => 'required|date|after:startDate'
        ]);
        $cars = $this->getSearchedCars($parameters);

        $startDate = $parameters["startDate"];
        $endDate = $parameters["endDate"];

        return $cars->filter(function ($car) use($startDate, $endDate){
            return $car->isAvailableCar($startDate, $endDate);
        });
    }

    public function isAvailableCar(string $startDate, string $endDate): bool
    {
        return !($this->reservations()
            ->where(function ($query) use ($startDate, $endDate){
                $query->where('start_date', '<', $endDate)
                    ->where('end_date', '>', $startDate);
            })->exists());
    }

    public function getAvgRate(): float
    {
        $avg =  $this->reservations()->with('rate')
            ->get()->pluck('rate.rate')->avg();

        return round($avg, 2);
    }
}
