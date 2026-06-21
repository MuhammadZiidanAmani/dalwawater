<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        if (! auth()->check()) {
            return view('auth.login');
        }

        $startDate = $request->query('start_date', Carbon::today()->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());
        $selectedUserId = $request->user()?->isAdmin() ? $request->query('user_id') : null;
        $selectedPaymentStatus = $request->query('payment_status');
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $products = Product::orderBy('nama_barang')->get();
        $activeProducts = Product::where('status', 'active')->orderBy('nama_barang')->get();
        $transactions = Transaction::visibleTo($request->user())->with(['user', 'details.product'])->latest()->limit(15)->get();
        $latestTransaction = Transaction::visibleTo($request->user())->with(['user', 'details.product'])->latest()->first();

        $rangeTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereBetween('created_at', [$start, $end])
            ->when($selectedUserId, fn ($query) => $query->where('user_id', $selectedUserId))
            ->when($selectedPaymentStatus, fn ($query) => $query->where('payment_status', $selectedPaymentStatus));

        $lowStockProducts = Product::where('stok', '<=', 20)
            ->orderBy('stok')
            ->orderBy('nama_barang')
            ->limit(5)
            ->get();

        $salesChart = collect(range(6, 0))->map(function (int $daysAgo) use ($request): array {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->translatedFormat('D'),
                'count' => Transaction::visibleTo($request->user())->whereDate('created_at', $date)->count(),
            ];
        });

        $cashiers = User::whereIn('role', ['admin', 'cashier'])
            ->withSum(['transactions as today_sales' => fn ($query) => $query->whereDate('created_at', Carbon::today())->where('payment_status', 'paid')], 'total')
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        $monthlyProductSales = Product::query()
            ->leftJoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')
            ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where(function ($query) use ($start, $end) {
                $query->whereNull('transactions.id')
                    ->orWhereBetween('transactions.created_at', [$start, $end]);
            })
            ->select('products.nama_barang', DB::raw('COALESCE(SUM(transaction_details.qty), 0) as qty'))
            ->groupBy('products.id', 'products.nama_barang')
            ->orderByDesc('qty')
            ->limit(6)
            ->get();

        $todayTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereDate('created_at', Carbon::today());

        $stats = [
            'total_stock' => Product::sum('stok'),
            'today_transactions' => (clone $todayTransactionsQuery)->count(),
            'today_income' => (clone $todayTransactionsQuery)->where('payment_status', 'paid')->sum('total'),
            'today_items' => DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereIn('transactions.id', (clone $todayTransactionsQuery)->select('id'))
                ->sum('transaction_details.qty'),
            'low_stock' => Product::where('stok', '<=', 20)->count(),
        ];

        $reportQuery = Transaction::visibleTo($request->user())->with(['user', 'details.product'])->latest();
        $reportBaseQuery = Transaction::visibleTo($request->user());

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            $reportQuery->whereBetween('created_at', [$start, $end]);
            $reportBaseQuery->whereBetween('created_at', [$start, $end]);
        }

        $reportTransactions = $reportQuery->get();
        $purchaseHistory = $request->user()->isAdmin()
            ? StockIn::with('product')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get()
            : collect();

        $reportStats = [
            'transactions' => (clone $reportBaseQuery)->count(),
            'income' => (clone $reportBaseQuery)->where('payment_status', 'paid')->sum('total'),
            'items' => DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereIn('transactions.id', (clone $reportBaseQuery)->select('id'))
                ->sum('transaction_details.qty'),
            'cash_income' => (clone $reportBaseQuery)->where('payment_type', 'cash')->sum('total'),
            'transfer_income' => (clone $reportBaseQuery)->where('payment_type', 'transfer')->sum('total'),
        ];

        $categories = Category::all();

        return view(auth()->user()->isAdmin() ? 'dashboard.admin' : 'dashboard.kasir', [
            'categories' => $categories,
            'products' => $products,
            'activeProducts' => $activeProducts,
            'stockIns' => StockIn::with('product')->latest()->limit(10)->get(),
            'transactions' => $transactions,
            'latestTransaction' => $latestTransaction,
            'lowStockProducts' => $lowStockProducts,
            'salesChart' => $salesChart,
            'cashiers' => $cashiers,
            'monthlyProductSales' => $monthlyProductSales,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedUserId' => $selectedUserId,
            'selectedPaymentStatus' => $selectedPaymentStatus,
            'reportTransactions' => $reportTransactions,
            'reportStats' => $reportStats,
            'historyTransactions' => (clone $rangeTransactionsQuery)->with(['user', 'details.product'])->latest()->get(),
            'purchaseHistory' => $purchaseHistory,
        ]);
    }
}
