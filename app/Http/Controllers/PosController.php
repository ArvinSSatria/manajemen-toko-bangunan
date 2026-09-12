<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Product;
use App\Services\SaleService;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function __construct(protected SaleService $saleService)
    {
    }

    public function index()
    {
        $user = auth()->user();
        $storeId = $user->getActiveStoreId();

        // Kasir (POS) tidak bisa beroperasi dalam mode "Semua Toko". Harus spesifik 1 toko.
        // Jika Super Admin memilih "Semua Toko" (storeId = null), paksa ke toko pertama.
        if (!$storeId) {
            $firstStore = \App\Models\Store::where('is_active', true)->first();
            if ($firstStore) {
                $storeId = $firstStore->id;
                session(['active_store_id' => $storeId, 'active_store_name' => $firstStore->name]);
            }
        }

        $products = Product::with(['category:id,name', 'inventories' => function($q) use ($storeId) {
                $q->where('store_id', $storeId)->select('product_id', 'stock');
            }])
            ->whereHas('inventories', fn($q) => $q->where('store_id', $storeId)->where('stock', '>', 0))
            ->select('id', 'name', 'product_code', 'selling_price', 'unit', 'category_id', 'is_consignment')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'product_code' => $product->product_code,
                    'selling_price' => $product->selling_price,
                    'unit' => $product->unit,
                    'is_consignment' => $product->is_consignment,
                    'category' => ['name' => $product->category->name ?? ''],
                    'current_stock' => $product->inventories->first()?->stock ?? 0,
                ];
            });

        $customers = Customer::orderBy('name')->select('id', 'name', 'phone')->get();

        return view('pos.index', compact('products', 'customers', 'storeId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'customer_id' => [
                'nullable',
                'exists:customers,id',
                function ($attribute, $value, $fail) use ($request) {
                    $total = collect($request->items)->sum(function ($item) {
                        return ($item['qty'] ?? 0) * ($item['price'] ?? 0);
                    });
                    if ($request->paid_amount < $total && empty($value)) {
                        $fail('Pelanggan wajib dipilih jika pembayaran kurang dari total belanja (Transaksi Belum Lunas).');
                    }
                },
            ],
            'paid_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            $sale = $this->saleService->createSale($request->all(), auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'sale' => $sale,
                'redirect' => route('sales.receipt', $sale->id),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
