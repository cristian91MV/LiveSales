<?php

namespace App\Http\Controllers;

use App\Actions\Products\DeleteProductPhotoAction;
use App\Actions\Products\SetPrimaryProductPhotoAction;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class ProductPhotoController extends Controller
{
    public function destroy(
        Product $product,
        ProductPhoto $photo,
        DeleteProductPhotoAction $action
    ): RedirectResponse {
        try {
            $action->execute($product, $photo);

            return redirect()
                ->route('products.edit', $product)
                ->with(
                    'success',
                    'Fotografía eliminada correctamente.'
                );
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('products.edit', $product)
                ->with('error', $exception->getMessage());
        }
    }

    public function setPrimary(
        Product $product,
        ProductPhoto $photo,
        SetPrimaryProductPhotoAction $action
    ): RedirectResponse {
        try {
            $action->execute($product, $photo);

            return redirect()
                ->route('products.edit', $product)
                ->with(
                    'success',
                    'Fotografía principal actualizada.'
                );
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('products.edit', $product)
                ->with('error', $exception->getMessage());
        }
    }
}
