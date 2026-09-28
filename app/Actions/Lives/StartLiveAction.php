<?php

namespace App\Actions\Lives;

use App\Enums\LiveStatus;
use App\Models\LiveSession;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StartLiveAction
{
    public function execute(
        LiveSession $liveSession
    ): LiveSession {
        return DB::transaction(
            function () use ($liveSession) {

                /*
                 * Para el MVP bloqueamos las filas de Lives
                 * durante esta operación.
                 *
                 * Esto reduce el riesgo de que dos solicitudes
                 * intenten iniciar dos Lives al mismo tiempo.
                 */
                LiveSession::query()
                    ->select('id')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $liveSession = LiveSession::query()
                    ->findOrFail($liveSession->id);

                if (
                    $liveSession->status
                    !== LiveStatus::SCHEDULED
                ) {
                    throw new RuntimeException(
                        'Solo un Live programado puede iniciarse.'
                    );
                }

                $anotherActiveLiveExists =
                    LiveSession::query()
                        ->where(
                            'status',
                            LiveStatus::ACTIVE->value
                        )
                        ->where(
                            'id',
                            '!=',
                            $liveSession->id
                        )
                        ->exists();

                if ($anotherActiveLiveExists) {
                    throw new RuntimeException(
                        'Ya existe otro Live activo. Finalízalo o cancélalo antes de iniciar uno nuevo.'
                    );
                }

                $liveSession->update([
                    'status' => LiveStatus::ACTIVE,
                    'started_at' => now(),
                    'ended_at' => null,
                ]);

                return $liveSession->fresh();
            }
        );
    }
}
