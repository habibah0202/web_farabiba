<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Luminia') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f6f3ee] text-[#2a211b] antialiased">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <header class="flex items-center justify-between border-b border-[#e5d8cb] bg-[#f6f3ee] py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#2d241d] text-lg font-semibold text-[#f7efe9]">G</div>
                    <span class="font-serif text-4xl tracking-tight text-[#2d241d]">GlowCare</span>
                </div>

                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-full bg-[#2d241d] px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-[#1d1815]">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full border border-[#d9c7b5] bg-white px-5 py-2.5 text-sm font-medium text-[#2d241d] transition hover:bg-[#f9f1ea]">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-full bg-[#2d241d] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#1d1815]">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </header>

            <main class="py-8 sm:py-12">
                <section class="grid gap-8 rounded-[2rem] border border-[#e7d9cb] bg-[#f3ebdf] p-6 shadow-[0_20px_45px_rgba(72,49,35,0.08)] lg:grid-cols-[1.05fr_1.15fr] lg:p-10">
                    <div class="flex flex-col justify-center">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.38em] text-[#7b675d]">Free shipping on orders over 180k</p>
                        <h1 class="mt-6 max-w-md font-serif text-5xl leading-[0.95] text-[#2a211b] sm:text-6xl">
                            Glow naturally, with authentic skincare.
                        </h1>
                        <p class="mt-6 max-w-lg text-base leading-7 text-[#5d4d45]">
                            Skincare inspired by nature, perfected by science. Our clean, effective formulas help you glow every day with confidence.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ route('login') }}" class="rounded-full bg-[#2d241d] px-6 py-3 text-sm font-medium text-[#f9f2ed] shadow-sm transition hover:bg-[#1e1916]">
                                Shop now
                            </a>
                            <a href="#produk" class="rounded-full border border-[#d9c7b5] bg-white/70 px-6 py-3 text-sm font-medium text-[#2d241d] transition hover:bg-white">
                                Our story
                            </a>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-3 text-sm text-[#5d4d45]">
                            <span class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2">Clean &amp; Natural</span>
                            <span class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2">Science-backed</span>
                            <span class="rounded-full border border-[#dcc8b8] bg-[#fffaf6] px-3 py-2">Cruelty free</span>
                        </div>
                    </div>

                    <div class="relative min-h-[420px] overflow-hidden rounded-[2rem] border border-[#eadcc9] bg-[#f8f1ea] p-6">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.8),_transparent_40%)]"></div>
                        <div class="absolute left-1/2 top-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/30 blur-2xl"></div>
                        <div class="absolute bottom-8 right-10 h-52 w-52 rounded-full bg-[#d0c0ad]/40 blur-3xl"></div>

                        <div class="relative mx-auto mt-10 h-[320px] w-[220px]">
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

                <section id="produk" class="mt-10 grid gap-4 md:grid-cols-3">
                    <div class="rounded-[1.8rem] border border-[#e7d9cb] bg-white/80 p-6 shadow-sm">
                        <div class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7b675d]">Best seller</div>
                        <h2 class="mt-4 font-serif text-3xl text-[#2d241d]">Hydrating Essence</h2>
                        <p class="mt-3 text-sm leading-6 text-[#5d4d45]">Refreshing toner untuk kulit lembap dan siap menutrisi sepanjang hari.</p>
                        <div class="mt-5 flex items-center justify-between">
                            <span class="text-xl font-semibold text-[#2d241d]">Rp 245.000</span>
                            <span class="rounded-full bg-[#efe0d5] px-2.5 py-1 text-xs font-medium text-[#5d4d45]">4.9/5</span>
                        </div>
                    </div>

                    <div class="rounded-[1.8rem] border border-[#e7d9cb] bg-white/80 p-6 shadow-sm">
                        <div class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7b675d]">New arrival</div>
                        <h2 class="mt-4 font-serif text-3xl text-[#2d241d]">Barrier Repair Cream</h2>
                        <p class="mt-3 text-sm leading-6 text-[#5d4d45]">Krim pelembap dengan formula kuat untuk menutup dan menjaga skin barrier.</p>
                        <div class="mt-5 flex items-center justify-between">
                            <span class="text-xl font-semibold text-[#2d241d]">Rp 320.000</span>
                            <span class="rounded-full bg-[#efe0d5] px-2.5 py-1 text-xs font-medium text-[#5d4d45]">5.0/5</span>
                        </div>
                    </div>

                    <div class="rounded-[1.8rem] border border-[#e7d9cb] bg-white/80 p-6 shadow-sm">
                        <div class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7b675d]">Glow booster</div>
                        <h2 class="mt-4 font-serif text-3xl text-[#2d241d]">Vitamin C Serum</h2>
                        <p class="mt-3 text-sm leading-6 text-[#5d4d45]">Serum yang membantu memudarkan noda dan menonjolkan kilau alami kulit.</p>
                        <div class="mt-5 flex items-center justify-between">
                            <span class="text-xl font-semibold text-[#2d241d]">Rp 290.000</span>
                            <span class="rounded-full bg-[#efe0d5] px-2.5 py-1 text-xs font-medium text-[#5d4d45]">4.8/5</span>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>