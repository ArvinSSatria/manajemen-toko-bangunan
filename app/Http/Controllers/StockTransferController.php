<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use App\Models\Product;
use App\Models\Store;
use App\Services\StockTransferService;
use Illuminate\Http\Request;

class StockTransferController extends Controller
{
    public function __construct(protected StockTransferService $service)
    {
    }

    public function index(Request $request)
    {
        $query = StockTransfer::with(['fromStore', 'toStore', 'creator'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transfers = $query->paginate(15);
        $stores = Store::where('is_active', true)->get();
        $products = Product::with('inventories')->orderBy('name')->get();
        return view('transfers.index', compact('transfers', 'stores', 'products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'from_store_id' => 'required|exists:stores,id',
            'to_store_id' => 'required|exists:stores,id|different:from_store_id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $this->service->createTransfer($request->all(), auth()->id());

        return redirect()->route('transfers.index')->with('success', 'Transfer stok berhasil dibuat.');
    }

    public function show(StockTransfer $transfer)
    {
        $transfer->load(['fromStore', 'toStore', 'creator', 'items.product']);
        return view('transfers.show', compact('transfer'));
    }

    public function complete(StockTransfer $transfer)
    {
        try {
            $this->service->completeTransfer($transfer);
            return redirect()->route('transfers.show', $transfer)->with('success', 'Transfer berhasil diselesaikan. Stok telah dipindahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel(StockTransfer $transfer)
    {
        try {
            $this->service->cancelTransfer($transfer);
            return redirect()->route('transfers.show', $transfer)->with('success', 'Transfer berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
