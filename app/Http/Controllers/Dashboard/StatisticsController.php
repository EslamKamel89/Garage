<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class StatisticsController extends Controller {
    public function index(Request $request) {
        // Total Revenue (actual_paid_amount)
        $totalRevenue = Invoice::sum('actual_paid_amount');

        // Total Invoices & Status Breakdown
        $invoiceStats = Invoice::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalInvoices = $invoiceStats->sum();
        $paidInvoices = $invoiceStats->get('paid', 0);
        $unpaidInvoices = $invoiceStats->get('unpaid', 0);
        $partialInvoices = $invoiceStats->get('partial', 0);

        // Revenue vs Last Month
        $currentMonthRevenue = Invoice::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('actual_paid_amount');

        $lastMonthRevenue = Invoice::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('actual_paid_amount');

        $revenueChange = $lastMonthRevenue > 0
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        // Top Selling Products
        $topProducts = DB::table('invoice_items')
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name',
                'categories.name as category_name',
                DB::raw('SUM(invoice_items.quantity) as total_quantity'),
                DB::raw('SUM(invoice_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Most Common Services (Labors)
        $laborStats = [];
        $invoices = Invoice::whereNotNull('labor_info')->get();

        $laborMap = [];
        foreach ($invoices as $invoice) {
            foreach ($invoice->labor_info['items'] ?? [] as $item) {
                $name = $item['name'];
                $fee = $item['fee'];
                if (!isset($laborMap[$name])) {
                    $laborMap[$name] = ['count' => 0, 'total_fee' => 0];
                }
                $laborMap[$name]['count']++;
                $laborMap[$name]['total_fee'] += $fee;
            }
        }

        // Sort by count
        uasort($laborMap, fn($a, $b) => $b['count'] <=> $a['count']);
        $topLabors = array_slice($laborMap, 0, 5, true);

        // Low Stock Alert
        $lowStockProducts = Product::where('quantity', '<=', 'min_stock_quantity')
            ->orderByRaw('quantity - min_stock_quantity')
            ->get();

        // Recent Invoices
        $recentInvoices = Invoice::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Client Stats
        $totalClients = Client::count();
        $newClientsThisMonth = Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $topClient = DB::table('invoices')
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->select('clients.id', 'clients.name', DB::raw('count(*) as invoice_count'))
            ->groupBy('clients.id', 'clients.name')
            ->orderByDesc('invoice_count')
            ->first();

        // Monthly Revenue (Last 6 Months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $amount = Invoice::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('actual_paid_amount');

            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'label' => $this->arabicMonth($month->format('M')),
                'revenue' => $amount,
            ];
        }

        return inertia('Dashboard', [
            'stats' => [
                'totalRevenue' => $totalRevenue,
                'totalInvoices' => $totalInvoices,
                'paidInvoices' => $paidInvoices,
                'unpaidInvoices' => $unpaidInvoices,
                'partialInvoices' => $partialInvoices,
                'revenueChange' => $revenueChange,
                'currentMonthRevenue' => $currentMonthRevenue,
                'lastMonthRevenue' => $lastMonthRevenue,
            ],
            'topProducts' => $topProducts,
            'topLabors' => $topLabors,
            'lowStockProducts' => $lowStockProducts,
            'recentInvoices' => $recentInvoices,
            'clientStats' => [
                'total' => $totalClients,
                'newThisMonth' => $newClientsThisMonth,
                'topClient' => $topClient,
            ],
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }

    private function arabicMonth(string $engMonth): string {
        return match ($engMonth) {
            'Jan' => 'يناير',
            'Feb' => 'فبراير',
            'Mar' => 'مارس',
            'Apr' => 'أبريل',
            'May' => 'مايو',
            'Jun' => 'يونيو',
            'Jul' => 'يوليو',
            'Aug' => 'أغسطس',
            'Sep' => 'سبتمبر',
            'Oct' => 'أكتوبر',
            'Nov' => 'نوفمبر',
            'Dec' => 'ديسمبر',
            default => $engMonth,
        };
    }
}
