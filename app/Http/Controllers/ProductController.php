<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $statusFilter = $request->query('status');

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($statusFilter !== null && $statusFilter !== '', fn ($query) => $query->where('status', $statusFilter))
            ->latest()
            ->get();

        return view('products.index', [
            'products' => $products,
            'search' => $search,
            'categoryId' => $categoryId,
            'statusFilter' => $statusFilter,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('products.create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function stock(): View
    {
        $products = Product::with('category')->orderBy('stock', 'asc')->get();
        $stockLogs = collect(session()->get('stock_logs', []))
            ->sortByDesc('created_at')
            ->take(8)
            ->values();

        return view('stock.index', compact('products', 'stockLogs'));
    }

    public function stockIn(): View
    {
        $products = Product::with('category')->orderBy('name')->get();

        return view('stock.in', compact('products'));
    }

    public function storeStockIn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $product->increment('stock', $validated['quantity']);

        $this->recordStockLog($product, 'masuk', $validated['quantity'], $validated['note'] ?? 'Stok masuk manual');

        return redirect()->route('stock.index')->with('success', 'Stok masuk berhasil dicatat.');
    }

    public function stockOut(): View
    {
        $products = Product::with('category')->orderBy('name')->get();

        return view('stock.out', compact('products'));
    }

    public function adjustStock(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['type'] === 'out' && $product->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Jumlah stok keluar tidak boleh melebihi stok saat ini.'])->withInput();
        }

        if ($validated['type'] === 'in') {
            $product->increment('stock', $validated['quantity']);
            $this->recordStockLog($product, 'masuk', $validated['quantity'], 'Penyesuaian stok dari tabel admin');
        } else {
            $product->decrement('stock', $validated['quantity']);
            $this->recordStockLog($product, 'keluar', $validated['quantity'], 'Penyesuaian stok dari tabel admin');
        }

        return redirect()->route('stock.index')->with('success', 'Stok berhasil disesuaikan.');
    }

    public function storeStockOut(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Jumlah stok keluar tidak boleh melebihi stok saat ini.'])->withInput();
        }

        $product->decrement('stock', $validated['quantity']);

        $this->recordStockLog($product, 'keluar', $validated['quantity'], $validated['note'] ?? 'Stok keluar manual');

        return redirect()->route('stock.index')->with('success', 'Stok keluar berhasil dicatat.');
    }

    public function stockTracking(): View
    {
        $stockLogs = collect(session()->get('stock_logs', []))
            ->sortByDesc('created_at')
            ->values();

        return view('stock.tracking', compact('stockLogs'));
    }

    private function recordStockLog(Product $product, string $type, int $quantity, string $note): void
    {
        $logs = session()->get('stock_logs', []);

        $logs[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'category_name' => $product->category?->name ?? '-',
            'type' => $type,
            'quantity' => $quantity,
            'note' => $note,
            'created_at' => now()->toDateTimeString(),
        ];

        session()->put('stock_logs', $logs);
    }

    public function transactions(): View
    {
        $transactions = [
            ['invoice' => 'INV-2026-001', 'customer' => 'Ayu', 'total' => 'Rp 245.000', 'status' => 'Lunas'],
            ['invoice' => 'INV-2026-002', 'customer' => 'Nia', 'total' => 'Rp 320.000', 'status' => 'Lunas'],
            ['invoice' => 'INV-2026-003', 'customer' => 'Rina', 'total' => 'Rp 578.000', 'status' => 'Proses'],
        ];

        return view('transactions.index', compact('transactions'));
    }
}
