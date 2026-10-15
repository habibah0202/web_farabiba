<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Our Story | {{ config('app.name', 'GlowCare') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f3ee] text-[#2a211b] antialiased">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
        <header class="flex items-center justify-between gap-3 border-b border-[#e5d8cb] py-3 sm:py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="GlowCare, halaman utama">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#2d241d] text-base font-semibold text-[#f7efe9] sm:h-10 sm:w-10 sm:text-lg">G</span>
                <span class="font-serif text-3xl tracking-tight text-[#2d241d] sm:text-4xl">GlowCare</span>
            </a>
            <a href="{{ auth()->check() ? route('customer.index') : route('login') }}" class="rounded-full bg-[#2d241d] px-4 py-2.5 text-xs font-medium text-white transition hover:bg-[#1d1815] sm:px-5 sm:text-sm">Jelajahi produk</a>
        </header>

        <main class="py-7 sm:py-12">
            <section class="overflow-hidden rounded-[1.5rem] border border-[#e7d9cb] bg-[#f3ebdf] p-6 shadow-[0_20px_45px_rgba(72,49,35,0.08)] sm:rounded-[2rem] sm:p-10 lg:p-14">
                <p class="text-[10px] font-semibold uppercase tracking-[0.38em] text-[#7b675d]">Tentang GlowCare</p>
                <h1 class="mt-5 max-w-3xl font-serif text-4xl leading-[1.02] text-[#2a211b] sm:text-6xl">Perawatan kulit yang terasa dekat dengan alam dan dipercaya oleh ilmu.</h1>
                <p class="mt-6 max-w-2xl text-sm leading-7 text-[#5d4d45] sm:text-base">GlowCare hadir untuk membuat rutinitas perawatan kulit terasa sederhana, nyaman, dan penuh perhatian. Kami percaya kulit setiap orang layak dirawat dengan formula yang efektif dan pilihan bahan yang dipertimbangkan dengan baik.</p>
                <a href="{{ auth()->check() ? route('customer.index') : route('login') }}" class="mt-8 inline-flex rounded-full bg-[#2d241d] px-6 py-3 text-sm font-medium text-white transition hover:bg-[#1d1815]">Temukan perawatanmu</a>
            </section>

            <section class="mt-7 grid gap-4 sm:mt-10 md:grid-cols-3">
                <a id="ingredients" href="#ingredients" class="scroll-mt-6 rounded-[1.5rem] border border-[#e7d9cb] bg-white/80 p-6 transition hover:-translate-y-1 hover:shadow-md sm:rounded-[1.8rem] sm:p-8">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7b675d]">01 · Bahan terpilih</span>
                    <h2 class="mt-4 font-serif text-3xl">Clean &amp; Natural</h2>
                    <p class="mt-3 text-sm leading-6 text-[#5d4d45]">Kami mengutamakan bahan yang dipilih dengan cermat untuk mendukung rutinitas kulit yang nyaman.</p>
                </a>
                <a id="science" href="#science" class="scroll-mt-6 rounded-[1.5rem] border border-[#e7d9cb] bg-white/80 p-6 transition hover:-translate-y-1 hover:shadow-md sm:rounded-[1.8rem] sm:p-8">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7b675d]">02 · Formula efektif</span>
                    <h2 class="mt-4 font-serif text-3xl">Science-backed</h2>
                    <p class="mt-3 text-sm leading-6 text-[#5d4d45]">Setiap formula dirancang dengan fokus pada manfaat, kenyamanan, dan pemakaian sehari-hari.</p>
                </a>
                <a id="cruelty-free" href="#cruelty-free" class="scroll-mt-6 rounded-[1.5rem] border border-[#e7d9cb] bg-white/80 p-6 transition hover:-translate-y-1 hover:shadow-md sm:rounded-[1.8rem] sm:p-8">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7b675d]">03 · Penuh kepedulian</span>
                    <h2 class="mt-4 font-serif text-3xl">Cruelty free</h2>
                    <p class="mt-3 text-sm leading-6 text-[#5d4d45]">Kami percaya perawatan diri dapat berjalan seiring dengan kepedulian terhadap makhluk hidup.</p>
                </a>
            </section>

            <section class="mt-7 flex flex-col gap-4 rounded-[1.5rem] border border-[#e7d9cb] bg-[#fffaf6] p-6 sm:mt-10 sm:flex-row sm:items-center sm:justify-between sm:rounded-[2rem] sm:p-8">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-[#7b675d]">Mulai dari rutinitasmu</p>
                    <h2 class="mt-2 font-serif text-3xl">Temukan produk GlowCare.</h2>
                </div>
                <a href="{{ auth()->check() ? route('customer.index') : route('login') }}" class="inline-flex justify-center rounded-full border border-[#d9c7b5] bg-white px-5 py-3 text-sm font-medium text-[#2d241d] transition hover:bg-[#f9f1ea]">Belanja sekarang</a>
            </section>
        </main>
    </div>
</body>
</html>
