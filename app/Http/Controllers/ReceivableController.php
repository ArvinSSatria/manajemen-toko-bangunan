<?php

namespace App\Http\Controllers;

use App\Models\Receivable;
use App\Models\Store;
use App\Services\ReceivableService;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function __construct(protected ReceivableService $service)
    {
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->isSuperAdmin() ? ($request->input('store_id') ?: session('active_store_id')) : $user->store_id;

        $query = Receivable::with(['customer', 'store', 'sale'])->latest();

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        // Mark overdue
        $this->service->markOverdue();

        $receivables = $query->paginate(15);
        $stores = Store::where('is_active', true)->get();

        return view('receivables.index', compact('receivables', 'stores', 'storeId'));
    }

    public function show(Receivable $receivable)
    {
        $receivable->load(['customer', 'store', 'sale.items.product', 'payments.creator']);
        return view('receivables.show', compact('receivable'));
    }

    public function pay(Request $request, Receivable $receivable)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $receivable->remaining,
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->service->recordPayment($receivable, $request->amount, $request->notes, auth()->id());
            return redirect()->route('receivables.show', $receivable)->with('success', 'Pembayaran berhasil dicatat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
