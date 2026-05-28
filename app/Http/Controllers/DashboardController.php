<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        if (! auth()->check()) {
            return view('auth.login');
        }

        $startDate = $request->query('start_date', Carbon::today()->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());
        $selectedUserId = $request->query('user_id');
        $selectedPaymentStatus = $request->query('payment_status');
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $products = Product::orderBy('nama_barang')->get();
        $activeProducts = Product::where('status', 'active')->orderBy('nama_barang')->get();
        $transactions = Transaction::with(['user', 'details.product'])->latest()->limit(15)->get();
        $latestTransaction = Transaction::with(['user', 'details.product'])->latest()->first();

        $rangeTransactionsQuery = Transaction::whereBetween('created_at', [$start, $end])
            ->when($selectedUserId, fn ($query) => $query->where('user_id', $selectedUserId))
            ->when($selectedPaymentStatus, fn ($query) => $query->where('payment_status', $selectedPaymentStatus));

        $topProducts = Product::query()
            ->leftJoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')
            ->select('products.*', DB::raw('COALESCE(SUM(transaction_details.qty), 0) as sold_qty'))
            ->groupBy(
                'products.id',
                'products.kode_barang',
                'products.nama_barang',
                'products.kategori',
                'products.harga_modal',
                'products.harga_jual',
                'products.stok',
                'products.satuan',
                'products.status',
                'products.created_at',
                'products.updated_at'
            )
            ->orderByDesc('sold_qty')
            ->limit(5)
            ->get();

        $salesChart = collect(range(6, 0))->map(function (int $daysAgo): array {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->translatedFormat('D'),
                'count' => Transaction::whereDate('created_at', $date)->count(),
            ];
        });

        $cashiers = User::whereIn('role', ['admin', 'cashier'])
            ->withSum(['transactions as today_sales' => fn ($query) => $query->whereDate('created_at', Carbon::today())], 'total')
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

        $stats = [
            'total_stock' => Product::sum('stok'),
            'today_transactions' => (clone $rangeTransactionsQuery)->count(),
            'today_income' => (clone $rangeTransactionsQuery)->sum('total'),
            'low_stock' => Product::where('stok', '<=', 20)->count(),
        ];


        
        $reportQuery = Transaction::with(['user', 'details.product'])->latest();
        $reportBaseQuery = Transaction::query();

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            
            $reportQuery->whereBetween('created_at', [$start, $end]);
            $reportBaseQuery->whereBetween('created_at', [$start, $end]);
        }

        $reportTransactions = $reportQuery->get();

        $reportStats = [
            'transactions' => (clone $reportBaseQuery)->count(),
            'income' => (clone $reportBaseQuery)->sum('total'),
            'items' => DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereIn('transactions.id', (clone $reportBaseQuery)->select('id'))
                ->sum('transaction_details.qty'),
            'cash_income' => (clone $reportBaseQuery)->where('payment_type', 'cash')->sum('total'),
            'transfer_income' => (clone $reportBaseQuery)->where('payment_type', 'transfer')->sum('total'),
        ];

        $categories = \App\Models\Category::all();

        return view(auth()->user()->isAdmin() ? 'dashboard.admin' : 'dashboard.kasir', [
            'categories' => $categories,
            'products' => $products,
            'activeProducts' => $activeProducts,
            'stockIns' => StockIn::with('product')->latest()->limit(10)->get(),
            'transactions' => $transactions,
            'latestTransaction' => $latestTransaction,
            'topProducts' => $topProducts,
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
        ]);
    }
}
