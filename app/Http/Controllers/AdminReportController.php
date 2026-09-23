<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $period = $request->query('period', 'harian');

        $transactions = Transaction::query()
            ->select('invoice', 'customer', 'total', 'status', 'created_at')
            ->when($startDate, function ($query, $startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($transaction) {
                return [
                    'invoice' => $transaction->invoice ?? '-',
                    'customer' => $transaction->customer ?? 'Pelanggan',
                    'total' => (float) ($transaction->total ?? 0),
                    'status' => $transaction->status ?? 'Lunas',
                    'created_at' => $transaction->created_at ? $transaction->created_at->toDateTimeString() : now()->toDateTimeString(),
                ];
            })
            ->values();

        $dailyRevenue = $transactions->filter(fn ($transaction) => \Carbon\Carbon::parse($transaction['created_at'])->isToday())->sum('total');
        $weeklyRevenue = $transactions->filter(fn ($transaction) => \Carbon\Carbon::parse($transaction['created_at'])->between(now()->startOfWeek(), now()->endOfWeek()))->sum('total');
        $monthlyRevenue = $transactions->filter(fn ($transaction) => \Carbon\Carbon::parse($transaction['created_at'])->isCurrentMonth())->sum('total');

        return view('admin.reports.index', compact(
            'transactions',
            'startDate',
            'endDate',
            'period',
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue'
        ));
    }
}
