<?php

namespace App\Services;

use App\Enums\ReceivableStatus;
use App\Enums\SaleStatus;
use App\Models\Income;
use App\Models\Receivable;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\BoOrder;
use App\Models\Expense;
use App\Enums\BoOrderStatus;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Create a new sale transaction.
     *
     * @param array $data ['store_id', 'customer_id', 'paid_amount', 'items' => [['product_id', 'qty', 'price'], ...]]
     */
    public function createSale(array $data, int $userId): Sale
    {
        return DB::transaction(function () use ($data, $userId) {
            // Calculate totals
            $total = 0;
            foreach ($data['items'] as $key => $item) {
                $data['items'][$key]['subtotal'] = $item['qty'] * $item['price'];
                $total += $data['items'][$key]['subtotal'];
            }

            $paidAmount = (float) ($data['paid_amount'] ?? $total);
            $remaining = $total - $paidAmount;
            $status = $remaining <= 0 ? SaleStatus::PAID : SaleStatus::UNPAID;

            $paymentMethod = $data['payment_method'] ?? 'Tunai';
            $paymentFee = ($paymentMethod === 'QRIS') ? 500 : 0;
            
            if ($paymentMethod === 'Deposit' && empty($data['customer_id'])) {
                throw new \Exception("Pelanggan wajib dipilih jika metode pembayaran adalah Potong Deposit.");
            }

            $customerDepositRef = null;
            if ($paymentMethod === 'Deposit' && $paidAmount > 0) {
                $customer = \App\Models\Customer::findOrFail($data['customer_id']);
                if ($customer->deposit_balance < $paidAmount) {
                    throw new \Exception("Saldo deposit pelanggan tidak mencukupi. Sisa saldo: Rp " . number_format($customer->deposit_balance, 0, ',', '.'));
                }
                
                $customer->decrement('deposit_balance', $paidAmount);
                $customerDepositRef = $customer->deposits()->create([
                    'store_id' => $data['store_id'],
                    'amount' => $paidAmount,
                    'type' => 'usage',
                    'notes' => 'Pembayaran tagihan POS (Menunggu Invoice)',
                ]);
            }

            // Create sale
            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber($data['store_id']),
                'store_id' => $data['store_id'],
                'customer_id' => $data['customer_id'] ?? null,
                'date' => now()->toDateString(),
                'total' => $total,
                'paid_amount' => $paidAmount,
                'remaining_balance' => max(0, $remaining),
                'payment_method' => $paymentMethod,
                'payment_fee' => $paymentFee,
                'status' => $status,
                'created_by' => $userId,
            ]);

            // Pre-fetch products to avoid N+1 and get purchase_price
            $productIds = collect($data['items'])->pluck('product_id')->toArray();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            // Create sale items & reduce inventory
            foreach ($data['items'] as $item) {
                $product = $products->get($item['product_id']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'purchase_price' => $product ? $product->purchase_price : 0,
                    'subtotal' => $item['subtotal'],
                ]);

                // Reduce stock
                $this->inventoryService->reduceStock(
                    $data['store_id'],
                    $item['product_id'],
                    $item['qty']
                );

                // Auto create BoOrder if product is consignment
                if ($product && $product->is_consignment && $product->bo_id) {
                    $debtAmount = $product->purchase_price * $item['qty'];
                    
                    if ($debtAmount > 0) {
                        BoOrder::create([
                            'bo_id' => $product->bo_id,
                            'store_id' => $data['store_id'],
                            'date' => now()->toDateString(),
                            'due_date' => now()->addDays(30)->toDateString(),
                            'invoice_number' => 'AUTO-' . $sale->invoice_number,
                            'description' => "Konsinyasi dari Penjualan {$sale->invoice_number} - {$product->name} ({$item['qty']} {$product->unit})",
                            'total' => $debtAmount,
                            'paid_amount' => 0,
                            'remaining_balance' => $debtAmount,
                            'status' => BoOrderStatus::UNPAID,
                            'notes' => "Sistem Penjualan Otomatis",
                        ]);
                    }
                }
            }

            // Update deposit reference if using deposit
            if ($customerDepositRef) {
                $customerDepositRef->update([
                    'reference_id' => 'SALE-' . $sale->id,
                    'notes' => "Pembayaran tagihan {$sale->invoice_number}",
                ]);
            }

            // Create income record for paid amount (only if NOT using Deposit, as Deposit already created Income when topped up)
            if ($paidAmount > 0 && $paymentMethod !== 'Deposit') {
                Income::create([
                    'store_id' => $data['store_id'],
                    'date' => now()->toDateString(),
                    'amount' => max(0, $paidAmount - $paymentFee),
                    'description' => "Penjualan {$sale->invoice_number} ({$paymentMethod})",
                    'source_type' => Sale::class,
                    'source_id' => $sale->id,
                ]);
            }

            // Create receivable if not fully paid
            if ($status === SaleStatus::UNPAID && !empty($data['customer_id'])) {
                Receivable::create([
                    'sale_id' => $sale->id,
                    'customer_id' => $data['customer_id'],
                    'store_id' => $data['store_id'],
                    'total' => $remaining,
                    'paid' => 0,
                    'remaining' => $remaining,
                    'due_date' => now()->addDays(30)->toDateString(),
                    'status' => ReceivableStatus::UNPAID,
                ]);
            }

            return $sale->load(['items.product', 'customer', 'store']);
        });
    }
}
