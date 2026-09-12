<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $service)
    {
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->isSuperAdmin() ? session('active_store_id') : $user->store_id;

        $stats = $this->service->getStats($storeId);
        $chartData = $this->service->getSalesChartData($storeId);
        $bestSelling = $this->service->getBestSellingProducts($storeId);
        $latestTransactions = $this->service->getLatestTransactions($storeId);
        $lowStockProducts = $this->service->getLowStockProducts($storeId, 5);

        return view('dashboard', compact('stats', 'chartData', 'bestSelling', 'latestTransactions', 'lowStockProducts', 'storeId'));
    }
}
