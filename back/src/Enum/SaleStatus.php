<?php

namespace App\Enum;

enum SaleStatus: string
{
    case COMPLETED = 'completed';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';

    public static function getValues(): array
    {
        return [
            self::COMPLETED->value,
            self::PENDING->value,
            self::CANCELLED->value,
        ];
    }
}
