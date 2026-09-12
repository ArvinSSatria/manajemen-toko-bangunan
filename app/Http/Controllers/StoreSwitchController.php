<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreSwitchController extends Controller
{
    /**
     * Switch the active store for Super Admin.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin()) {
            abort(403);
        }

        $storeId = $request->input('store_id');

        if ($storeId === 'all') {
            session()->forget(['active_store_id', 'active_store_name']);
        } else {
            $store = Store::findOrFail($storeId);
            session([
                'active_store_id' => $store->id,
                'active_store_name' => $store->name,
            ]);
        }

        return redirect()->back()->with('success', 'Toko berhasil diubah.');
    }
}
