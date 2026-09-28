<?php

namespace App\Enums;

enum LiveStatus: string
{
    case SCHEDULED = 'PROGRAMADO';
    case ACTIVE = 'ACTIVO';
    case FINISHED = 'FINALIZADO';
    case CANCELLED = 'CANCELADO';

    public static function values(): array
    {
        return array_column(
            self::cases(),
            'value'
        );
    }
}
