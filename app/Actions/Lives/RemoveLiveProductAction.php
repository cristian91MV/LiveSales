<?php

namespace App\Actions\Lives;

use App\Enums\LiveStatus;
use App\Models\LiveProduct;
use App\Models\LiveSession;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RemoveLiveProductAction
{
    public function execute(
        LiveSession $liveSession,
        LiveProduct $liveProduct
    ): void {
        DB::transaction(
            function () use (
                $liveSession,
                $liveProduct
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
                        'Ya no pueden retirarse productos de este Live.'
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

                $liveProduct->delete();
            }
        );
    }
}
