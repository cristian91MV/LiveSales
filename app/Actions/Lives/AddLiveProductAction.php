<?php

namespace App\Actions\Lives;

use App\Enums\LiveStatus;
use App\Enums\ProductStatus;
use App\Models\LiveProduct;
use App\Models\LiveSession;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AddLiveProductAction
{
    public function execute(
        LiveSession $liveSession,
        Product $product,
        string|float|int $livePrice
    ): LiveProduct {
        return DB::transaction(
            function () use (
                $liveSession,
                $product,
                $livePrice
            ) {
                $liveSession = LiveSession::query()
                    ->whereKey($liveSession->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    !in_array(
                        $liveSession->status,
                        [
                            LiveStatus::SCHEDULED,
                            LiveStatus::ACTIVE,
                        ],
                        true
                    )
                ) {
                    throw new RuntimeException(
                        'No se pueden agregar productos a un Live finalizado o cancelado.'
                    );
                }

                $product = Product::query()
                    ->whereKey($product->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $product->status
                    !== ProductStatus::AVAILABLE
                ) {
                    throw new RuntimeException(
                        'Solo pueden agregarse productos disponibles.'
                    );
                }

                $alreadyExists = LiveProduct::query()
                    ->where(
                        'live_session_id',
                        $liveSession->id
                    )
                    ->where(
                        'product_id',
                        $product->id
                    )
                    ->exists();

                if ($alreadyExists) {
                    throw new RuntimeException(
                        'Este producto ya pertenece al Live.'
                    );
                }

                return LiveProduct::create([
                    'live_session_id' =>
                        $liveSession->id,

                    'product_id' =>
                        $product->id,

                    'live_price' =>
                        $livePrice,
                ]);
            }
        );
    }
}
