<?php

namespace App\Enums;

enum ProductStatus: string
{
    case AVAILABLE = 'DISPONIBLE';
    case RESERVED = 'RESERVADO';
    case SOLD = 'VENDIDO';
    case INACTIVE = 'INACTIVO';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
