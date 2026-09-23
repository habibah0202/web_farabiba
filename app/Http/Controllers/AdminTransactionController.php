<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
}
