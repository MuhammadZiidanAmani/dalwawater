<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardMetrics
{
    public function transactionProfit(Builder $transactionsQuery): int
    {
        return (int) DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereIn('transaction_details.transaction_id', (clone $transactionsQuery)->select('transactions.id'))
            ->sum(DB::raw('transaction_details.subtotal - (products.harga_modal * transaction_details.qty)'));
    }

    public function transactionCapital(Builder $transactionsQuery): int
    {
        return (int) DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereIn('transaction_details.transaction_id', (clone $transactionsQuery)->select('transactions.id'))
            ->sum(DB::raw('products.harga_modal * transaction_details.qty'));
    }

    public function purchaseTotal(Builder $stockInQuery): int
    {
        return (int) $stockInQuery
            ->selectRaw('COALESCE(SUM(CASE WHEN subtotal > 0 THEN subtotal ELSE harga_beli * qty END), 0) as total_purchase')
            ->value('total_purchase');
    }

    public function financialChart(User $user, Carbon $periodStart, Carbon $periodEnd): Collection
    {
        $baseTransactions = Transaction::visibleTo($user)
            ->whereBetween('created_at', [$periodStart->copy()->startOfDay(), $periodEnd->copy()->endOfDay()])
            ->where('status', '!=', 'void')
            ->where('payment_status', 'paid');

        $incomeByDate = (clone $baseTransactions)
            ->selectRaw('DATE(created_at) as date_key, COALESCE(SUM(total), 0) as income')
            ->groupBy('date_key')
            ->pluck('income', 'date_key')
            ->map(fn ($value): int => (int) $value);

        $detailByDate = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereIn('transactions.id', (clone $baseTransactions)->select('transactions.id'))
            ->selectRaw('DATE(transactions.created_at) as date_key')
            ->selectRaw('COALESCE(SUM(products.harga_modal * transaction_details.qty), 0) as capital')
            ->selectRaw('COALESCE(SUM(transaction_details.subtotal - (products.harga_modal * transaction_details.qty)), 0) as gross_profit')
            ->groupBy('date_key')
            ->get()
            ->keyBy('date_key');

        $expenseByDate = $user->isAdmin()
            ? Expense::query()
                ->whereDate('spent_at', '>=', $periodStart->toDateString())
                ->whereDate('spent_at', '<=', $periodEnd->toDateString())
                ->selectRaw('spent_at as date_key, COALESCE(SUM(amount), 0) as expense')
                ->groupBy('date_key')
                ->pluck('expense', 'date_key')
                ->map(fn ($value): int => (int) $value)
            : collect();

        return collect(range(0, (int) $periodStart->diffInDays($periodEnd)))
            ->map(function (int $daysAfter) use ($periodStart, $incomeByDate, $detailByDate, $expenseByDate): array {
                $day = $periodStart->copy()->addDays($daysAfter);
                $dateKey = $day->toDateString();
                $detail = $detailByDate->get($dateKey);
                $income = (int) ($incomeByDate[$dateKey] ?? 0);
                $expense = (int) ($expenseByDate[$dateKey] ?? 0);
                $capital = (int) ($detail?->capital ?? 0);
                $grossProfit = (int) ($detail?->gross_profit ?? 0);

                return [
                    'label' => $day->translatedFormat('d M'),
                    'income' => $income,
                    'expense' => $expense,
                    'capital' => $capital,
                    'gross_profit' => $grossProfit,
                    'net_profit' => $grossProfit - $expense,
                ];
            });
    }
}
