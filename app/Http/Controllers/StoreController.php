<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\StoreRepository;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(protected StoreRepository $repository)
    {
    }

    public function index(Request $request)
    {
        $stores = $this->repository->paginate(15, $request->only('search'));
        return view('stores.index', compact('stores'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $this->repository->create($data);

        return redirect()->route('stores.index')->with('success', 'Toko berhasil ditambahkan.');
    }


    public function update(Request $request, Store $store)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $this->repository->update($store->id, $data);

        return redirect()->route('stores.index')->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Store $store)
    {
        $this->repository->delete($store->id);
        return redirect()->route('stores.index')->with('success', 'Toko berhasil dihapus.');
    }
}
