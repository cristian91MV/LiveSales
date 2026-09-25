<?php

namespace App\Actions\Products;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SetPrimaryProductPhotoAction
{
    public function execute(
        Product $product,
        ProductPhoto $photo
    ): void {
        if ($photo->product_id !== $product->id) {
            throw new RuntimeException(
                'La fotografía no pertenece al producto indicado.'
            );
        }

        DB::transaction(function () use ($product, $photo) {
            $product->photos()->update([
                'is_primary' => false,
            ]);

            $photo->update([
                'is_primary' => true,
            ]);
        });
    }
}
