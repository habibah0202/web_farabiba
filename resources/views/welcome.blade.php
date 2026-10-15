<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'GlowCare') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f6f3ee] text-[#2a211b] antialiased">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <header class="flex items-center justify-between gap-3 border-b border-[#e5d8cb] bg-[#f6f3ee] py-3 sm:py-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="GlowCare, halaman utama">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#2d241d] text-base font-semibold text-[#f7efe9] sm:h-10 sm:w-10 sm:text-lg">G</span>
                        <span class="font-serif text-3xl tracking-tight text-[#2d241d] sm:text-4xl">GlowCare</span>
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="whitespace-nowrap rounded-full bg-[#2d241d] px-4 py-2.5 text-xs font-medium text-white shadow-sm transition hover:bg-[#1d1815] sm:px-5 sm:text-sm">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full border border-[#d9c7b5] bg-white px-4 py-2.5 text-xs font-medium text-[#2d241d] transition hover:bg-[#f9f1ea] sm:px-5 sm:text-sm">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="hidden rounded-full bg-[#2d241d] px-4 py-2.5 text-xs font-medium text-white transition hover:bg-[#1d1815] sm:inline-flex sm:px-5 sm:text-sm">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </header>

            <main class="py-6 sm:py-12">
                <section class="grid gap-6 rounded-[1.5rem] border border-[#e7d9cb] bg-[#f3ebdf] p-5 shadow-[0_20px_45px_rgba(72,49,35,0.08)] sm:gap-8 sm:rounded-[2rem] sm:p-8 lg:grid-cols-[1.05fr_1.15fr] lg:p-10">
                    <div class="flex flex-col justify-center">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-[#7b675d] sm:tracking-[0.38em]">Free shipping on orders over 180k</p>
                        <h1 class="mt-5 max-w-md font-serif text-4xl leading-[0.98] text-[#2a211b] sm:mt-6 sm:text-5xl lg:text-6xl">
                            Glow naturally, with authentic skincare.
                        </h1>
                        <p class="mt-6 max-w-lg text-base leading-7 text-[#5d4d45]">
                            Skincare inspired by nature, perfected by science. Our clean, effective formulas help you glow every day with confidence.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ auth()->check() ? route('customer.index') : route('login') }}" class="rounded-full bg-[#2d241d] px-6 py-3 text-sm font-medium text-[#f9f2ed] shadow-sm transition hover:bg-[#1e1916]">
                                Shop now
                            </a>
                            <a href="{{ route('our-story') }}" class="rounded-full border border-[#d9c7b5] bg-white/70 px-6 py-3 text-sm font-medium text-[#2d241d] transition hover:bg-white">
                                Our story
                            </a>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-3 text-sm text-[#5d4d45]">
                            <a href="{{ route('our-story') }}#ingredients" class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2 transition hover:border-[#2d241d]">Clean &amp; Natural</a>
                            <a href="{{ route('our-story') }}#science" class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2 transition hover:border-[#2d241d]">Science-backed</a>
                            <a href="{{ route('our-story') }}#cruelty-free" class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2 transition hover:border-[#2d241d]">Cruelty free</a>
                        </div>
                    </div>

                    <div class="relative min-h-[300px] overflow-hidden rounded-[1.5rem] border border-[#eadcc9] bg-[#f8f1ea] p-4 sm:min-h-[360px] sm:rounded-[2rem] sm:p-6 lg:min-h-[420px]">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.8),_transparent_40%)]"></div>
                        <div class="absolute left-1/2 top-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/30 blur-2xl"></div>
                        <div class="absolute bottom-8 right-10 h-52 w-52 rounded-full bg-[#d0c0ad]/40 blur-3xl"></div>

                        <div class="relative mx-auto mt-3 h-[270px] w-[220px] sm:mt-10 sm:h-[320px]">
                            <div class="absolute left-1/2 top-14 h-20 w-20 -translate-x-1/2 rounded-full bg-[#d8ad7f]/50 blur-xl"></div>
                            <div class="absolute left-1/2 top-8 h-6 w-16 -translate-x-1/2 rounded-t-2xl border border-[#b38a67] bg-[#f7d9b8]"></div>
                            <div class="absolute left-1/2 top-12 h-20 w-16 -translate-x-1/2 rounded-xl border border-[#9a735b] bg-[linear-gradient(180deg,#c1804d_0%,#a55a2a_100%)] shadow-[0_12px_25px_rgba(130,76,38,0.25)]"></div>
                            <div class="absolute left-1/2 top-24 h-10 w-12 -translate-x-1/2 rounded-b-xl bg-[#2a211b]/10"></div>
                            <div class="absolute left-1/2 top-10 h-6 w-10 -translate-x-1/2 rounded-t-xl border border-[#7d5137] bg-[#f3e0c7]"></div>
                            <div class="absolute left-1/2 top-28 h-24 w-24 -translate-x-1/2 rounded-[2rem] border border-[#d0b998] bg-[linear-gradient(180deg,#f4d8b3_0%,#d2a170_100%)] shadow-[inset_0_10px_20px_rgba(255,255,255,0.4)]"></div>
                            <div class="absolute left-1/2 top-1/2 h-28 w-48 -translate-x-1/2 rounded-[2rem] border border-[#d3b292] bg-[linear-gradient(180deg,#f1dbc7_0%,#d7b07d_100%)] opacity-80 blur-[1px]"></div>
                            <div class="absolute bottom-0 left-10 h-4 w-40 rotate-12 rounded-full bg-[#c89e6b]/60 blur-sm"></div>
                        </div>
                    </div>
                </section>

                <section id="produk" class="mt-6 sm:mt-10">
                    <div class="mb-5 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Koleksi GlowCare</p>
                            <h2 class="mt-2 font-serif text-3xl text-[#2d241d] sm:text-4xl">Produk untuk rutinitas kulitmu</h2>
                        </div>
                        <span class="text-sm text-[#7b675d]">{{ $products->count() }} produk</span>
                    </div>

                    @if ($products->isEmpty())
                        <div class="rounded-[1.8rem] border border-[#e7d9cb] bg-white/80 p-8 text-center text-sm text-[#5d4d45]">
                            Produk GlowCare akan segera hadir.
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
                            @foreach ($products as $product)
                                <a href="{{ auth()->check() ? route('customer.products.show', $product) : route('login') }}" class="group block min-w-0 overflow-hidden rounded-[1.5rem] border border-[#e7d9cb] bg-white/90 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:rounded-[1.8rem]">
                                    <x-product-image :product="$product" image-class="h-48 w-full bg-[#f3ebdf] object-cover transition duration-300 group-hover:scale-[1.02] sm:h-56" />
                                    <div class="p-5 sm:p-6">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="min-w-0 truncate rounded-full bg-[#f3ebdf] px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#7b675d]">{{ $product->category?->name ?? 'Skincare' }}</span>
                                            @if ($product->reviews_count > 0)
                                                <span class="shrink-0 rounded-full bg-[#efe0d5] px-2.5 py-1 text-xs font-medium text-[#5d4d45]">★ {{ number_format((float) $product->reviews_avg_rating, 1) }} <span class="text-[#7b675d]">({{ $product->reviews_count }})</span></span>
                                            @else
                                                <span class="shrink-0 text-xs text-[#7b675d]">Belum ada ulasan</span>
                                            @endif
                                        </div>
                                        <h3 class="mt-4 line-clamp-2 font-serif text-2xl text-[#2d241d] sm:text-3xl">{{ $product->name }}</h3>
                                        <p class="mt-2 line-clamp-2 min-h-12 text-sm leading-6 text-[#5d4d45]">{{ $product->description ?: 'Temukan detail dan manfaat produk ini.' }}</p>
                                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                                            <span class="text-lg font-semibold text-[#2d241d]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            <span class="text-sm font-medium text-[#7b675d] group-hover:text-[#2d241d]">Lihat &amp; beri ulasan <span aria-hidden="true">→</span></span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </section>
            </main>
        </div>
    </body>
</html>
