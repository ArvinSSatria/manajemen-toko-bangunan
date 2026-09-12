<?php

namespace App\Enums;

enum ReceivableStatus: string
{
    case PAID = 'PAID';
    case UNPAID = 'UNPAID';
    case OVERDUE = 'OVERDUE';

    public function label(): string
    {
        return match ($this) {
            self::PAID => 'Lunas',
            self::UNPAID => 'Belum Lunas',
            self::OVERDUE => 'Jatuh Tempo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PAID => 'success',
            self::UNPAID => 'warning',
            self::OVERDUE => 'danger',
        };
    }
}
