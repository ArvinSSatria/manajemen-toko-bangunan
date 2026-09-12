<?php

namespace App\Services;

use App\Enums\ReceivableStatus;
use App\Models\Income;
use App\Models\Receivable;
use App\Models\ReceivablePayment;
use Illuminate\Support\Facades\DB;

class ReceivableService
{
    /**
     * Record a partial or full payment on a receivable.
     */
    public function recordPayment(Receivable $receivable, float $amount, ?string $notes, int $userId): ReceivablePayment
    {
        if ($amount <= 0) {
            throw new \Exception('Jumlah pembayaran harus lebih dari 0.');
        }

        if ($amount > (float) $receivable->remaining) {
            throw new \Exception('Jumlah pembayaran melebihi sisa piutang.');
        }

        return DB::transaction(function () use ($receivable, $amount, $notes, $userId) {
            // Record the payment
            $payment = ReceivablePayment::create([
                'receivable_id' => $receivable->id,
                'date' => now()->toDateString(),
                'amount' => $amount,
                'notes' => $notes,
                'created_by' => $userId,
            ]);

            // Update receivable
            $receivable->paid = (float) $receivable->paid + $amount;
            $receivable->remaining = (float) $receivable->total - (float) $receivable->paid;

            if ($receivable->remaining <= 0) {
                $receivable->remaining = 0;
                $receivable->status = ReceivableStatus::PAID;

                // Also update the related sale
                if ($receivable->sale) {
                    $receivable->sale->update([
                        'paid_amount' => (float) $receivable->sale->paid_amount + $amount,
                        'remaining_balance' => 0,
                        'status' => 'PAID',
                    ]);
                }
            }

            $receivable->save();

            // Create income record
            Income::create([
                'store_id' => $receivable->store_id,
                'date' => now()->toDateString(),
                'amount' => $amount,
                'description' => "Pembayaran piutang - {$receivable->customer->name}",
                'source_type' => ReceivablePayment::class,
                'source_id' => $payment->id,
            ]);

            return $payment;
        });
    }

    /**
     * Mark overdue receivables.
     */
    public function markOverdue(): int
    {
        return Receivable::where('status', ReceivableStatus::UNPAID)
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => ReceivableStatus::OVERDUE]);
    }
}
