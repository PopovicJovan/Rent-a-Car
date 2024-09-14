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
}
