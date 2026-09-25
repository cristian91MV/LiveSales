<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->normalizeName(
                $this->input('name')
            ),

            'tiktok_username' => $this->normalizeTikTok(
                $this->input('tiktok_username')
            ),

            'whatsapp' => $this->normalizeWhatsapp(
                $this->input('whatsapp')
            ),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'tiktok_username' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-z0-9._]+$/',
                'unique:customers,tiktok_username',
            ],

            'whatsapp' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^\d+$/',
                'unique:customers,whatsapp',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>
                'El nombre del cliente es obligatorio.',

            'name.string' =>
                'El nombre debe ser texto.',

            'name.max' =>
                'El nombre no puede superar los 150 caracteres.',

            'tiktok_username.regex' =>
                'El usuario de TikTok solo puede contener letras, números, punto y guion bajo.',

            'tiktok_username.unique' =>
                'Ese usuario de TikTok ya está registrado.',

            'tiktok_username.max' =>
                'El usuario de TikTok no puede superar los 100 caracteres.',

            'whatsapp.regex' =>
                'El número de WhatsApp no tiene un formato válido.',

            'whatsapp.unique' =>
                'Ese número de WhatsApp ya está registrado.',

            'whatsapp.max' =>
                'El número de WhatsApp es demasiado largo.',
        ];
    }

    private function normalizeName(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return trim($value);
    }

    private function normalizeTikTok(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        // Elimina un @ inicial.
        $value = preg_replace('/^@/', '', $value);

        return Str::lower($value);
    }

    private function normalizeWhatsapp(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        // Permitimos escribir espacios, guiones y paréntesis.
        $value = preg_replace('/[\s\-\(\)]+/', '', $value);

        // Elimina solamente un + inicial.
        $value = preg_replace('/^\+/', '', $value);

        return $value === '' ? null : $value;
    }
}
