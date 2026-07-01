<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Support\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view reports');
    }

    /**
     * Cash-basis day book: money in vs money out per day, with a running balance.
     */
    public function dayBook(Request $request)
    {
        $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();

        // --- In-range daily totals ---
        $incomeByDay = $this->merge([
            $this->dailySum(Sale::query(), 'sale_date', 'paid', $from, $to),
            $this->dailySum(Payment::where('payable_type', Sale::class), 'payment_date', 'amount', $from, $to),
        ]);

        $expenseByDay = $this->merge([
            $this->dailySum(Expense::query(), 'expense_date', 'amount', $from, $to),
            $this->dailySum(Purchase::query(), 'purchase_date', 'paid', $from, $to),
            $this->dailySum(Payment::where('payable_type', Purchase::class), 'payment_date', 'amount', $from, $to),
            $this->dailySum(SaleReturn::query(), 'return_date', 'total', $from, $to),
        ]);

        // --- Opening balance (everything before the range) ---
        $opening = $this->totalBefore(Sale::query(), 'sale_date', 'paid', $from)
            + $this->totalBefore(Payment::where('payable_type', Sale::class), 'payment_date', 'amount', $from)
            - $this->totalBefore(Expense::query(), 'expense_date', 'amount', $from)
            - $this->totalBefore(Purchase::query(), 'purchase_date', 'paid', $from)
            - $this->totalBefore(Payment::where('payable_type', Purchase::class), 'payment_date', 'amount', $from)
            - $this->totalBefore(SaleReturn::query(), 'return_date', 'total', $from);

        // --- Build per-day rows with a running balance ---
        $dates = collect(array_keys($incomeByDay + $expenseByDay))->unique()->sort()->values();

        $balance = $opening;
        $rows = [];
        foreach ($dates as $d) {
            $in = $incomeByDay[$d] ?? 0;
            $out = $expenseByDay[$d] ?? 0;
            $balance += $in - $out;
            $rows[] = [
                'date' => $d,
                'income' => $in,
                'expense' => $out,
                'net' => $in - $out,
                'balance' => $balance,
            ];
        }

        $totals = [
            'income' => array_sum($incomeByDay),
            'expense' => array_sum($expenseByDay),
            'net' => array_sum($incomeByDay) - array_sum($expenseByDay),
            'opening' => $opening,
            'closing' => $balance,
        ];

        // Source-wise breakdown of what makes up income and expense.
        $breakdown = [
            'income' => [
                'Cash sales' => (float) Sale::whereBetween('sale_date', [$from, $to])->sum('paid'),
                'Due collections' => (float) Payment::where('payable_type', Sale::class)->whereBetween('payment_date', [$from, $to])->sum('amount'),
            ],
            'expense' => [
                'Expenses' => (float) Expense::whereBetween('expense_date', [$from, $to])->sum('amount'),
                'Purchase payments' => (float) Purchase::whereBetween('purchase_date', [$from, $to])->sum('paid')
                    + (float) Payment::where('payable_type', Purchase::class)->whereBetween('payment_date', [$from, $to])->sum('amount'),
                'Sale returns (refund)' => (float) SaleReturn::whereBetween('return_date', [$from, $to])->sum('total'),
            ],
        ];

        // PDF export
        if ($request->boolean('pdf')) {
            $pdfRows = [['Opening Balance', '', '', '', number_format($totals['opening'], 2)]];
            foreach ($rows as $r) {
                $pdfRows[] = [
                    Carbon::parse($r['date'])->format('d-m-Y'),
                    number_format($r['income'], 2),
                    number_format($r['expense'], 2),
                    number_format($r['net'], 2),
                    number_format($r['balance'], 2),
                ];
            }

            return Pdf::render('pdf.report', [
                'title' => 'Day Book',
                'period' => $from->format('d M Y') . ' — ' . $to->format('d M Y'),
                'head' => ['Date', 'Income', 'Expense', 'Net', 'Balance'],
                'align' => ['left', 'right', 'right', 'right', 'right'],
                'rows' => $pdfRows,
                'foot' => ['Total', number_format($totals['income'], 2), number_format($totals['expense'], 2),
                    number_format($totals['net'], 2), number_format($totals['closing'], 2)],
            ], 'Day-Book.pdf', $request->boolean('download'));
        }

        return view('admin.accounting.day-book', [
            'rows' => $rows,
            'totals' => $totals,
            'breakdown' => $breakdown,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }

    /** Daily SUM of a column, keyed by Y-m-d, within a date range. */
    private function dailySum(Builder $query, string $dateCol, string $amountCol, $from, $to): array
    {
        return $query->whereBetween($dateCol, [$from, $to])
            ->selectRaw("DATE($dateCol) as d, SUM($amountCol) as v")
            ->groupBy('d')
            ->pluck('v', 'd')
            ->map(fn ($v) => (float) $v)
            ->toArray();
    }

    /** Total of a column for everything strictly before a date. */
    private function totalBefore(Builder $query, string $dateCol, string $amountCol, $before): float
    {
        return (float) $query->where($dateCol, '<', $before)->sum($amountCol);
    }

    /** Merge several [date => amount] maps by summing matching dates. */
    private function merge(array $maps): array
    {
        $out = [];
        foreach ($maps as $map) {
            foreach ($map as $d => $v) {
                $out[$d] = ($out[$d] ?? 0) + $v;
            }
        }

        return $out;
    }
}
