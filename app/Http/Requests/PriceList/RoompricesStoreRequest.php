<?php

namespace App\Http\Requests\PriceList;

use Illuminate\Foundation\Http\FormRequest;

class RoompricesStoreRequest extends FormRequest
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
            'dt' => [
                'required',
                'date',
                'after_or_equal:' . now()->format('d-m-Y'),
                'before_or_equal:' . now()->addYear()->format('d-m-Y'),
            ],
            'id_type' => 'required|integer',
            'beds' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'uzs' => 'required|numeric|min:0',
            'usd' => 'required|numeric|min:0',
            'breakfast' => 'required|boolean',
        ];
    }
    public function messages()
    {
        return [
            'dt.required' => 'The date field is required.',
            'id_type.required' => 'Please select a valid room type.',
            'beds.required' => 'Please specify the number of beds.',
            'uzs.required' => 'Price in UZS is required.',
            'usd.required' => 'Price in USD is required.',
            'breakfast.required' => 'Breakfast option is required.',
        ];
    }
}
