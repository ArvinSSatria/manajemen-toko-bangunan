<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Store;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->isSuperAdmin() ? ($request->input('store_id') ?: session('active_store_id')) : $user->store_id;

        $query = Expense::with('store')->latest();

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->paginate(15);
        $stores = Store::where('is_active', true)->get();

        return view('expenses.index', compact('expenses', 'stores', 'storeId'));
    }

    public function create()
    {
        $stores = Store::where('is_active', true)->get();
        return view('expenses.form', ['expense' => new Expense(), 'stores' => $stores]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:500',
        ]);

        Expense::create($data);
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit(Expense $expense)
    {
        $stores = Store::where('is_active', true)->get();
        return view('expenses.form', compact('expense', 'stores'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:500',
        ]);

        $expense->update($data);
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
