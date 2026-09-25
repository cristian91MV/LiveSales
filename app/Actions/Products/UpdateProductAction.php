<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UpdateProductAction
{
    public function execute(Product $product, array $data): Product
    {
        $images = $data['images'] ?? [];

        unset($data['images']);

        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $product,
                $data,
                $images,
                &$storedPaths
            ) {
                $product->update($data);

                foreach ($images as $image) {
                    $path = $image->store(
                        'products/' . $product->id,
                        'public'
                    );

                    $storedPaths[] = $path;

                    $product->photos()->create([
                        'path' => $path,
                        'is_primary' => false,
                    ]);
                }
            });

            return $product->fresh()->load([
                'category',
                'photos',
                'primaryPhoto',
            ]);
        } catch (Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }
    }
}
