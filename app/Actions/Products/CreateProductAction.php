<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class CreateProductAction
{
    public function execute(array $data): Product
    {
        $images = $data['images'] ?? [];

        unset($data['images']);

        $storedPaths = [];

        try {
            $product = DB::transaction(function () use (
                $data,
                $images,
                &$storedPaths
            ) {
                $product = Product::create($data);

                foreach ($images as $index => $image) {
                    $path = $image->store(
                        'products/' . $product->id,
                        'public'
                    );

                    if (!$path) {
                        throw new RuntimeException(
                            'No se pudo almacenar una fotografía del producto.'
                        );
                    }

                    $storedPaths[] = $path;

                    $product->photos()->create([
                        'path' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }

                return $product;
            });

            return $product->load([
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
