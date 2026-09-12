<?php

namespace App\Models;

use App\Enums\BoOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'bo_id',
        'store_id',
        'date',
        'due_date',
        'invoice_number',
        'description',
        'total',
        'paid_amount',
        'remaining_balance',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'due_date' => 'date',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_balance' => 'decimal:2',
            'status' => BoOrderStatus::class,
        ];
    }

    public function bo(): BelongsTo
    {
        return $this->belongsTo(Bo::class, 'bo_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function isDueSoon(): bool
    {
        return $this->status === BoOrderStatus::UNPAID && $this->due_date->diffInDays(now()) <= 7;
    }
}
