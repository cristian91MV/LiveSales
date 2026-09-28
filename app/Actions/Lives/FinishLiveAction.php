<?php

namespace App\Actions\Lives;

use App\Enums\LiveStatus;
use App\Models\LiveSession;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FinishLiveAction
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
                    $liveSession->status
                    !== LiveStatus::ACTIVE
                ) {
                    throw new RuntimeException(
                        'Solo un Live activo puede finalizarse.'
                    );
                }

                $liveSession->update([
                    'status' => LiveStatus::FINISHED,
                    'ended_at' => now(),
                ]);

                return $liveSession->fresh();
            }
        );
    }
}
