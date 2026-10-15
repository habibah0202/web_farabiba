<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();
        $hasPurchased = Transaction::where('user_id', $user->id)
            ->get(['items'])
            ->contains(fn (Transaction $transaction) => collect($transaction->items ?? [])
                ->contains(fn (array $item) => (int) ($item['id'] ?? 0) === $product->id));

        abort_unless($hasPurchased, 403, 'Ulasan hanya dapat diberikan setelah membeli produk ini.');

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        ProductReview::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $product->id],
            $validated,
        );

        return redirect()
            ->route('customer.products.show', $product)
            ->with('review-status', 'Ulasan Anda berhasil disimpan. Terima kasih sudah berbagi pengalaman.');
    }
}
