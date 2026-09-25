<?php

namespace App\Http\Requests;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

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
                Rule::unique('products', 'code')->ignore($product),
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
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ];
    }
}
