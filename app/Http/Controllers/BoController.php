<?php

namespace App\Http\Controllers;

use App\Models\Bo;
use App\Models\BoOrder;
use App\Models\Store;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoController extends Controller
{
    public function index(Request $request)
    {
        $query = Bo::withCount('orders');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $bos = $query->latest()->paginate(15);

        // Due soon orders
        $dueSoonOrders = BoOrder::with(['bo', 'store'])
            ->where('status', 'UNPAID')
            ->where('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date')
            ->get();

        return view('bo.index', compact('bos', 'dueSoonOrders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Bo::create($data);
        return redirect()->route('bo.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Bo $bo)
    {
        $bo->load(['orders.store']);
        $stores = Store::where('is_active', true)->get();
        return view('bo.show', compact('bo', 'stores'));
    }

    public function update(Request $request, Bo $bo)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $bo->update($data);
        return redirect()->route('bo.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function storeOrder(Request $request, Bo $bo)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'invoice_number' => 'required|string|max:255',
            'description' => 'required|string',
            'total_amount' => 'required|numeric|min:1',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        $total = $data['total_amount'];
        $paid = $data['paid_amount'] ?? 0;
        $remaining = max(0, $total - $paid);
        $status = $remaining <= 0 ? 'PAID' : 'UNPAID';

        DB::transaction(function () use ($bo, $data, $total, $paid, $remaining, $status) {
            $order = $bo->orders()->create([
                'store_id' => $data['store_id'],
                'date' => $data['date'],
                'due_date' => $data['due_date'] ?? $data['date'],
                'invoice_number' => $data['invoice_number'],
                'description' => $data['description'],
                'total' => $total,
                'paid_amount' => $paid,
                'remaining_balance' => $remaining,
                'status' => $status,
            ]);

            // Jika ada DP, catat sebagai Expense
            if ($paid > 0) {
                Expense::create([
                    'store_id' => $data['store_id'],
                    'date' => now()->toDateString(),
                    'amount' => $paid,
                    'description' => "DP Nota Pembelian {$data['invoice_number']} dari BO {$bo->name}",
                ]);
            }
        });

        return redirect()->route('bo.show', $bo)->with('success', 'Nota berhasil ditambahkan.');
    }

    public function payOrder(Request $request, BoOrder $order)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $order->remaining_balance,
        ]);

        $payment = $data['amount'];
        $newPaid = $order->paid_amount + $payment;
        $newRemaining = $order->total - $newPaid;
        $status = $newRemaining <= 0 ? 'PAID' : 'UNPAID';

        DB::transaction(function () use ($order, $payment, $newPaid, $newRemaining, $status) {
            $order->update([
                'paid_amount' => $newPaid,
                'remaining_balance' => max(0, $newRemaining),
                'status' => $status,
            ]);

            Expense::create([
                'store_id' => $order->store_id,
                'date' => now()->toDateString(),
                'amount' => $payment,
                'description' => "Pembayaran Nota {$order->invoice_number} ke BO {$order->bo->name}",
            ]);
        });

        return redirect()->back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function destroy(Bo $bo)
    {
        $bo->delete();
        return redirect()->route('bo.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
