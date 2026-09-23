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
        @php
            $featuredProducts = $products->take(3);
        @endphp

        <div class="min-h-screen bg-[#f6f3ee] text-[#2a211b]" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <header class="mb-8 flex items-center justify-between border-b border-[#e5d8cb] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#2d241d] text-lg font-semibold text-[#f7efe9]">G</div>
                        <div>
                            <div class="font-serif text-3xl tracking-tight text-[#2d241d]">GlowCare</div>
                        </div>
                    </div>

                    <nav class="hidden items-center gap-6 text-sm font-medium text-[#584d46] md:flex">
                        <a href="{{ route('customer.index') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.index') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Home</a>
                        <a href="{{ route('customer.search') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.search') || request()->routeIs('customer.filter') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Produk</a>
                        <a href="{{ route('customer.categories') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.categories') || request()->routeIs('customer.products.show') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Kategori Produk</a>
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

                <main class="relative z-0 space-y-8">
                    <section class="grid gap-6 rounded-[2rem] border border-[#e7d9cb] bg-[#f3ebdf] p-6 shadow-[0_20px_45px_rgba(72,49,35,0.08)] lg:grid-cols-[1.1fr_0.9fr] lg:p-10">
                        <div class="flex flex-col justify-center">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.38em] text-[#7b675d]">GlowCare skincare</p>
                            <h1 class="mt-5 max-w-md font-serif text-5xl leading-[0.95] text-[#2a211b] sm:text-6xl">Skincare yang bikin kulit glowing tiap hari.</h1>
                            <p class="mt-6 max-w-lg text-base leading-7 text-[#5d4d45]">Temukan produk skincare yang aman, efektif, dan nyaman dipakai untuk kebutuhan harian kulit Anda.</p>

                            <div class="mt-8 flex flex-wrap gap-4">
                                <a href="#produk" class="rounded-full bg-[#2d241d] px-6 py-3 text-sm font-medium text-[#f9f2ed] shadow-sm transition hover:bg-[#1e1916]">Belanja sekarang</a>
                                <a href="{{ route('customer.categories') }}" class="rounded-full border border-[#d9c7b5] bg-white/70 px-6 py-3 text-sm font-medium text-[#2d241d] transition hover:bg-white">Lihat kategori</a>
                            </div>

                            <div class="mt-8 flex flex-wrap gap-3 text-sm text-[#5d4d45]">
                                <span class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2">Clean &amp; Natural</span>
                                <span class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2">Science backed</span>
                                <span class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2">Cruelty free</span>
                            </div>
                        </div>

                        <div class="relative min-h-[420px] overflow-hidden rounded-[2rem] border border-[#eadcc9] bg-[#f8f1ea] p-6">
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.8),_transparent_40%)]"></div>
                            <div class="relative mx-auto mt-8 h-[340px] w-[230px]">
                                <div class="absolute left-1/2 top-8 h-6 w-16 -translate-x-1/2 rounded-t-2xl border border-[#b38a67] bg-[#f7d9b8]"></div>
                                <div class="absolute left-1/2 top-12 h-20 w-16 -translate-x-1/2 rounded-xl border border-[#9a735b] bg-[linear-gradient(180deg,#c1804d_0%,#a55a2a_100%)] shadow-[0_12px_25px_rgba(130,76,38,0.25)]"></div>
                                <div class="absolute left-1/2 top-28 h-24 w-24 -translate-x-1/2 rounded-[2rem] border border-[#d0b998] bg-[linear-gradient(180deg,#f4d8b3_0%,#d2a170_100%)] shadow-[inset_0_10px_20px_rgba(255,255,255,0.4)]"></div>
                                <div class="absolute bottom-4 left-1/2 h-8 w-48 -translate-x-1/2 rounded-full bg-[#c89e6b]/60 blur-md"></div>
                            </div>
                        </div>
                    </section>

                    <section id="produk" class="rounded-[2rem] border border-[#eadcc9] bg-white/80 p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Produk favorit</p>
                                <h2 class="mt-2 font-serif text-3xl text-[#2d241d]">Pilihan untuk kulit glowing</h2>
                            </div>
                            <a href="{{ route('customer.search') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-4 py-2 text-sm font-medium text-[#2d241d]">Lihat semua produk</a>
                        </div>

                        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($featuredProducts as $product)
                                <div class="rounded-[1.7rem] border border-[#e7d9cb] bg-[#f9f4ef] p-5 shadow-sm">
                                    <div class="mb-4 flex items-center justify-between">
                                        <span class="rounded-full bg-white px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#7b675d]">{{ $product->category?->name ?? 'Skincare' }}</span>
                                        <span class="text-sm font-medium text-[#2d241d]">{{ $product->stock }} stok</span>
                                    </div>
                                    <h3 class="font-serif text-2xl text-[#2d241d]">{{ $product->name }}</h3>
                                    <p class="mt-3 text-sm leading-6 text-[#5d4d45]">{{ \Illuminate\Support\Str::limit($product->description ?? 'Produk skincare terbaik untuk kebutuhan harian kulit.', 110) }}</p>
                                    <div class="mt-5 flex items-center justify-between gap-3">
                                        <span class="text-xl font-semibold text-[#2d241d]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('customer.products.addToCart', $product) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input type="number" name="quantity" min="1" value="1" class="w-14 rounded-full border border-[#d9c7b5] bg-white px-2 py-2 text-center text-sm text-[#2d241d] focus:border-[#2d241d] focus:outline-none">
                                                <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#2d241d] text-white shadow-sm transition hover:bg-[#1d1815]" aria-label="Tambah {{ $product->name }} ke keranjang">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="9" cy="19" r="2"></circle>
                                                        <circle cx="17" cy="19" r="2"></circle>
                                                        <path d="M3 3h2l2.4 9.4a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L20 6H7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <a href="{{ route('customer.products.show', $product) }}" class="rounded-full bg-[#2d241d] px-4 py-2 text-sm font-medium text-white">Lihat</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </body>
</html>
