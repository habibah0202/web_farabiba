<x-app-layout>
    @php
        $lowStockProducts = $products->filter(fn ($product) => $product->stock <= 10);
        $totalStock = $products->sum('stock');
        $totalSoldUnits = \App\Models\Transaction::query()
            ->get()
            ->sum(fn ($transaction) => collect($transaction->items ?? [])
                ->sum(fn ($item) => (int) ($item['quantity'] ?? 0)));
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Manajemen Stok</p>
            <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Stok Produk</h1>
        </div>

        <div class="mb-6 grid gap-4 md:grid-cols-3">
            <div id="stok-masuk" class="rounded-[1.75rem] border border-[#eadcc9] bg-[#f9f4ef] p-5 shadow-sm">
                <div class="text-[10px] font-semibold uppercase tracking-[0.24em] text-[#7d685f]">Stok Masuk</div>
                <div class="mt-3 text-3xl font-semibold text-[#2d241d]">{{ number_format($totalStock, 0, ',', '.') }}</div>
                <p class="mt-2 text-sm text-[#5d5049]">Jumlah total barang yang tersedia di gudang saat ini.</p>
            </div>

            <div id="stok-keluar" class="rounded-[1.75rem] border border-[#eadcc9] bg-[#f9f4ef] p-5 shadow-sm">
                <div class="text-[10px] font-semibold uppercase tracking-[0.24em] text-[#7d685f]">Stok Keluar</div>
                <div class="mt-3 text-3xl font-semibold text-[#2d241d]">{{ number_format($totalSoldUnits, 0, ',', '.') }}</div>
                <p class="mt-2 text-sm text-[#5d5049]">Jumlah unit yang terjual dari transaksi pelanggan.</p>
            </div>

            <div id="tracking-barang" class="rounded-[1.75rem] border border-[#eadcc9] bg-[#f9f4ef] p-5 shadow-sm">
                <div class="text-[10px] font-semibold uppercase tracking-[0.24em] text-[#7d685f]">Tracking Barang</div>
                <div class="mt-3 text-3xl font-semibold text-[#2d241d]">{{ $products->count() }}</div>
                <p class="mt-2 text-sm text-[#5d5049]">Aktivitas tracking otomatis produk berdasarkan stok dan kategori.</p>
            </div>
        </div>

        <div class="mb-6 grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-[2rem] border border-[#eadcc9] bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-serif text-2xl text-[#2d241d]">Status Stok Produk</h2>
                    <span class="rounded-full bg-[#f5e7dc] px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-[#6a564d]">
                        Auto Track
                    </span>
                </div>

                <div class="overflow-hidden rounded-[1.5rem] border border-[#eadcc9]">
                    <table class="min-w-full divide-y divide-[#f0e3d8] text-left text-sm">
                        <thead class="bg-[#f9f4ef] text-[#6f5d55]">
                            <tr>
                                <th class="px-4 py-3 font-medium">Produk</th>
                                <th class="px-4 py-3 font-medium">Kategori</th>
                                <th class="px-4 py-3 font-medium">Stok</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#f0e3d8] bg-white">
                            @foreach ($products as $product)
                                <tr class="{{ $product->stock <= 10 ? 'bg-red-50' : '' }}">
                                    <td class="px-4 py-3 font-medium text-[#2d241d]">{{ $product->name }}</td>
                                    <td class="px-4 py-3">{{ $product->category?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 {{ $product->stock <= 10 ? 'text-red-600 font-semibold' : '' }}">
                                        <div class="flex flex-col gap-2 xl:flex-row xl:items-center">
                                            <span class="whitespace-nowrap">{{ $product->stock }}</span>
                                            <form method="POST" action="{{ route('stock.adjust', $product) }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                                @csrf
                                                <select name="type" class="min-w-[86px] rounded-xl border border-[#eadcc9] bg-[#f9f4ef] px-2 py-1 text-xs font-medium text-[#2d241d]">
                                                    <option value="in">Tambah</option>
                                                    <option value="out">Kurang</option>
                                                </select>
                                                <input type="number" name="quantity" value="1" min="1" class="w-16 rounded-xl border border-[#eadcc9] bg-white px-2 py-1 text-center text-xs text-[#2d241d]" />
                                                <button type="submit" class="whitespace-nowrap rounded-xl bg-[#2d241d] px-2 py-1 text-[10px] font-medium text-white">Update</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($product->stock <= 10)
                                            <span class="rounded-full border border-red-200 bg-red-100 px-2 py-1 text-xs font-medium text-red-700">⚠ Stok Rendah</span>
                                        @else
                                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">Cukup</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-[2rem] border border-[#eadcc9] bg-[#f9f4ef] p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-serif text-2xl text-[#2d241d]">Tracking Otomatis</h2>
                    <span class="rounded-full bg-white px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#6a564d]">
                        Live
                    </span>
                </div>

                <div class="space-y-3">
                    @foreach ($products->take(4) as $product)
                        <div class="rounded-2xl border border-[#eadcc9] bg-white p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-[#2d241d]">{{ $product->name }}</div>
                                    <div class="mt-1 text-xs text-[#705f57]">{{ $product->category?->name ?? 'Tanpa kategori' }}</div>
                                </div>
                                <span class="rounded-full {{ $product->stock <= 10 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }} px-2 py-1 text-[10px] font-medium">
                                    {{ $product->stock <= 10 ? 'Perlu Restock' : 'Normal' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
