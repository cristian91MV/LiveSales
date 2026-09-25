<?php

namespace App\Actions\Products;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DeleteProductPhotoAction
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

        if ($product->photos()->count() <= 1) {
            throw new RuntimeException(
                'No se puede eliminar la última fotografía del producto.'
            );
        }

        $path = $photo->path;

        DB::transaction(function () use ($product, $photo) {
            $wasPrimary = $photo->is_primary;

            $photo->delete();

            if ($wasPrimary) {
                $newPrimary = $product->photos()
                    ->orderBy('id')
                    ->first();

                if ($newPrimary) {
                    $newPrimary->update([
                        'is_primary' => true,
                    ]);
                }
            }
        });

        Storage::disk('public')->delete($path);
    }
}
