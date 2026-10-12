<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function toggleSuspension(User $user): RedirectResponse
    {
        abort_if($user->email === 'farbib@gmail.com' || $user->id === Auth::id(), 403);

        $user->is_suspended = ! $user->is_suspended;
        $user->save();

        if ($user->is_suspended) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $message = $user->is_suspended
            ? 'Pelanggan berhasil disuspend.'
            : 'Suspend pelanggan berhasil dicabut.';

        return redirect()->route('admin.customers.index')->with('success', $message);
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->email === 'farbib@gmail.com' || $user->id === Auth::id(), 403);

        DB::table('sessions')->where('user_id', $user->id)->delete();
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }
        $user->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus. Riwayat pesanan tetap tersimpan.');
    }
}
