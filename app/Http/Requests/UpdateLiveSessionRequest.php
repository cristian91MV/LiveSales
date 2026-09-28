<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLiveSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'scheduled_at' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>
                'El nombre del Live es obligatorio.',

            'name.max' =>
                'El nombre del Live no puede superar los 150 caracteres.',

            'scheduled_at.date' =>
                'La fecha programada no tiene un formato válido.',

            'notes.max' =>
                'Las notas no pueden superar los 5000 caracteres.',
        ];
    }
}
