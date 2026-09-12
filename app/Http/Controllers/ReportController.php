<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $stores = Store::where('is_active', true)->get();
        return view('reports.index', compact('stores'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:sales,profit_loss,inventory',
            'store_id' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'format' => 'required|in:pdf,excel',
        ]);

        $reportType = $request->report_type;
        $storeId = $request->store_id === 'all' ? null : $request->store_id;
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $store = $storeId ? Store::find($storeId) : null;

        $data = [];

        if ($reportType === 'sales') {
            $query = Sale::with(['store', 'customer', 'items.product'])->whereBetween('created_at', [$startDate, $endDate]);
            if ($storeId) $query->where('store_id', $storeId);
            $data = $query->orderBy('created_at', 'desc')->get();
            
        } elseif ($reportType === 'profit_loss') {
            $salesQuery = Sale::with('items.product')->whereBetween('created_at', [$startDate, $endDate]);
            $expenseQuery = Expense::whereBetween('date', [$startDate, $endDate]);
            
            if ($storeId) {
                $salesQuery->where('store_id', $storeId);
                $expenseQuery->where('store_id', $storeId);
            }

            $sales = $salesQuery->get();
            $grossRevenue = $sales->sum('total');
            
            $cogs = 0;
            foreach ($sales as $sale) {
                foreach ($sale->items as $item) {
                    $cogs += ($item->qty * $item->product->purchase_price);
                }
            }
            
            $grossProfit = $grossRevenue - $cogs;
            $expenses = $expenseQuery->get();
            $totalExpense = $expenses->sum('amount');
            $netProfit = $grossProfit - $totalExpense;

            $data = [
                'gross_revenue' => $grossRevenue,
                'cogs' => $cogs,
                'gross_profit' => $grossProfit,
                'expenses' => $expenses,
                'total_expense' => $totalExpense,
                'net_profit' => $netProfit,
            ];

        } elseif ($reportType === 'inventory') {
            $query = Product::with(['category', 'inventories']);
            // Inventory valuation doesn't strictly depend on date, it's current snapshot
            // If storeId is specified, we'll only count stock in that store later in the view
            $data = $query->get();
        }

        // Return generic print-friendly view for both formats for simplicity right now
        // A real production app would use Laravel Excel / DomPDF here
        return view('reports.pdf', compact('reportType', 'store', 'startDate', 'endDate', 'data'));
    }
}
