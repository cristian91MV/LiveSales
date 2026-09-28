<?php

namespace App\Actions\Lives;

use App\Enums\LiveStatus;
use App\Models\LiveProduct;
use App\Models\LiveSession;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class UpdateLiveProductPriceAction
{
    public function execute(
        LiveSession $liveSession,
        LiveProduct $liveProduct,
        string|float|int $livePrice
    ): LiveProduct {
        return DB::transaction(
            function () use (
                $liveSession,
                $liveProduct,
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
                        'El precio ya no puede modificarse en este Live.'
                    );
                }

                $liveProduct = LiveProduct::query()
                    ->whereKey($liveProduct->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    (int) $liveProduct->live_session_id
                    !== (int) $liveSession->id
                ) {
                    throw new RuntimeException(
                        'El producto indicado no pertenece a este Live.'
                    );
                }

                $liveProduct->update([
                    'live_price' => $livePrice,
                ]);

                return $liveProduct->fresh();
            }
        );
    }
}
