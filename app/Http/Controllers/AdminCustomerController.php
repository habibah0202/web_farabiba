<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\View\View;

class AdminCustomerController extends Controller
{
    public function index(): View
    {
        $customers = User::query()
            ->where('email', '!=', 'farbib@gmail.com')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function transactions(): View
    {
        $transactions = Transaction::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($transaction) => [
                'invoice' => $transaction->invoice,
                'customer' => $transaction->customer,
                'total' => (float) $transaction->total,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at->toDateTimeString(),
                'items' => $transaction->items ?? [],
            ])
            ->values();

        return view('admin.customers.transactions', compact('transactions'));
    }
}
