@php
    $recentTransactions = \App\Models\Transaction::query()
        ->orderByDesc('created_at')
        ->take(3)
        ->get()
        ->map(function ($transaction) {
            return [
                'type' => 'purchase',
                'title' => 'Pembelian baru',
                'detail' => $transaction->invoice . ' • Rp ' . number_format((float) $transaction->total, 0, ',', '.'),
                'time' => $transaction->created_at->diffForHumans(),
            ];
        })
        ->values();

    $newCustomers = \App\Models\User::query()
        ->where('email', '!=', 'farbib@gmail.com')
        ->whereDate('created_at', today())
        ->orderByDesc('created_at')
        ->take(3)
        ->get()
        ->map(function ($user) {
            return [
                'type' => 'customer',
                'title' => 'Pelanggan baru',
                'detail' => $user->name . ' bergabung ke akun pelanggan.',
                'time' => \Carbon\Carbon::parse($user->created_at)->diffForHumans(),
            ];
        });

    $notifications = $recentTransactions->concat($newCustomers)->sortByDesc(function ($notification) {
        return match ($notification['type']) {
            'purchase' => 1,
            'customer' => 0,
            default => 0,
        };
    })->take(6)->values();
@endphp

<nav x-data="{ open: false, notifOpen: false }" class="sticky top-0 z-50 border-b border-[#e7d9cb] bg-[#f8f3ee]/90 backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#2d241d] text-sm font-bold text-[#f8efe8] shadow-sm">
                        G
                    </div>
                    <span class="font-serif text-3xl tracking-tight text-[#2d241d]">GlowCare</span>
                </a>
            </div>

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('dashboard') }}" class="border-b-2 border-transparent pb-1 text-sm font-medium text-[#5f5148] transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('dashboard') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="border-b-2 border-transparent pb-1 text-sm font-medium text-[#5f5148] transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('products.*') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">
                    Produk
                </a>
                <a href="{{ route('categories.index') }}" class="border-b-2 border-transparent pb-1 text-sm font-medium text-[#5f5148] transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('categories.*') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">
                    Kategori
                </a>
                <a href="{{ route('stock.index') }}" class="border-b-2 border-transparent pb-1 text-sm font-medium text-[#5f5148] transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('stock.*') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">
                    Stok
                </a>
                <div class="relative" x-data="{ pelangganOpen: false }">
                    <button type="button" @click="pelangganOpen = !pelangganOpen" @click.outside="pelangganOpen = false" class="flex items-center gap-1 border-b-2 border-transparent pb-1 text-sm font-medium text-[#5f5148] transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.*') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">
                        Pelanggan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="pelangganOpen" x-transition class="absolute left-0 z-20 mt-3 w-64 rounded-2xl border border-[#eadcc9] bg-white p-2 shadow-lg" style="display: none;">
                        <a href="{{ route('admin.customers.index') }}" class="block rounded-xl px-3 py-2 text-sm text-[#2d241d] transition hover:bg-[#f7f1ea] {{ request()->routeIs('admin.customers.index') ? 'bg-[#f7f1ea] font-medium' : '' }}">
                            Kelola Profil Pelanggan
                        </a>
                        <a href="{{ route('admin.customers.transactions') }}" class="block rounded-xl px-3 py-2 text-sm text-[#2d241d] transition hover:bg-[#f7f1ea] {{ request()->routeIs('admin.customers.transactions') ? 'bg-[#f7f1ea] font-medium' : '' }}">
                            Riwayat Transaksi
                        </a>
                    </div>
                </div>
                <div class="relative" x-data="{ transaksiOpen: false }">
                    <button type="button" @click="transaksiOpen = !transaksiOpen" @click.outside="transaksiOpen = false" class="flex items-center gap-1 border-b-2 border-transparent pb-1 text-sm font-medium text-[#5f5148] transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('admin.transactions.*') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">
                        Transaksi
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="transaksiOpen" x-transition class="absolute left-0 z-20 mt-3 w-72 rounded-2xl border border-[#eadcc9] bg-white p-2 shadow-lg" style="display: none;">
                        <a href="{{ route('admin.transactions.sales') }}" class="block rounded-xl px-3 py-2 text-sm text-[#2d241d] transition hover:bg-[#f7f1ea] {{ request()->routeIs('admin.transactions.sales') ? 'bg-[#f7f1ea] font-medium' : '' }}">
                            Transaksi Penjualan
                        </a>
                        <a href="{{ route('admin.transactions.receipts') }}" class="block rounded-xl px-3 py-2 text-sm text-[#2d241d] transition hover:bg-[#f7f1ea] {{ request()->routeIs('admin.transactions.receipts') ? 'bg-[#f7f1ea] font-medium' : '' }}">
                            Detail Transaksi &amp; Struk
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="border-b-2 border-transparent pb-1 text-sm font-medium text-[#5f5148] transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('admin.reports.index') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">
                    Laporan
                </a>
            </div>

            <div class="flex items-center gap-3">
                <button class="flex h-10 w-10 items-center justify-center rounded-full border border-[#e6d9c9] bg-white text-[#3a2f2a] shadow-sm transition hover:bg-[#f7efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="notifOpen = !notifOpen" @click.outside="notifOpen = false" class="relative flex h-10 w-10 items-center justify-center rounded-full border border-[#e6d9c9] bg-white text-[#3a2f2a] shadow-sm transition hover:bg-[#f7efe8]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        @if ($notifications->isNotEmpty())
                            <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#b4562d] px-1 text-[10px] font-semibold text-white">
                                {{ $notifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="notifOpen" x-transition class="absolute right-0 z-30 mt-3 w-80 rounded-2xl border border-[#eadcc9] bg-white p-3 shadow-xl" style="display: none;">
                        <div class="mb-2 flex items-center justify-between px-2">
                            <span class="text-sm font-semibold text-[#2d241d]">Notifikasi</span>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-[#7d685f]">{{ $notifications->count() }} baru</span>
                        </div>

                        <div class="max-h-80 space-y-2 overflow-y-auto">
                            @forelse ($notifications as $notification)
                                <div class="rounded-xl border border-[#f1e4d7] bg-[#faf6f2] p-3">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-7 w-7 items-center justify-center rounded-full {{ $notification['type'] === 'purchase' ? 'bg-[#eadcc9] text-[#3e2f28]' : 'bg-[#e8d9c6] text-[#5a473d]' }} text-[10px] font-bold">
                                            {{ $notification['type'] === 'purchase' ? 'B' : 'P' }}
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-[#2d241d]">{{ $notification['title'] }}</div>
                                            <div class="mt-1 text-xs text-[#6f5c54]">{{ $notification['detail'] }}</div>
                                            <div class="mt-1 text-[10px] uppercase tracking-[0.18em] text-[#8d7367]">{{ $notification['time'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-[#e4d3c0] bg-[#faf6f2] p-4 text-center text-xs text-[#755f56]">
                                    Tidak ada notifikasi baru.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 rounded-full bg-[#2d241d] px-3 py-1.5 text-sm text-[#f8efe8] shadow-sm transition hover:bg-[#1f1a17]">
                                @if (Auth::user()->foto)
                                    <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto profil" class="h-7 w-7 rounded-full object-cover ring-2 ring-[#f5e7d7]">
                                @else
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#f1e9df] text-xs font-semibold text-[#2d241d]">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="-me-2 flex items-center md:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-[#3a2f2a] hover:bg-[#efe3d5] focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-[#e7d9cb] bg-[#f8f3ee] md:hidden">
        <div class="space-y-1 px-4 py-4">
            <a href="{{ route('dashboard') }}" class="block rounded-xl px-3 py-2 text-sm text-[#2d241d] hover:bg-[#f0e7de] {{ request()->routeIs('dashboard') ? 'bg-[#f0e7de] font-medium' : '' }}">Home</a>
            <a href="{{ route('products.index') }}" class="block rounded-xl px-3 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de] {{ request()->routeIs('products.*') ? 'bg-[#f0e7de] font-medium' : '' }}">Produk</a>
            <a href="{{ route('categories.index') }}" class="block rounded-xl px-3 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de] {{ request()->routeIs('categories.*') ? 'bg-[#f0e7de] font-medium' : '' }}">Kategori</a>
            <a href="{{ route('stock.index') }}" class="block rounded-xl px-3 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de] {{ request()->routeIs('stock.*') ? 'bg-[#f0e7de] font-medium' : '' }}">Stok</a>
            <div class="rounded-xl px-3 py-2">
                <div class="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#7d685f]">Pelanggan</div>
                <div class="space-y-1 pl-2">
                    <a href="{{ route('admin.customers.index') }}" class="block rounded-lg px-2 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de] {{ request()->routeIs('admin.customers.index') ? 'bg-[#f0e7de] font-medium' : '' }}">Kelola Profil Pelanggan</a>
                    <a href="{{ route('admin.customers.transactions') }}" class="block rounded-lg px-2 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de] {{ request()->routeIs('admin.customers.transactions') ? 'bg-[#f0e7de] font-medium' : '' }}">Riwayat Transaksi</a>
                </div>
            </div>
            <div class="rounded-xl px-3 py-2">
                <div class="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#7d685f]">Transaksi</div>
                <div class="space-y-1 pl-2">
                    <a href="{{ route('admin.transactions.sales') }}" class="block rounded-lg px-2 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de] {{ request()->routeIs('admin.transactions.sales') ? 'bg-[#f0e7de] font-medium' : '' }}">Transaksi Penjualan</a>
                    <a href="{{ route('admin.transactions.receipts') }}" class="block rounded-lg px-2 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de] {{ request()->routeIs('admin.transactions.receipts') ? 'bg-[#f0e7de] font-medium' : '' }}">Detail Transaksi &amp; Struk</a>
                </div>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="block rounded-xl px-3 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de] {{ request()->routeIs('admin.reports.index') ? 'bg-[#f0e7de] font-medium' : '' }}">Laporan</a>

            <div class="mt-4 border-t border-[#e7d9cb] pt-4">
                <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2 text-sm text-[#5f5148] hover:bg-[#f0e7de]">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mt-1 block w-full rounded-xl px-3 py-2 text-left text-sm text-[#5f5148] hover:bg-[#f0e7de]">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
