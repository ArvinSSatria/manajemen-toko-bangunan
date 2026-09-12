<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Store;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->isSuperAdmin() ? ($request->input('store_id') ?: session('active_store_id')) : $user->store_id;

        $query = Income::with(['store', 'source'])->latest();

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($request->filled('type')) {
            if ($request->type === 'revenue') {
                $query->whereIn('source_type', ['App\Models\Sale', 'App\Models\ReceivablePayment']);
            } elseif ($request->type === 'deposit') {
                $query->where('source_type', 'App\Models\Customer');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $incomes = $query->paginate(15);
        $stores = Store::where('is_active', true)->get();

        return view('incomes.index', compact('incomes', 'stores', 'storeId'));
    }
}
