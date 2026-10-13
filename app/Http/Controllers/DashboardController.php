<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
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
use App\Services\DashboardMetrics;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(DashboardRequest $request, DashboardMetrics $metrics): View
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

        $filters = $request->validated();

        $selectedPage = $filters['page'] ?? 'dashboard';
        $selectedPeriodMonth = $filters['period_month'] ?? Carbon::today()->format('Y-m');
        $periodStart = Carbon::createFromFormat('Y-m', $selectedPeriodMonth)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();
        $defaultStartDate = Carbon::today()->toDateString();
        $defaultEndDate = Carbon::today()->toDateString();
        $startDate = $selectedPage === 'history' ? $periodStart->toDateString() : ($filters['start_date'] ?? $defaultStartDate);
        $endDate = $selectedPage === 'history' ? $periodEnd->toDateString() : ($filters['end_date'] ?? $defaultEndDate);
        $selectedUserId = $request->user()?->isAdmin() ? ($filters['user_id'] ?? null) : null;
        $selectedPaymentStatus = $filters['payment_status'] ?? null;
        $selectedPaymentType = $filters['payment_type'] ?? null;
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $products = Product::orderBy('nama_barang')->get();
        $activeProducts = Product::where('status', 'active')->orderBy('nama_barang')->get();
        $transactions = Transaction::visibleTo($request->user())
            ->where('status', '!=', 'void')
            ->with(['user', 'details.product'])
            ->latest()
            ->limit(15)
            ->get();
        $latestTransaction = Transaction::visibleTo($request->user())
            ->where('status', '!=', 'void')
            ->with(['user', 'details.product'])
            ->latest()
            ->first();

        $rangeTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'void')
            ->when($selectedUserId, fn ($query) => $query->where('user_id', $selectedUserId))
            ->when($selectedPaymentStatus, fn ($query) => $query->where('payment_status', $selectedPaymentStatus))
            ->when($selectedPaymentType, fn ($query) => $query->where('payment_type', $selectedPaymentType));
        $dashboardTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'void');

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
        $netProfitChart = collect(range(11, 0))->map(function (int $monthsAgo) use ($request, $metrics): array {
            $month = Carbon::today()->startOfMonth()->subMonths($monthsAgo);
            $monthTransactions = Transaction::visibleTo($request->user())
                ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->where('status', '!=', 'void')
                ->where('payment_status', 'paid');
            $grossProfit = $metrics->transactionProfit(clone $monthTransactions);
            $expense = $request->user()->isAdmin()
                ? (int) Expense::whereDate('spent_at', '>=', $month->copy()->startOfMonth()->toDateString())
                    ->whereDate('spent_at', '<=', $month->copy()->endOfMonth()->toDateString())
                    ->sum('amount')
                : 0;

            return [
                'label' => $month->translatedFormat('M y'),
                'count' => $grossProfit - $expense,
            ];
        });
        $financialChart = $metrics->financialChart($request->user(), $periodStart, $periodEnd);

        $cashiers = User::whereIn('role', ['admin', 'cashier'])
            ->withSum(['transactions as today_sales' => fn ($query) => $query->whereDate('created_at', Carbon::today())->where('status', '!=', 'void')->where('payment_status', 'paid')], 'total')
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
            ->where(function ($query) {
                $query->whereNull('transactions.id')
                    ->orWhere('transactions.status', '!=', 'void');
            })
            ->select(
                'products.nama_barang',
                DB::raw('COALESCE(SUM(transaction_details.qty), 0) as qty'),
                DB::raw('COALESCE(SUM(transaction_details.subtotal), 0) as total')
            )
            ->groupBy('products.id', 'products.nama_barang')
            ->orderByDesc('qty')
            ->limit(6)
            ->get();

        $todayTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'void');

        $periodTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'void');

        $stats = [
            'total_stock' => Product::sum('stok'),
            'total_transactions' => (clone $todayTransactionsQuery)->count(),
            'total_transaction_amount' => (clone $todayTransactionsQuery)->sum('total'),
            'total_income' => $metrics->transactionProfit((clone $todayTransactionsQuery)->where('payment_status', 'paid')),
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
                ->whereDate('spent_at', '>=', $startDate)
                ->whereDate('spent_at', '<=', $endDate)
                ->orderByDesc('spent_at')
                ->orderByDesc('id')
                ->get()
            : collect();
        $expenseTotal = (int) $expenses->sum('amount');
        $reportPaidQuery = (clone $reportBaseQuery)->where('payment_status', 'paid');
        $reportPaidTransactions = (clone $reportPaidQuery)->count();
        $reportIncome = (int) (clone $reportPaidQuery)->sum('total');
        $reportCapital = $metrics->transactionCapital(clone $reportPaidQuery);
        $reportGrossProfit = $metrics->transactionProfit(clone $reportPaidQuery);
        $reportNetProfit = $reportGrossProfit - $expenseTotal;
        $reportPurchaseQuery = StockIn::query()->whereBetween('tanggal', [$startDate, $endDate]);
        $reportPurchaseTotal = $request->user()->isAdmin() ? $metrics->purchaseTotal(clone $reportPurchaseQuery) : 0;
        $reportPurchaseQty = $request->user()->isAdmin() ? (int) (clone $reportPurchaseQuery)->sum('qty') : 0;
        $dailyTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'void')
            ->when($selectedUserId, fn ($query) => $query->where('user_id', $selectedUserId))
            ->when($selectedPaymentStatus, fn ($query) => $query->where('payment_status', $selectedPaymentStatus))
            ->when($selectedPaymentType, fn ($query) => $query->where('payment_type', $selectedPaymentType));
        $monthlyTransactionsQuery = Transaction::visibleTo($request->user())
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->where('status', '!=', 'void')
            ->when($selectedUserId, fn ($query) => $query->where('user_id', $selectedUserId))
            ->when($selectedPaymentStatus, fn ($query) => $query->where('payment_status', $selectedPaymentStatus))
            ->when($selectedPaymentType, fn ($query) => $query->where('payment_type', $selectedPaymentType));

        $reportStats = [
            'transactions' => (clone $reportBaseQuery)->count(),
            'paid_transactions' => $reportPaidTransactions,
            'daily_transactions' => (clone $dailyTransactionsQuery)->count(),
            'monthly_transactions' => (clone $monthlyTransactionsQuery)->count(),
            'income' => $reportIncome,
            'capital' => $reportCapital,
            'gross_profit' => $reportGrossProfit,
            'expense_total' => $expenseTotal,
            'net_profit' => $reportNetProfit,
            'purchase_total' => $reportPurchaseTotal,
            'purchase_qty' => $reportPurchaseQty,
            'average_transaction' => $reportPaidTransactions > 0 ? (int) round($reportIncome / $reportPaidTransactions) : 0,
            'items' => DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereIn('transactions.id', (clone $reportBaseQuery)->select('id'))
                ->sum('transaction_details.qty'),
            'cash_income' => (clone $reportBaseQuery)->where('payment_status', 'paid')->where('payment_type', 'cash')->sum('total'),
            'transfer_income' => (clone $reportBaseQuery)->where('payment_status', 'paid')->where('payment_type', 'transfer')->sum('total'),
            'pending_income' => (clone $reportBaseQuery)->where('payment_status', 'pending')->sum('total'),
            'expense_count' => $expenses->count(),
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
            'netProfitChart' => $netProfitChart,
            'financialChart' => $financialChart,
            'cashiers' => $cashiers,
            'monthlyProductSales' => $monthlyProductSales,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedPeriodMonth' => $selectedPeriodMonth,
            'selectedUserId' => $selectedUserId,
            'selectedPaymentStatus' => $selectedPaymentStatus,
            'selectedPaymentType' => $selectedPaymentType,
            'reportTransactions' => $reportTransactions,
            'reportStats' => $reportStats,
            'dashboardTransactions' => (clone $dashboardTransactionsQuery)->with(['user', 'details.product'])->latest()->get(),
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
