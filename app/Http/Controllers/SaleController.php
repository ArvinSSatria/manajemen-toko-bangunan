<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Store;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->isSuperAdmin() ? ($request->input('store_id') ?: session('active_store_id')) : $user->store_id;

        $query = Sale::with(['store', 'customer', 'creator'])->latest();

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $sales = $query->paginate(15);
        $stores = Store::where('is_active', true)->get();

        return view('sales.index', compact('sales', 'stores', 'storeId'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product.bo', 'customer', 'store', 'creator', 'receivable']);
        return view('sales.show', compact('sale'));
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['items.product.bo', 'customer', 'store']);
        return view('sales.receipt', compact('sale'));
    }
}
