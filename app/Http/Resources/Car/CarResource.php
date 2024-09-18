<?php

namespace App\Http\Resources\Car;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'fuelType' => $this->fuelType,
            "description" => $this->description,
            "price" => $this->price,
            "type" => $this->type,
            "image" => $this->image,
            "avgRate" => $this->getAvgRate()
        ];
    }
}
