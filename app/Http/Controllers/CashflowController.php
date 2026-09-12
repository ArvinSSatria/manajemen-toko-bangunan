<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->isSuperAdmin() ? ($request->input('store_id') ?: session('active_store_id')) : $user->store_id;

        $period = $request->input('period', 'monthly');
        $month = $request->input('month', now()->format('Y-m'));

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $incomeQuery = Income::whereBetween('date', [$startDate, $endDate]);
        $expenseQuery = Expense::whereBetween('date', [$startDate, $endDate]);

        if ($storeId) {
            $incomeQuery->where('store_id', $storeId);
            $expenseQuery->where('store_id', $storeId);
        }

        $incomes = $incomeQuery->with('store')->get();
        $expenses = $expenseQuery->with('store')->get();

        $cashflow = collect();

        foreach ($incomes as $inc) {
            $cashflow->push([
                'date' => $inc->date,
                'store_name' => $inc->store ? $inc->store->name : 'Pusat',
                'description' => $inc->description,
                'type' => 'Pemasukan',
                'is_in' => true,
                'amount' => $inc->amount,
            ]);
        }

        foreach ($expenses as $exp) {
            $cashflow->push([
                'date' => $exp->date,
                'store_name' => $exp->store ? $exp->store->name : 'Pusat',
                'description' => $exp->description,
                'type' => 'Pengeluaran',
                'is_in' => false,
                'amount' => $exp->amount,
            ]);
        }

        $cashflow = $cashflow->sortByDesc('date')->values();

        $totalIn = $incomes->sum('amount');
        $totalOut = $expenses->sum('amount');
        $netCash = $totalIn - $totalOut;

        $stores = Store::where('is_active', true)->get();

        return view('cashflow.index', compact(
            'cashflow', 'totalIn', 'totalOut', 'netCash',
            'stores', 'storeId', 'month', 'period'
        ));
    }
}
