<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Reduce stock for a specific store and product.
     */
    public function reduceStock(int $storeId, int $productId, int $qty): void
    {
        $inventory = Inventory::where('store_id', $storeId)
            ->where('product_id', $productId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($inventory->stock < $qty) {
            throw new \Exception("Stok tidak mencukupi untuk produk ID {$productId}. Stok tersedia: {$inventory->stock}");
        }

        $inventory->decrement('stock', $qty);
    }

    /**
     * Add stock for a specific store and product.
     */
    public function addStock(int $storeId, int $productId, int $qty): void
    {
        $inventory = Inventory::firstOrCreate(
            ['store_id' => $storeId, 'product_id' => $productId],
            ['stock' => 0, 'minimum_stock' => 5]
        );

        $inventory->increment('stock', $qty);
    }

    /**
     * Transfer stock between stores.
     */
    public function transferStock(int $fromStoreId, int $toStoreId, int $productId, int $qty): void
    {
        DB::transaction(function () use ($fromStoreId, $toStoreId, $productId, $qty) {
            $this->reduceStock($fromStoreId, $productId, $qty);
            $this->addStock($toStoreId, $productId, $qty);
        });
    }

    /**
     * Get low stock items for a store (or all stores).
     */
    public function getLowStockItems(?int $storeId = null, int $limit = 10)
    {
        $query = Inventory::with(['product', 'store'])
            ->whereColumn('stock', '<=', 'minimum_stock');

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        return $query->orderBy('stock')->limit($limit)->get();
    }
}
