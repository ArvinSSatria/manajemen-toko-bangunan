<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Sale;
use App\Models\Receivable;
use App\Models\BoOrder;
use App\Models\Inventory;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get dashboard statistics.
     */
    public function getStats(?int $storeId = null): array
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        // Sales queries
        $salesQuery = Sale::query();
        $incomeQuery = Income::query();
        $expenseQuery = Expense::query();
        $receivableQuery = Receivable::query();
        $boOrderQuery = BoOrder::query();
        $inventoryQuery = Inventory::query()->join('products', 'inventories.product_id', '=', 'products.id');
        $saleItemsQuery = SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.date', [$monthStart, $monthEnd]);

        if ($storeId) {
            $salesQuery->where('store_id', $storeId);
            $incomeQuery->where('store_id', $storeId);
            $expenseQuery->where('store_id', $storeId);
            $receivableQuery->where('store_id', $storeId);
            $boOrderQuery->where('store_id', $storeId);
            $inventoryQuery->where('inventories.store_id', $storeId);
            $saleItemsQuery->where('sales.store_id', $storeId);
        }

        $monthlySales = (clone $salesQuery)->whereBetween('date', [$monthStart, $monthEnd])->sum('total');
        $monthlyCogs = (clone $saleItemsQuery)->sum(DB::raw('sale_items.qty * COALESCE(NULLIF(sale_items.purchase_price, 0), products.purchase_price)'));

        return [
            'today_sales' => (clone $salesQuery)->whereDate('date', $today)->sum('total'),
            'today_transactions' => (clone $salesQuery)->whereDate('date', $today)->count(),
            'weekly_sales' => (clone $salesQuery)->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total'),
            'monthly_sales' => $monthlySales,
            'monthly_income' => (clone $incomeQuery)->whereBetween('date', [$monthStart, $monthEnd])->sum('amount'),
            'monthly_expense' => (clone $expenseQuery)->whereBetween('date', [$monthStart, $monthEnd])->sum('amount'),
            'total_receivable' => (clone $receivableQuery)->where('status', '!=', 'PAID')->sum('remaining'),
            'active_receivables' => (clone $receivableQuery)->where('status', '!=', 'PAID')->count(),
            'total_bo_debt' => (clone $boOrderQuery)->where('status', 'UNPAID')->sum('total'),
            'total_inventory_value' => (clone $inventoryQuery)->sum(DB::raw('inventories.stock * products.purchase_price')),
            'estimated_gross_profit' => $monthlySales - $monthlyCogs,
        ];
    }

    /**
     * Get low stock products alert.
     */
    public function getLowStockProducts(?int $storeId = null, int $limit = 5)
    {
        $query = Inventory::with(['product.category', 'store'])
            ->whereRaw('stock <= minimum_stock');

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        return $query->orderBy('stock', 'asc')->take($limit)->get();
    }

    /**
     * Get sales chart data for current month.
     */
    public function getSalesChartData(?int $storeId = null): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $query = Sale::select(
            DB::raw('DATE(date) as sale_date'),
            DB::raw('SUM(total) as total_sales'),
            DB::raw('COUNT(*) as total_transactions')
        )
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->groupBy(DB::raw('DATE(date)'))
        ->orderBy('sale_date');

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        $results = $query->get();

        $labels = [];
        $salesData = [];
        $transactionData = [];

        $current = $startOfMonth->copy();
        while ($current <= Carbon::now()) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->format('d M');

            $dayData = $results->firstWhere('sale_date', $dateStr);
            $salesData[] = $dayData ? (float) $dayData->total_sales : 0;
            $transactionData[] = $dayData ? $dayData->total_transactions : 0;

            $current->addDay();
        }

        return [
            'labels' => $labels,
            'sales' => $salesData,
            'transactions' => $transactionData,
        ];
    }

    /**
     * Get best selling products.
     */
    public function getBestSellingProducts(?int $storeId = null, int $limit = 5)
    {
        $query = SaleItem::select(
            'product_id',
            DB::raw('SUM(qty) as total_qty'),
            DB::raw('SUM(subtotal) as total_revenue')
        )
        ->with('product')
        ->groupBy('product_id')
        ->orderByDesc('total_qty')
        ->limit($limit);

        if ($storeId) {
            $query->whereHas('sale', fn($q) => $q->where('store_id', $storeId));
        }

        return $query->get();
    }
    /**
     * Get latest transactions (sales).
     */
    public function getLatestTransactions(?int $storeId = null, int $limit = 5)
    {
        $query = \App\Models\Sale::with(['customer', 'creator'])
            ->latest();

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        return $query->limit($limit)->get();
    }
}
