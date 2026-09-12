<?php

namespace App\Enums;

enum BoOrderStatus: string
{
    case PAID = 'PAID';
    case UNPAID = 'UNPAID';

    public function label(): string
    {
        return match ($this) {
            self::PAID => 'Lunas',
            self::UNPAID => 'Belum Lunas',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PAID => 'success',
            self::UNPAID => 'danger',
        };
    }
}
