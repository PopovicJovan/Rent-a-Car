<?php

namespace App\Http\Requests\Car;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'sometimes|string',
            'brand' => 'sometimes|string',
            'price' => 'sometimes|int|min:0',
            'description' => 'sometimes|string',
            'fuelType' => 'sometimes|string',
            'status' => ['sometimes', 'string', Rule::in([Car::AVAILABLE, Car::RESERVED, Car::UNUSABLE])],
            'image' => 'sometimes|image',
            'gear' => 'sometimes|string',
            'fuelConsumption' => 'sometimes|int',
            'passengers' => 'sometimes|int'
        ];
    }
}
