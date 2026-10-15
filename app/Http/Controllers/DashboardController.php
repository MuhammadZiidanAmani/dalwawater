<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Models\ApprovalRequest;
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
        $selectedReportScope = $filters['report_scope'] ?? 'month';
        $selectedPeriodMonth = $filters['period_month'] ?? Carbon::today()->format('Y-m');
        $periodStart = Carbon::createFromFormat('Y-m', $selectedPeriodMonth)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();
        $reportPeriodStart = match ($selectedReportScope) {
            'year' => $periodStart->copy()->startOfYear(),
            'all' => null,
            default => $periodStart,
        };
        $reportPeriodEnd = match ($selectedReportScope) {
            'year' => $periodStart->copy()->endOfYear(),
            'all' => null,
            default => $periodEnd,
        };
        $defaultStartDate = Carbon::today()->toDateString();
        $defaultEndDate = Carbon::today()->toDateString();
        $startDate = $selectedPage === 'history' ? $reportPeriodStart?->toDateString() : ($filters['start_date'] ?? $defaultStartDate);
        $endDate = $selectedPage === 'history' ? $reportPeriodEnd?->toDateString() : ($filters['end_date'] ?? $defaultEndDate);
        $selectedUserId = $request->user()?->isAdmin() ? ($filters['user_id'] ?? null) : null;
        $selectedPaymentStatus = $filters['payment_status'] ?? null;
        $selectedPaymentType = $filters['payment_type'] ?? null;
        $hasDateRange = $startDate && $endDate;
        $start = $hasDateRange ? Carbon::parse($startDate)->startOfDay() : Carbon::today()->startOfDay();
        $end = $hasDateRange ? Carbon::parse($endDate)->endOfDay() : Carbon::today()->endOfDay();

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
            ->where('status', '!=', 'void')
            ->when($hasDateRange, fn ($query) => $query->whereBetween('created_at', [$start, $end]))
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
            $expense = (int) Expense::whereDate('spent_at', '>=', $month->copy()->startOfMonth()->toDateString())
                ->whereDate('spent_at', '<=', $month->copy()->endOfMonth()->toDateString())
                ->sum('amount');

            return [
                'label' => $month->translatedFormat('M y'),
                'count' => $grossProfit - $expense,
            ];
        });
        $financialChartStart = $selectedReportScope === 'all' ? $this->allReportPeriodStart($request->user()) : ($reportPeriodStart ?? $periodStart);
        $financialChartEnd = $selectedReportScope === 'all' ? Carbon::today()->endOfMonth() : ($reportPeriodEnd ?? $periodEnd);
        $financialChart = $metrics->financialChart(
            $request->user(),
            $financialChartStart,
            $financialChartEnd,
            $selectedReportScope === 'month' ? 'day' : 'month',
        );

        $cashiers = User::whereIn('role', ['admin', 'cashier'])
            ->withSum(['transactions as today_sales' => fn ($query) => $query->whereDate('created_at', Carbon::today())->where('status', '!=', 'void')->where('payment_status', 'paid')], 'total')
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        $monthlyProductSales = Product::query()
            ->leftJoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')
            ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->when($hasDateRange, function ($query) use ($start, $end) {
                $query->where(function ($query) use ($start, $end) {
                    $query->whereNull('transactions.id')
                        ->orWhereBetween('transactions.created_at', [$start, $end]);
                });
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
            ->where('status', '!=', 'void')
            ->when($hasDateRange, fn ($query) => $query->whereBetween('created_at', [$start, $end]));

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

        if ($hasDateRange) {
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
        $stockEntries = StockIn::with('product')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();
        $purchaseHistory = StockIn::with('product')
            ->when($hasDateRange, fn ($query) => $query->whereBetween('tanggal', [$startDate, $endDate]))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();
        $expenses = Expense::with('user')
            ->when($hasDateRange, fn ($query) => $query->whereDate('spent_at', '>=', $startDate)->whereDate('spent_at', '<=', $endDate))
            ->orderByDesc('spent_at')
            ->orderByDesc('id')
            ->get();
        $expenseTotal = (int) $expenses->sum('amount');
        $reportPaidQuery = (clone $reportBaseQuery)->where('payment_status', 'paid');
        $reportPaidTransactions = (clone $reportPaidQuery)->count();
        $reportIncome = (int) (clone $reportPaidQuery)->sum('total');
        $reportCapital = $metrics->transactionCapital(clone $reportPaidQuery);
        $reportGrossProfit = $metrics->transactionProfit(clone $reportPaidQuery);
        $reportNetProfit = $reportGrossProfit - $expenseTotal;
        $reportPurchaseQuery = StockIn::query()
            ->when($hasDateRange, fn ($query) => $query->whereBetween('tanggal', [$startDate, $endDate]));
        $reportPurchaseTotal = $metrics->purchaseTotal(clone $reportPurchaseQuery);
        $reportPurchaseQty = (int) (clone $reportPurchaseQuery)->sum('qty');
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
        $suppliers = Supplier::orderBy('nama')->get();
        $currentShift = $request->user()->cashierShifts()
            ->open()
            ->latest('opened_at')
            ->first();
        $recentShifts = $request->user()->isAdmin()
            ? CashierShift::with('user')->latest('opened_at')->limit(10)->get()
            : $request->user()->cashierShifts()->latest('opened_at')->limit(5)->get();
        $stockOpnames = StockOpname::with(['user', 'adjustments.product'])->latest()->limit(10)->get();
        $auditLogs = $request->user()->isAdmin()
            ? AuditLog::with('user')->latest()->limit(20)->get()
            : collect();
        $approvalRequests = $request->user()->isAdmin()
            ? ApprovalRequest::with(['requester', 'reviewer', 'subject'])->latest()->limit(100)->get()
            : $request->user()->approvalRequests()->with(['reviewer', 'subject'])->latest()->limit(50)->get();

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
            'selectedReportScope' => $selectedReportScope,
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
            'approvalRequests' => $approvalRequests,
        ]);
    }

    private function allReportPeriodStart(User $user): Carbon
    {
        $dates = collect([
            Transaction::visibleTo($user)
                ->where('status', '!=', 'void')
                ->min('created_at'),
            Expense::min('spent_at'),
            StockIn::min('tanggal'),
        ])->filter();

        if ($dates->isEmpty()) {
            return Carbon::today()->startOfMonth();
        }

        return $dates
            ->map(fn ($date) => Carbon::parse($date)->startOfMonth())
            ->sortBy(fn (Carbon $date) => $date->timestamp)
            ->first();
    }
}
