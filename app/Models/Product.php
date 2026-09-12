<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'image_path',
        'category_id',
        'unit',
        'purchase_price',
        'selling_price',
        'is_consignment',
        'bo_id',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'selling_price'  => 'decimal:2',
            'is_consignment' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bo(): BelongsTo
    {
        return $this->belongsTo(Bo::class, 'bo_id');
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get stock for a specific store.
     */
    public function stockForStore(int $storeId): int
    {
        if ($this->relationLoaded('inventories')) {
            return $this->inventories->where('store_id', $storeId)->first()?->stock ?? 0;
        }
        return $this->inventories()->where('store_id', $storeId)->value('stock') ?? 0;
    }
}
