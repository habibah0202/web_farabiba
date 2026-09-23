<x-app-layout>
    @php
        $transactions = \App\Models\Transaction::query()
            ->select('total', 'created_at')
            ->get()
            ->map(function ($transaction) {
                return [
                    'total' => (float) $transaction->total,
                    'created_at' => $transaction->created_at->toDateTimeString(),
                ];
            })
            ->values();

        $todayTransactions = $transactions->filter(fn ($transaction) => \Carbon\Carbon::parse($transaction['created_at'])->isToday());
        $yesterdayTransactions = $transactions->filter(fn ($transaction) => \Carbon\Carbon::parse($transaction['created_at'])->isYesterday());

        $todayRevenue = (float) $todayTransactions->sum('total');
        $yesterdayRevenue = (float) $yesterdayTransactions->sum('total');
        $revenueGrowth = $yesterdayRevenue > 0
            ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100
            : ($todayRevenue > 0 ? 100 : 0);

        $totalRevenue = (float) $transactions->sum('total');
        $totalTransactions = \App\Models\Transaction::query()->count();
        $totalOrderedUnits = \App\Models\Transaction::query()
            ->get()
            ->sum(fn ($transaction) => collect($transaction->items ?? [])
                ->sum(fn ($item) => (int) ($item['quantity'] ?? 0)));

        $salesByPeriod = [
            'Harian' => $todayRevenue,
            'Mingguan' => $transactions
                ->filter(fn ($transaction) => isset($transaction['created_at']) && \Carbon\Carbon::parse($transaction['created_at'])->between(now()->startOfWeek(), now()->endOfWeek()))
                ->sum('total'),
            'Bulanan' => $transactions
                ->filter(fn ($transaction) => isset($transaction['created_at']) && \Carbon\Carbon::parse($transaction['created_at'])->isCurrentMonth())
                ->sum('total'),
        ];

        $lowStockProducts = \App\Models\Product::with('category')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->get();

        $recentCustomers = \App\Models\User::where('email', '!=', 'farbib@gmail.com')
            ->whereDate('created_at', today())
            ->count();

        $activities = [];

        if ($todayTransactions->isNotEmpty()) {
            $latest = $todayTransactions->sortByDesc('created_at')->first();
            $latestTime = \Carbon\Carbon::parse($latest['created_at'])->format('H.i');
            $activityText = $todayTransactions->count() === 1 ? '1 transaksi baru' : $todayTransactions->count().' transaksi baru';

            $activities[] = [
                'title' => $activityText,
                'detail' => $latestTime.' — Penjualan skincare bertambah '.number_format(abs($revenueGrowth), 1, '.', '.').'%',
            ];
        }

        if ($lowStockProducts->isNotEmpty()) {
            $lowStockNames = $lowStockProducts->take(2)->pluck('name')->implode(', ');
            $activities[] = [
                'title' => $lowStockProducts->count().' produk menipis stok',
                'detail' => 'Perlu restock produk '.($lowStockNames ?: 'terpilih').' dengan stok minimal.',
            ];
        }

        if ($recentCustomers > 0) {
            $activities[] = [
                'title' => $recentCustomers.' pelanggan baru',
                'detail' => now()->format('H.i').' — Data pelanggan berhasil diupdate',
            ];
        }

        $stats = [
            ['label' => 'Produk', 'value' => number_format(\App\Models\Product::count(), 0, ',', '.'), 'detail' => 'Total produk aktif'],
            ['label' => 'Stok', 'value' => number_format(\App\Models\Product::sum('stock') ?? 0, 0, ',', '.'), 'detail' => 'Barang siap jual'],
            ['label' => 'Pelanggan', 'value' => number_format(\App\Models\User::where('email', '!=', 'farbib@gmail.com')->count(), 0, ',', '.'), 'detail' => 'Pembeli terdaftar'],
            ['label' => 'Transaksi', 'value' => number_format($totalTransactions, 0, ',', '.'), 'detail' => 'Riwayat checkout'],
        ];

        $modules = [
            ['title' => 'Login & Hak Akses Admin', 'description' => 'Autentikasi, role, dan pembatasan akses admin.', 'badge' => 'Security'],
            ['title' => 'Dashboard', 'description' => 'Ringkasan produk, stok, pelanggan, dan transaksi.', 'badge' => 'Overview'],
            ['title' => 'Manajemen Produk Skincare', 'description' => 'Tambah, edit, hapus, dan detail produk skincare.', 'badge' => 'Product'],
            ['title' => 'Manajemen Kategori Produk', 'description' => 'Kategori seperti cleanser, toner, serum, moisturizer.', 'badge' => 'Category'],
            ['title' => 'Manajemen Stok', 'description' => 'Stok masuk, stok keluar, dan tracking barang otomatis.', 'badge' => 'Inventory'],
            ['title' => 'Data Pelanggan', 'description' => 'Kelola profil pelanggan dan riwayat transaksi.', 'badge' => 'Customers'],
            ['title' => 'Transaksi Penjualan', 'description' => 'Catat penjualan, pembayaran, dan detail pembelian.', 'badge' => 'Sales'],
            ['title' => 'Detail Transaksi & Struk', 'description' => 'Rincian produk, total pembayaran, dan struk cetak.', 'badge' => 'Receipt'],
            ['title' => 'Perhitungan Total Otomatis', 'description' => 'Subtotal, diskon, pajak, dan total otomatis.', 'badge' => 'Math'],
            ['title' => 'Laporan Penjualan', 'description' => 'Rekap harian, mingguan, dan bulanan.', 'badge' => 'Report'],
            ['title' => 'Pencarian & Filter', 'description' => 'Cari produk dan transaksi berdasarkan data tertentu.', 'badge' => 'Search'],
            ['title' => 'Laporan Per Periode', 'description' => 'Filter tanggal mulai dan tanggal akhir untuk laporan.', 'badge' => 'Filter'],
        ];

        $products = \App\Models\Product::with('category')
            ->where('status', 'active')
            ->orderByDesc('stock')
            ->take(4)
            ->get()
            ->map(fn ($product) => [
                'name' => $product->name,
                'category' => $product->category?->name ?? '-',
                'stock' => $product->stock,
                'price' => 'Rp '.number_format($product->price, 0, ',', '.'),
            ])
            ->all();
    @endphp

    <div class="min-h-screen bg-[#f7f1ea] text-[#2f241d]">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <main class="mt-8 space-y-8">
                <section id="home" class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                    <div class="rounded-[2rem] border border-[#e9dccd] bg-[#f3ebdf] p-8 shadow-[0_15px_35px_rgba(109,82,62,0.08)] sm:p-10">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.36em] text-[#7d685f]">Free shipping on orders over $70</p>
                        <h1 class="mt-6 max-w-md font-serif text-4xl leading-none text-[#2d241d] sm:text-5xl">
                            Dashboard Admin
                        </h1>
                        <p class="mt-5 max-w-lg text-base leading-7 text-[#574a41]">
                            Pantau performa bisnis skincare Anda secara real time, mulai dari produk, stok, pelanggan, hingga laporan penjualan harian.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <button class="rounded-full bg-[#2d241d] px-5 py-3 text-sm font-medium text-[#faf5f0] shadow-sm transition hover:bg-[#1f1a17]">
                                Laporan Hari Ini
                            </button>
                            <button class="rounded-full border border-[#d7c5b2] bg-white/60 px-5 py-3 text-sm font-medium text-[#2d241d] transition hover:bg-white">
                                Kelola Produk
                            </button>
                        </div>

                    </div>

                    <div class="rounded-[2rem] border border-[#e9dccd] bg-[#f9f4ef] p-6 shadow-[0_15px_30px_rgba(109,82,62,0.05)]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-[#7d685f]">Overview</p>
                                <h2 class="mt-2 text-2xl font-serif text-[#2d241d]">Penjualan</h2>
                            </div>
                            <span class="rounded-full bg-[#efe1d1] px-3 py-1 text-xs font-medium text-[#5f5148]">
                                {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1, '.', '.') }}%
                            </span>
                        </div>

                        <div class="mt-8 flex items-end gap-3">
                            <div class="flex flex-1 items-end justify-around gap-2">
                                @php
                                    $barValue = max($todayRevenue, $yesterdayRevenue, 1);
                                    $barHeight = [38, 52, 70, 88, 100];
                                @endphp
                                <div class="w-full rounded-t-2xl bg-[#d7b69d]" style="height: {{ min(100, ($todayRevenue / $barValue) * 100) }}%"></div>
                                <div class="w-full rounded-t-2xl bg-[#c9a58d]" style="height: {{ min(100, ($yesterdayRevenue / $barValue) * 100) }}%"></div>
                                <div class="w-full rounded-t-2xl bg-[#b98f73]" style="height: {{ min(100, (($todayRevenue + $yesterdayRevenue) / ($barValue * 2)) * 100) }}%"></div>
                                <div class="w-full rounded-t-2xl bg-[#8a6857]" style="height: {{ min(100, (($todayRevenue + $yesterdayRevenue) / ($barValue * 1.4)) * 100) }}%"></div>
                                <div class="w-full rounded-t-2xl bg-[#6d4b39]" style="height: {{ min(100, (($todayRevenue + $yesterdayRevenue) / ($barValue * 1.1)) * 100) }}%"></div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-[#f2e8df] p-4">
                                <div class="text-[10px] uppercase tracking-[0.25em] text-[#7d685f]">Revenue</div>
                                <div class="mt-2 text-2xl font-semibold text-[#2d241d]">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-2xl bg-[#f2e8df] p-4">
                                <div class="text-[10px] uppercase tracking-[0.25em] text-[#7d685f]">Orders</div>
                                <div class="mt-2 text-2xl font-semibold text-[#2d241d]">{{ number_format((int) $totalOrderedUnits, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($stats as $stat)
                        <div class="rounded-[1.6rem] border border-[#eadcc9] bg-white/80 p-5 shadow-sm">
                            <div class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7d685f]">{{ $stat['label'] }}</div>
                            <div class="mt-4 text-3xl font-semibold text-[#2d241d]">{{ $stat['value'] }}</div>
                            <div class="mt-2 text-sm text-[#6b5d56]">{{ $stat['detail'] }}</div>
                        </div>
                    @endforeach
                </section>

                <section id="stok" class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
                    <div id="pelanggan" class="rounded-[2rem] border border-[#eadcc9] bg-white/80 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Produk</p>
                                <h2 class="mt-2 font-serif text-3xl text-[#2d241d]">Produk Populer</h2>
                            </div>
                            <button class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-4 py-2 text-sm font-medium text-[#2d241d]">
                                Tambah Produk
                            </button>
                        </div>

                        <div class="mt-6 overflow-hidden rounded-2xl border border-[#eadcc9]">
                            <table class="min-w-full divide-y divide-[#f0e3d8] text-left text-sm text-[#3f3129]">
                                <thead class="bg-[#f9f4ef] text-[#6f5d55]">
                                    <tr>
                                        <th class="px-4 py-3 font-medium">Nama Produk</th>
                                        <th class="px-4 py-3 font-medium">Kategori</th>
                                        <th class="px-4 py-3 font-medium">Stok</th>
                                        <th class="px-4 py-3 font-medium">Harga</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#f0e3d8] bg-white">
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="px-4 py-3 font-medium text-[#2d241d]">{{ $product['name'] }}</td>
                                            <td class="px-4 py-3">{{ $product['category'] }}</td>
                                            <td class="px-4 py-3">{{ $product['stock'] }}</td>
                                            <td class="px-4 py-3">{{ $product['price'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="transaksi" class="rounded-[2rem] border border-[#eadcc9] bg-[#f9f4ef] p-6 shadow-sm">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Aktivitas</p>
                        <h2 class="mt-2 font-serif text-3xl text-[#2d241d]">Hari Ini</h2>

                        <div class="mt-6 space-y-4">
                            @forelse ($activities as $activity)
                                <div class="rounded-2xl border border-[#eadcc9] bg-white p-4">
                                    <div class="text-sm font-medium text-[#2d241d]">{{ $activity['title'] }}</div>
                                    <div class="mt-1 text-xs text-[#705f57]">{{ $activity['detail'] }}</div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-[#eadcc9] bg-white p-4">
                                    <div class="text-sm font-medium text-[#2d241d]">Belum ada aktivitas hari ini</div>
                                    <div class="mt-1 text-xs text-[#705f57]">Transaksi dan pelanggan baru akan muncul di sini.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section id="laporan" class="rounded-[2rem] border border-[#eadcc9] bg-white/80 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Laporan</p>
                            <h2 class="mt-2 font-serif text-3xl text-[#2d241d]">Penjualan Berdasarkan Periode</h2>
                        </div>
                    </div>

                    @php
                        $periodMaxRevenue = max($salesByPeriod['Harian'] ?? 0, $salesByPeriod['Mingguan'] ?? 0, $salesByPeriod['Bulanan'] ?? 0, 1);
                    @endphp

                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-[1.5rem] bg-[#f8f2ec] p-5">
                            <div class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7d685f]">Harian</div>
                            <div class="mt-3 text-2xl font-semibold text-[#2d241d]">Rp {{ number_format($salesByPeriod['Harian'] ?? 0, 0, ',', '.') }}</div>
                            <div class="mt-4 h-2 overflow-hidden rounded-full bg-[#eadcc9]">
                                <div class="h-full rounded-full bg-[#8b6855]" style="width: {{ (($salesByPeriod['Harian'] ?? 0) / $periodMaxRevenue) * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="rounded-[1.5rem] bg-[#f8f2ec] p-5">
                            <div class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7d685f]">Mingguan</div>
                            <div class="mt-3 text-2xl font-semibold text-[#2d241d]">Rp {{ number_format($salesByPeriod['Mingguan'] ?? 0, 0, ',', '.') }}</div>
                            <div class="mt-4 h-2 overflow-hidden rounded-full bg-[#eadcc9]">
                                <div class="h-full rounded-full bg-[#b98f73]" style="width: {{ (($salesByPeriod['Mingguan'] ?? 0) / $periodMaxRevenue) * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="rounded-[1.5rem] bg-[#f8f2ec] p-5">
                            <div class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7d685f]">Bulanan</div>
                            <div class="mt-3 text-2xl font-semibold text-[#2d241d]">Rp {{ number_format($salesByPeriod['Bulanan'] ?? 0, 0, ',', '.') }}</div>
                            <div class="mt-4 h-2 overflow-hidden rounded-full bg-[#eadcc9]">
                                <div class="h-full rounded-full bg-[#4b372d]" style="width: {{ (($salesByPeriod['Bulanan'] ?? 0) / $periodMaxRevenue) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</x-app-layout>
