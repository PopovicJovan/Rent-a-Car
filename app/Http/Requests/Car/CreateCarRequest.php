<?php

namespace App\Http\Requests\Car;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCarRequest extends FormRequest
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
            'type' => 'required|string',
            'brand' => 'required|string',
            'price' => 'required|int|min:0',
            'description' => 'required|string',
            'fuelType' => 'required|string',
            'status' => ['sometimes', 'string', Rule::in([Car::AVAILABLE, Car::RESERVED, Car::UNUSABLE])],
            'image' => 'required|image',
            'gear' => 'required|string',
            'fuelConsumption' => 'required|int',
            'passengers' => 'required|int'
        ];
    }
}
