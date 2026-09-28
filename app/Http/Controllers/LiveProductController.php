<?php

namespace App\Http\Controllers;

use App\Actions\Lives\AddLiveProductAction;
use App\Actions\Lives\RemoveLiveProductAction;
use App\Actions\Lives\UpdateLiveProductPriceAction;
use App\Http\Requests\StoreLiveProductRequest;
use App\Http\Requests\UpdateLiveProductRequest;
use App\Models\LiveProduct;
use App\Models\LiveSession;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class LiveProductController extends Controller
{
    public function store(
        StoreLiveProductRequest $request,
        LiveSession $liveSession,
        AddLiveProductAction $action
    ): RedirectResponse {
        $product = Product::findOrFail(
            $request->integer('product_id')
        );

        try {
            $action->execute(
                $liveSession,
                $product,
                $request->validated('live_price')
            );

            return redirect()
                ->route(
                    'lives.show',
                    $liveSession
                )
                ->with(
                    'success',
                    'Producto agregado al Live correctamente.'
                );

        } catch (RuntimeException $exception) {

            return redirect()
                ->route(
                    'lives.show',
                    $liveSession
                )
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }

    public function update(
        UpdateLiveProductRequest $request,
        LiveSession $liveSession,
        LiveProduct $liveProduct,
        UpdateLiveProductPriceAction $action
    ): RedirectResponse {
        try {
            $action->execute(
                $liveSession,
                $liveProduct,
                $request->validated('live_price')
            );

            return redirect()
                ->route(
                    'lives.show',
                    $liveSession
                )
                ->with(
                    'success',
                    'Precio del Live actualizado correctamente.'
                );

        } catch (RuntimeException $exception) {

            return redirect()
                ->route(
                    'lives.show',
                    $liveSession
                )
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }

    public function destroy(
        LiveSession $liveSession,
        LiveProduct $liveProduct,
        RemoveLiveProductAction $action
    ): RedirectResponse {
        try {
            $action->execute(
                $liveSession,
                $liveProduct
            );

            return redirect()
                ->route(
                    'lives.show',
                    $liveSession
                )
                ->with(
                    'success',
                    'Producto retirado del Live correctamente.'
                );

        } catch (RuntimeException $exception) {

            return redirect()
                ->route(
                    'lives.show',
                    $liveSession
                )
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
}
