<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Car extends Model
{
    use HasFactory;

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

    public function getSearchedCars(array $parameters)
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

    public function getAvailableSearchedCars(array $parameters)
    {
        $cars = $this->getSearchedCars($parameters);

        $startDate = $parameters["startDate"];
        $endDate = $parameters["endDate"];

        return $cars->filter(function ($car) use($startDate, $endDate){
            return $car->isAvailableCar($startDate, $endDate);
        });
    }

    public function isAvailableCar(string $startDate, string $endDate)
    {
        return !($this->reservations()
            ->where(function ($query) use ($startDate, $endDate){
                $query->where('start_date', '<', $endDate)
                    ->where('end_date', '>', $startDate);
            })->exists());
    }
}
