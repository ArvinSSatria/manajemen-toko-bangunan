<?php

namespace App\Models;

use App\Enums\SaleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'store_id',
        'customer_id',
        'date',
        'total',
        'paid_amount',
        'remaining_balance',
        'payment_method',
        'payment_fee',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_balance' => 'decimal:2',
            'status' => SaleStatus::class,
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function receivable(): HasOne
    {
        return $this->hasOne(Receivable::class);
    }

    /**
     * Generate invoice number: INV-{STORE_CODE}-{YYYYMMDD}-{AUTO}
     */
    public static function generateInvoiceNumber(int $storeId): string
    {
        $store = Store::find($storeId);
        $storeCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $store->name), 0, 1));
        $date = now()->format('Ymd');
        $prefix = "INV-{$storeCode}-{$date}-";

        $lastSale = self::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastSale) {
            $lastNumber = (int) substr($lastSale->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
