<?php

namespace App\Http\Requests;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:products,code',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'size' => [
                'nullable',
                'string',
                'max:50',
            ],

            'base_price' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
            ],

            'condition' => [
                'required',
                Rule::enum(ProductCondition::class),
            ],

            'detail_description' => [
                'nullable',
                'string',
                Rule::requiredIf(
                    $this->input('condition') === ProductCondition::WITH_DETAILS->value
                ),
            ],

            'status' => [
                'required',
                Rule::in([
                    ProductStatus::AVAILABLE->value,
                    ProductStatus::INACTIVE->value,
                ]),
            ],

            'images' => [
                'required',
                'array',
                'min:1',
            ],

            'images.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',

            'code.required' => 'El código del producto es obligatorio.',
            'code.unique' => 'Ya existe un producto con ese código.',

            'name.required' => 'El nombre del producto es obligatorio.',

            'base_price.required' => 'El precio base es obligatorio.',
            'base_price.numeric' => 'El precio base debe ser un número.',
            'base_price.min' => 'El precio base no puede ser negativo.',
            'base_price.decimal' => 'El precio base puede tener como máximo 2 decimales.',

            'condition.required' => 'El estado de conservación es obligatorio.',
            'condition.enum' => 'El estado de conservación seleccionado no es válido.',

            'detail_description.required' =>
                'Debes describir los detalles o defectos del producto.',

            'status.required' => 'El estado del producto es obligatorio.',
            'status.in' => 'El estado seleccionado no está permitido.',

            'images.required' => 'Debes cargar al menos una fotografía.',
            'images.min' => 'Debes cargar al menos una fotografía.',

            'images.*.image' => 'Cada archivo debe ser una imagen.',
            'images.*.mimes' => 'Las fotografías deben ser JPG, JPEG, PNG o WEBP.',
            'images.*.max' => 'Cada fotografía puede pesar como máximo 4 MB.',
        ];
    }
}
