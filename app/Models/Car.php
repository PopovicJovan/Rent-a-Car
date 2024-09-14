<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function reservations(): HasOne
    {
        return $this->hasOne(Reservation::class);
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
}
