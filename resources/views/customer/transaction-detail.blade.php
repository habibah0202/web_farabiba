<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'GlowCare') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f6f3ee] text-[#2a211b] antialiased">
        <div class="min-h-screen bg-[#f6f3ee] text-[#2a211b]">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <header class="mb-8 flex items-center justify-between border-b border-[#e5d8cb] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#2d241d] text-lg font-semibold text-[#f7efe9]">G</div>
                        <div class="font-serif text-3xl tracking-tight text-[#2d241d]">GlowCare</div>
                    </div>

                    <nav class="hidden items-center gap-6 text-sm font-medium text-[#584d46] md:flex">
                        <a href="{{ route('customer.index') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.index') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Home</a>
                        <a href="{{ route('customer.categories') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.categories') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Kategori Produk</a>
                        <a href="{{ route('customer.search') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.search') || request()->routeIs('customer.filter') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Pencarian Produk</a>
                        <a href="{{ route('customer.transactions') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.transactions') || request()->routeIs('customer.transactions.show') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Riwayat</a>
                    </nav>

                    <div class="flex items-center gap-3">
                        @php($cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity'))
                        <a href="{{ route('customer.cart') }}" class="relative inline-flex items-center justify-center rounded-full border border-[#d9c7b5] bg-white p-2.5 text-[#2d241d] shadow-sm transition hover:bg-[#f7f1ea]" aria-label="Keranjang">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.2 9.2a1 1 0 0 0 1 .8h8.7a1 1 0 0 0 1-.8L20 7H7" />
                                <circle cx="10" cy="18" r="1.4" />
                                <circle cx="17" cy="18" r="1.4" />
                            </svg>
                            @if ($cartCount > 0)
                                <span class="absolute -right-1.5 -top-1.5 flex min-h-5 min-w-5 items-center justify-center rounded-full bg-[#2d241d] px-1 text-[10px] font-bold leading-none text-white">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <div class="relative z-20" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="inline-flex items-center gap-2 rounded-full bg-[#2d241d] px-3 py-2 text-sm font-medium text-white">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#f1e9df] text-xs font-semibold text-[#2d241d]">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span>{{ Auth::user()->name }}</span>
                            </button>

                            <div x-show="open" x-cloak class="absolute right-0 top-full z-50 mt-2 w-52 rounded-2xl border border-[#eadcc9] bg-white p-2 shadow-lg" @click.outside="open = false">
                                <a href="{{ route('customer.profile') }}" class="block rounded-xl px-3 py-2 text-sm text-[#2d241d] hover:bg-[#f7f1ea]">Profil Saya</a>
                                <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2 text-sm text-[#2d241d] hover:bg-[#f7f1ea]">Edit Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full rounded-xl px-3 py-2 text-left text-sm text-[#2d241d] hover:bg-[#f7f1ea]">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>
            </div>

            <div class="mx-auto max-w-5xl px-4 pb-8 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Detail transaksi</p>
                    <h1 class="mt-2 break-all font-serif text-4xl text-[#2d241d]">{{ $transaction['invoice'] ?? 'Detail Transaksi' }}</h1>
                </div>

                @if ($transaction)
                <div class="rounded-[1.8rem] border border-[#e7d9cb] bg-[#f9f4ef] p-6 shadow-sm">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-sm text-[#5d4d45]">Status</div>
                            <div class="mt-1 text-lg font-semibold text-[#2d241d]">{{ $transaction['status'] }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-[#5d4d45]">Total</div>
                            <div class="mt-1 text-lg font-semibold text-[#2d241d]">Rp {{ number_format((float) $transaction['total'], 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach ($transaction['items'] as $item)
                            <div class="flex flex-col gap-2 border-b border-[#eadcc9] pb-3 last:border-b-0 last:pb-0 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0 pr-3">
                                    <div class="break-words font-medium text-[#2d241d]">{{ $item['name'] }}</div>
                                    <div class="text-sm text-[#5d4d45]">Qty: {{ $item['quantity'] }}</div>
                                </div>
                                <div class="shrink-0 font-medium text-[#2d241d]">Rp {{ number_format((float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0), 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rounded-[1.6rem] border border-dashed border-[#d9c7b5] bg-[#f9f4ef] p-8 text-center text-[#5d4d45]">
                    Transaksi tidak ditemukan.
                </div>
            @endif
        </div>
    </body>
</html>
