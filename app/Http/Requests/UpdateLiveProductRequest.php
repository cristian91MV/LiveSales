<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLiveProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'live_price' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'live_price.required' =>
                'El precio del Live es obligatorio.',

            'live_price.numeric' =>
                'El precio del Live debe ser numérico.',

            'live_price.min' =>
                'El precio del Live no puede ser negativo.',

            'live_price.decimal' =>
                'El precio del Live puede tener como máximo dos decimales.',
        ];
    }
}
