<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->where('status', 'active')->get();
        $categories = Category::all();

        return view('customer.index', compact('products', 'categories'));
    }

    public function categories(): View
    {
        $categories = Category::withCount('products')->get();

        return view('customer.categories', compact('categories'));
    }

    public function search(Request $request): View
    {
        $query = $request->query('q');
        $categoryId = $request->query('category_id');
        $categories = Category::all();

        $products = Product::with('category')
            ->where('status', 'active')
            ->when($query, fn ($q) => $q->where('name', 'like', "%{$query}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->get();

        return view('customer.search', compact('products', 'query', 'categories', 'categoryId'));
    }

    public function filter(Request $request): View
    {
        $categoryId = $request->query('category_id');
        $products = Product::with('category')
            ->where('status', 'active')
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->get();

        $categories = Category::all();

        return view('customer.filter', compact('products', 'categories', 'categoryId'));
    }

    public function show(Product $product): View
    {
        return view('customer.show', compact('product'));
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $quantity = max(1, (int) $request->input('quantity', 1));

        $cartItem = Cart::firstOrNew([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $quantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function cart(): View
    {
        $cart = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get()
            ->map(fn ($item) => [
                'id' => $item->product_id,
                'name' => $item->product->name,
                'price' => (float) $item->product->price,
                'quantity' => $item->quantity,
            ])
            ->keyBy('id')
            ->all();

        $total = collect($cart)->sum(fn ($item) => (float) $item['price'] * (int) $item['quantity']);

        return view('customer.cart', compact('cart', 'total'));
    }

    public function updateCart(Request $request, Product $product): RedirectResponse
    {
        $quantity = max(1, (int) $request->input('quantity', 1));

        Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->update(['quantity' => $quantity]);

        return redirect()->route('customer.cart');
    }

    public function removeFromCart(Product $product): RedirectResponse
    {
        Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return redirect()->route('customer.cart')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function checkout(): View
    {
        $cart = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get()
            ->map(fn ($item) => [
                'id' => $item->product_id,
                'name' => $item->product->name,
                'price' => (float) $item->product->price,
                'quantity' => $item->quantity,
            ])
            ->keyBy('id')
            ->all();

        $total = collect($cart)->sum(fn ($item) => (float) $item['price'] * (int) $item['quantity']);

        return view('customer.checkout', compact('cart', 'total'));
    }

    public function storeCheckout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang masih kosong.');
        }

        $items = $cartItems->mapWithKeys(fn ($item) => [
            $item->product_id => [
                'id' => $item->product_id,
                'name' => $item->product->name,
                'price' => (float) $item->product->price,
                'quantity' => $item->quantity,
            ],
        ])->all();

        $total = collect($items)->sum(fn ($item) => (float) $item['price'] * (int) $item['quantity']);

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            if (! $product) {
                continue;
            }

            if ($product->stock < $cartItem->quantity) {
                return redirect()->route('customer.cart')->with('error', 'Stok produk '. $product->name .' tidak mencukupi.');
            }

            $product->decrement('stock', $cartItem->quantity);
        }

        Transaction::create([
            'user_id' => $user->id,
            'customer' => $user->name,
            'invoice' => 'INV-'.now()->format('YmdHis'),
            'total' => $total,
            'status' => 'Lunas',
            'items' => $items,
        ]);

        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('customer.transactions')->with('success', 'Checkout berhasil.');
    }

    public function transactions(): View
    {
        $transactions = Transaction::where('user_id', Auth::id())
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
            ->all();

        return view('customer.transactions', compact('transactions'));
    }

    public function transactionDetail(int $id): View
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        return view('customer.transaction-detail', ['transaction' => [
            'id' => $transaction->id,
            'invoice' => $transaction->invoice,
            'status' => $transaction->status,
            'total' => (float) $transaction->total,
            'items' => $transaction->items ?? [],
        ]]);
    }

    public function profile(): View
    {
        return view('customer.profile');
    }
}
