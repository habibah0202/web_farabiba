<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} | {{ config('app.name', 'GlowCare') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f3ee] text-[#2a211b] antialiased">
    <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        <a href="{{ route('customer.index') }}#produk" class="mb-5 inline-flex rounded-full border border-[#d9c7b5] bg-white px-4 py-2 text-sm font-medium text-[#2d241d] transition hover:bg-[#f9f1ea]">← Kembali ke produk</a>

        <section class="grid min-w-0 gap-6 rounded-[1.5rem] border border-[#e7d9cb] bg-[#f9f4ef] p-4 shadow-sm sm:gap-8 sm:rounded-[2rem] sm:p-6 lg:grid-cols-2 lg:p-8">
            <div class="overflow-hidden rounded-[1.2rem] border border-[#eadcc9] bg-[#f3ebdf] sm:rounded-[1.7rem]">
                <x-product-image :product="$product" image-class="h-72 w-full object-cover sm:h-96 lg:h-full lg:min-h-[420px]" />
            </div>

            <div class="flex min-w-0 flex-col justify-center py-2 sm:py-4">
                <span class="w-fit rounded-full bg-white px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#7b675d]">{{ $product->category?->name ?? 'Skincare' }}</span>
                <h1 class="mt-4 break-words font-serif text-4xl leading-tight text-[#2d241d] sm:text-5xl">{{ $product->name }}</h1>
                <p class="mt-4 text-xl font-semibold text-[#2d241d]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <a href="#reviews" class="mt-3 w-fit rounded-full bg-[#efe0d5] px-3 py-1.5 text-sm font-medium text-[#5d4d45]">
                    @if ($product->reviews_count > 0)
                        ★ {{ number_format((float) $product->reviews_avg_rating, 1) }} dari 5 · {{ $product->reviews_count }} ulasan
                    @else
                        Belum ada ulasan · Jadilah yang pertama
                    @endif
                </a>
                <p class="mt-5 text-sm leading-7 text-[#5d4d45] sm:text-base">{{ $product->description ?: 'Produk perawatan kulit pilihan dari GlowCare.' }}</p>

                <div class="mt-6 flex flex-wrap gap-2 text-sm text-[#5d4d45]">
                    <span class="rounded-full bg-white px-3 py-2">Stok: {{ $product->stock }}</span>
                    <span class="rounded-full bg-white px-3 py-2">{{ $product->status === 'active' ? 'Tersedia' : 'Tidak tersedia' }}</span>
                </div>

                @if ($product->status === 'active' && $product->stock > 0)
                    <form action="{{ route('customer.products.addToCart', $product) }}" method="POST" class="mt-7 flex flex-wrap items-center gap-3">
                        @csrf
                        <label for="quantity" class="sr-only">Jumlah</label>
                        <input id="quantity" type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" class="w-20 rounded-full border border-[#d9c7b5] bg-white px-4 py-3 text-center text-sm">
                        <button type="submit" class="rounded-full bg-[#2d241d] px-6 py-3 text-sm font-medium text-white transition hover:bg-[#1d1815]">Tambah ke keranjang</button>
                    </form>
                @endif
            </div>
        </section>

        <section id="reviews" class="mt-6 scroll-mt-6 rounded-[1.5rem] border border-[#e7d9cb] bg-white/90 p-5 shadow-sm sm:mt-8 sm:rounded-[2rem] sm:p-8">
            <div class="flex flex-col gap-2 border-b border-[#eadcc9] pb-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Pengalaman pembeli</p>
                    <h2 class="mt-2 font-serif text-3xl text-[#2d241d] sm:text-4xl">Rating &amp; ulasan jujur</h2>
                </div>
                <p class="text-sm text-[#5d4d45]">{{ $product->reviews_count }} ulasan</p>
            </div>

            @if (session('review-status'))
                <div class="mt-5 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('review-status') }}</div>
            @endif

            @if ($hasPurchased)
                <form method="POST" action="{{ route('customer.products.reviews.store', $product) }}" class="mt-6 rounded-[1.3rem] bg-[#f9f4ef] p-4 sm:p-6">
                    @csrf
                    <h3 class="font-medium text-[#2d241d]">{{ $myReview ? 'Perbarui ulasanmu' : 'Bagikan pengalamanmu' }}</h3>
                    <p class="mt-1 text-sm leading-6 text-[#7b675d]">Ulasan hanya dapat diberikan oleh pembeli produk ini.</p>

                    <fieldset class="mt-5">
                        <legend class="mb-2 text-sm font-medium text-[#2d241d]">Rating</legend>
                        <div class="flex flex-wrap gap-2">
                            @foreach (range(1, 5) as $rating)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $rating }}" class="peer sr-only" @checked((int) old('rating', $myReview?->rating ?? 5) === $rating)>
                                    <span class="inline-flex min-h-11 items-center rounded-full border border-[#d9c7b5] bg-white px-4 text-sm text-[#5d4d45] transition peer-checked:border-[#2d241d] peer-checked:bg-[#2d241d] peer-checked:text-white">{{ $rating }} ★</span>
                                </label>
                            @endforeach
                        </div>
                        @error('rating') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                    </fieldset>

                    <div class="mt-5">
                        <label for="comment" class="mb-2 block text-sm font-medium text-[#2d241d]">Ceritakan pengalamanmu</label>
                        <textarea id="comment" name="comment" rows="4" required minlength="10" maxlength="2000" placeholder="Apa yang kamu rasakan setelah memakai produk ini?" class="w-full rounded-2xl border border-[#d9c7b5] bg-white px-4 py-3 text-sm leading-6 text-[#2d241d] placeholder:text-[#9a8980] focus:border-[#2d241d] focus:outline-none">{{ old('comment', $myReview?->comment) }}</textarea>
                        @error('comment') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="mt-4 w-full rounded-full bg-[#2d241d] px-6 py-3 text-sm font-medium text-white transition hover:bg-[#1d1815] sm:w-auto">{{ $myReview ? 'Simpan perubahan ulasan' : 'Kirim ulasan' }}</button>
                </form>
            @else
                <p class="mt-6 rounded-[1.3rem] bg-[#f9f4ef] p-4 text-sm leading-6 text-[#5d4d45] sm:p-5">Kamu bisa memberi rating dan ulasan setelah membeli produk ini.</p>
            @endif

            <div class="mt-6 space-y-4">
                @forelse ($product->reviews as $review)
                    <article class="rounded-[1.3rem] border border-[#eadcc9] bg-[#fffaf6] p-4 sm:p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h3 class="font-medium text-[#2d241d]">{{ $review->user->name }}</h3>
                                <p class="mt-1 text-xs text-[#7b675d]">{{ $review->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="rounded-full bg-[#efe0d5] px-3 py-1 text-sm font-medium text-[#5d4d45]">{{ $review->rating }} / 5 ★</span>
                        </div>
                        <p class="mt-4 whitespace-pre-line text-sm leading-6 text-[#5d4d45]">{{ $review->comment }}</p>
                    </article>
                @empty
                    <p class="py-4 text-sm text-[#7b675d]">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
