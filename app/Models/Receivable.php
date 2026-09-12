<?php

namespace App\Models;

use App\Enums\ReceivableStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receivable extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'customer_id',
        'store_id',
        'total',
        'paid',
        'remaining',
        'due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'paid' => 'decimal:2',
            'remaining' => 'decimal:2',
            'due_date' => 'date',
            'status' => ReceivableStatus::class,
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ReceivablePayment::class);
    }

    /**
     * Check if overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status !== ReceivableStatus::PAID && $this->due_date->isPast();
    }
}
