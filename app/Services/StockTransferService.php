<?php

namespace App\Services;

use App\Enums\TransferStatus;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;

class StockTransferService
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Create a stock transfer request.
     */
    public function createTransfer(array $data, int $userId): StockTransfer
    {
        return DB::transaction(function () use ($data, $userId) {
            $transfer = StockTransfer::create([
                'from_store_id' => $data['from_store_id'],
                'to_store_id' => $data['to_store_id'],
                'date' => now()->toDateString(),
                'status' => TransferStatus::PENDING,
                'created_by' => $userId,
            ]);

            foreach ($data['items'] as $item) {
                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                ]);
            }

            return $transfer->load('items.product');
        });
    }

    /**
     * Complete a stock transfer — actually move inventory.
     */
    public function completeTransfer(StockTransfer $transfer): void
    {
        if ($transfer->status !== TransferStatus::PENDING) {
            throw new \Exception('Transfer ini sudah tidak bisa diproses.');
        }

        DB::transaction(function () use ($transfer) {
            foreach ($transfer->items as $item) {
                $this->inventoryService->transferStock(
                    $transfer->from_store_id,
                    $transfer->to_store_id,
                    $item->product_id,
                    $item->qty
                );
            }

            $transfer->update(['status' => TransferStatus::COMPLETED]);
        });
    }

    /**
     * Cancel a pending transfer.
     */
    public function cancelTransfer(StockTransfer $transfer): void
    {
        if ($transfer->status !== TransferStatus::PENDING) {
            throw new \Exception('Transfer ini sudah tidak bisa dibatalkan.');
        }

        $transfer->update(['status' => TransferStatus::CANCELLED]);
    }
}
