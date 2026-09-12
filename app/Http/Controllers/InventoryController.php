<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Store;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->isSuperAdmin() ? ($request->input('store_id') ?: session('active_store_id')) : $user->store_id;

        $query = Inventory::with(['product.category', 'product.bo', 'store']);

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('product_code', 'like', "%{$request->search}%"));
        }

        if ($request->filled('low_stock')) {
            $query->whereColumn('stock', '<=', 'minimum_stock');
        }

        $inventories = $query->latest()->paginate(20);
        $stores = Store::where('is_active', true)->get();

        return view('inventory.index', compact('inventories', 'stores', 'storeId'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $inventory->update($data);

        return redirect()->route('inventory.index')->with('success', 'Stok berhasil diperbarui.');
    }
}
