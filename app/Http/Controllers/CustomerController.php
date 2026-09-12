<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDeposit;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $customers = $query->latest()->paginate(15);
        return view('customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $deposits = $customer->deposits()->latest()->paginate(20);
        return view('customers.show', compact('customer', 'deposits'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create($data);
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }


    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($data);
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    public function deposit(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $customer) {
            $customer->deposits()->create([
                'store_id' => session('active_store_id'),
                'amount' => $request->amount,
                'type' => 'deposit',
                'notes' => $request->notes ?? 'Top-up deposit manual',
            ]);

            $customer->increment('deposit_balance', $request->amount);

            // Record income for the deposit received
            Income::create([
                'store_id' => session('active_store_id'),
                'date' => now()->toDateString(),
                'amount' => $request->amount,
                'description' => "Titip Dana / Deposit dari Pelanggan: {$customer->name}",
                'source_type' => Customer::class,
                'source_id' => $customer->id,
            ]);
        });

        return redirect()->route('customers.index')->with('success', 'Deposit pelanggan berhasil ditambahkan.');
    }
}
