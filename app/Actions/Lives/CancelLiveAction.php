<?php

namespace App\Actions\Lives;

use App\Enums\LiveStatus;
use App\Models\LiveSession;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CancelLiveAction
{
    public function execute(
        LiveSession $liveSession
    ): LiveSession {
        return DB::transaction(
            function () use ($liveSession) {

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
                        'Este Live ya no puede cancelarse.'
                    );
                }

                $wasActive =
                    $liveSession->status
                    === LiveStatus::ACTIVE;

                $liveSession->update([
                    'status' => LiveStatus::CANCELLED,

                    'ended_at' => $wasActive
                        ? now()
                        : null,
                ]);

                return $liveSession->fresh();
            }
        );
    }
}
