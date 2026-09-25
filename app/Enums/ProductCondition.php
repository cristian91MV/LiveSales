<?php

namespace App\Enums;

enum ProductCondition: string
{
    case NEW_UNUSED = 'NUEVO_SIN_USO';
    case LIKE_NEW = 'COMO_NUEVO';
    case GOOD_CONDITION = 'BUEN_ESTADO';
    case WITH_DETAILS = 'CON_DETALLES';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
