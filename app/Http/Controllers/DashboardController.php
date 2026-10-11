<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CashierShift;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOpname;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        if (! auth()->check()) {
            return view('auth.login');
        }

        if (! $request->user()->isActive()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Akun tidak aktif.');
        }

        $filters = $request->validate([
            'page' => ['nullable', 'string', 'max:80'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'payment_status' => ['nullable', 'in:paid,pending'],
            'payment_type' => ['nullable', 'in:cash,transfer'],
        ]);

        $startDate = $filters['start_date'] ?? Carbon::today()->toDateString();
        $endDate = $filters['end_date'] ?? Carbon::today()->toDateString();
        $selectedUserId = $request->user()?->isAdmin() ? ($filters['user_id'] ?? null) : null;
        $selectedPaymentStatus = $filters['payment_status'] ?? null;
        $selectedPaymentType = $filters['payment_type'] ?? null;
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $products = Product::orderBy('nama_barang')->get();
        $activeProducts = Product::where('status', 'active')->orderBy('nama_barang')->get();
        $transactions = Transaction::visibleTo($request->user())->with(['user', 'details.product'])->latest()->limit(15)->get();
        $latestTransaction = Transaction::visibleTo($request->user())->with(['user', 'details.product'])->latest()->first();

        $rangeTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereBetween('created_at', [$start, $end])
            ->when($selectedUserId, fn ($query) => $query->where('user_id', $selectedUserId))
            ->when($selectedPaymentStatus, fn ($query) => $query->where('payment_status', $selectedPaymentStatus))
            ->when($selectedPaymentType, fn ($query) => $query->where('payment_type', $selectedPaymentType));

        $lowStockProducts = Product::where('stok', '<=', 20)
            ->orderBy('stok')
            ->orderBy('nama_barang')
            ->limit(5)
            ->get();

        $salesChart = collect(range(11, 0))->map(function (int $monthsAgo) use ($request): array {
            $month = Carbon::today()->startOfMonth()->subMonths($monthsAgo);

            return [
                'label' => $month->translatedFormat('M y'),
                'count' => (int) Transaction::visibleTo($request->user())
                    ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                    ->where('status', '!=', 'void')
                    ->where('payment_status', 'paid')
                    ->sum('total'),
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
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'void');

        $allTransactionsQuery = Transaction::visibleTo($request->user())
            ->where('status', '!=', 'void');

        $stats = [
            'total_stock' => Product::sum('stok'),
            'total_transactions' => (clone $allTransactionsQuery)->count(),
            'total_transaction_amount' => (clone $allTransactionsQuery)->sum('total'),
            'total_income' => (clone $allTransactionsQuery)->where('payment_status', 'paid')->sum('total'),
            'today_transactions' => (clone $todayTransactionsQuery)->count(),
            'today_income' => (clone $todayTransactionsQuery)->where('payment_status', 'paid')->sum('total'),
            'today_pending' => (clone $todayTransactionsQuery)->where('payment_status', 'pending')->sum('total'),
            'today_cash' => (clone $todayTransactionsQuery)->where('payment_type', 'cash')->where('payment_status', 'paid')->sum('total'),
            'today_transfer' => (clone $todayTransactionsQuery)->where('payment_type', 'transfer')->where('payment_status', 'paid')->sum('total'),
            'today_void' => Transaction::visibleTo($request->user())
                ->whereDate('created_at', Carbon::today())
                ->where('status', 'void')
                ->sum('total'),
            'unverified_transfers' => Transaction::visibleTo($request->user())
                ->where('payment_type', 'transfer')
                ->where('payment_status', 'paid')
                ->where('status', '!=', 'void')
                ->whereNull('payment_verified_at')
                ->count(),
            'today_items' => DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereIn('transactions.id', (clone $todayTransactionsQuery)->select('id'))
                ->sum('transaction_details.qty'),
            'low_stock' => Product::where('stok', '<=', 20)->count(),
            'empty_stock' => Product::where('stok', '<=', 0)->count(),
            'stock_in_today' => StockIn::whereDate('tanggal', Carbon::today())->sum('qty'),
            'active_shifts' => CashierShift::open()->count(),
        ];

        $reportQuery = Transaction::visibleTo($request->user())->with(['user', 'details.product'])->latest();
        $reportBaseQuery = Transaction::visibleTo($request->user())
            ->where('status', '!=', 'void');

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            $reportQuery->whereBetween('created_at', [$start, $end]);
            $reportBaseQuery->whereBetween('created_at', [$start, $end]);
        }

        if ($selectedUserId) {
            $reportQuery->where('user_id', $selectedUserId);
            $reportBaseQuery->where('user_id', $selectedUserId);
        }

        if ($selectedPaymentStatus) {
            $reportQuery->where('payment_status', $selectedPaymentStatus);
            $reportBaseQuery->where('payment_status', $selectedPaymentStatus);
        }

        if ($selectedPaymentType) {
            $reportQuery->where('payment_type', $selectedPaymentType);
            $reportBaseQuery->where('payment_type', $selectedPaymentType);
        }

        $reportTransactions = $reportQuery->get();
        $stockEntries = $request->user()->isAdmin()
            ? StockIn::with('product')
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get()
            : collect();
        $purchaseHistory = $request->user()->isAdmin()
            ? StockIn::with('product')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get()
            : collect();
        $expenses = $request->user()->isAdmin()
            ? Expense::with('user')
                ->whereBetween('spent_at', [$startDate, $endDate])
                ->orderByDesc('spent_at')
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
        $suppliers = $request->user()->isAdmin()
            ? Supplier::orderBy('nama')->get()
            : collect();
        $currentShift = $request->user()->cashierShifts()
            ->open()
            ->latest('opened_at')
            ->first();
        $recentShifts = $request->user()->isAdmin()
            ? CashierShift::with('user')->latest('opened_at')->limit(10)->get()
            : $request->user()->cashierShifts()->latest('opened_at')->limit(5)->get();
        $stockOpnames = $request->user()->isAdmin()
            ? StockOpname::with(['user', 'adjustments.product'])->latest()->limit(10)->get()
            : collect();
        $auditLogs = $request->user()->isAdmin()
            ? AuditLog::with('user')->latest()->limit(20)->get()
            : collect();

        return view(auth()->user()->isAdmin() ? 'dashboard.admin' : 'dashboard.kasir', [
            'categories' => $categories,
            'suppliers' => $suppliers,
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
            'selectedPaymentType' => $selectedPaymentType,
            'reportTransactions' => $reportTransactions,
            'reportStats' => $reportStats,
            'historyTransactions' => (clone $rangeTransactionsQuery)->with(['user', 'details.product'])->latest()->get(),
            'stockEntries' => $stockEntries,
            'purchaseHistory' => $purchaseHistory,
            'expenses' => $expenses,
            'currentShift' => $currentShift,
            'recentShifts' => $recentShifts,
            'stockOpnames' => $stockOpnames,
            'auditLogs' => $auditLogs,
        ]);
    }
}
