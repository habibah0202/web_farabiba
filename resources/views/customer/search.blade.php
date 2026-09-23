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
                        <a href="{{ route('customer.search') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.search') || request()->routeIs('customer.filter') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Produk</a>
                        <a href="{{ route('customer.categories') }}" class="border-b-2 border-transparent pb-1 transition hover:border-[#2d241d] hover:text-[#2d241d] {{ request()->routeIs('customer.categories') ? 'border-[#2d241d] text-[#2d241d]' : '' }}">Kategori Produk</a>
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

            <div class="mx-auto max-w-6xl px-4 pb-8 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Pencarian</p>
                    <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Cari Produk</h1>
                </div>

                <form method="GET" action="{{ route('customer.search') }}" class="mb-6 flex flex-col gap-3 md:flex-row md:items-center">
                    <div class="flex w-full items-center gap-3 rounded-full border border-[#d9c7b5] bg-white px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#7b675d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>
                        <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Cari produk skincare..." class="w-full bg-transparent text-sm text-[#2d241d] placeholder:text-[#8b7267] focus:outline-none" />
                    </div>

                    <select name="category_id" class="rounded-full border border-[#d9c7b5] bg-white px-4 py-3 text-sm text-[#2d241d] focus:border-[#2d241d] focus:outline-none">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) ($categoryId ?? '') === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="rounded-full bg-[#2d241d] px-5 py-3 text-sm font-medium text-white">Cari</button>
                </form>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($products as $product)
                        <div class="rounded-[1.7rem] border border-[#e7d9cb] bg-[#f9f4ef] p-5 shadow-sm">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="rounded-full bg-white px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#7b675d]">{{ $product->category?->name ?? 'Skincare' }}</span>
                                <span class="text-sm font-medium text-[#2d241d]">{{ $product->stock }} stok</span>
                            </div>
                            <h3 class="font-serif text-2xl text-[#2d241d]">{{ $product->name }}</h3>
                            <p class="mt-3 text-sm leading-6 text-[#5d4d45]">{{ \Illuminate\Support\Str::limit($product->description ?? 'Produk skincare terbaik.', 110) }}</p>
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
                    @empty
                        <div class="col-span-full rounded-[1.6rem] border border-dashed border-[#d9c7b5] bg-[#f9f4ef] p-8 text-center text-[#5d4d45]">
                            Produk yang Anda cari tidak ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </body>
</html>
