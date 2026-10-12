<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTransactionController extends Controller
{
    public function sales(): View
    {
        $transactions = Transaction::query()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($transaction) => [
                'id' => $transaction->id,
                'invoice' => $transaction->invoice,
                'customer' => $transaction->customer,
                'total' => (float) $transaction->total,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at->toDateTimeString(),
                'items' => $transaction->items ?? [],
            ])
            ->values();

        return view('admin.transactions.sales', compact('transactions'));
    }

    public function salesPdf()
    {
        $transactions = Transaction::query()
            ->orderByDesc('created_at')
            ->get();

        return Pdf::loadView('admin.transactions.sales-pdf', compact('transactions'))
            ->setPaper('a4', 'landscape')
            ->download('transaksi-penjualan.pdf');
    }
    public function receipts(): View
    {
        $transactions = Transaction::query()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($transaction) => [
                'id' => $transaction->id,
                'invoice' => $transaction->invoice,
                'customer' => $transaction->customer,
                'total' => (float) $transaction->total,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at->toDateTimeString(),
                'items' => $transaction->items ?? [],
            ])
            ->values();

        return view('admin.transactions.receipts', compact('transactions'));
    }

    public function receipt(Transaction $transaction): View
    {
        return view('admin.transactions.receipt', compact('transaction'));
    }

    public function tracking(): View
    {
        $transactions = Transaction::query()->orderByDesc('created_at')->get();

        return view('admin.orders.tracking', compact('transactions'));
    }

    public function updateTracking(Request $request, Transaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => ['required', 'in:packed,in_delivery,arrived,completed'],
        ]);

        $transaction->update($validated);

        return redirect()->route('admin.orders.tracking')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
