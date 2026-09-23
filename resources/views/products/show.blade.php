<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Produk</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Detail Produk</h1>
            </div>
            <a href="{{ route('products.index') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-4 py-2 text-sm font-medium text-[#2d241d]">Kembali</a>
        </div>

        <div class="rounded-[2rem] border border-[#eadcc9] bg-white p-6 shadow-sm">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <div class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Nama Produk</div>
                    <h2 class="mt-2 text-3xl font-serif text-[#2d241d]">{{ $product->name }}</h2>
                </div>
                <div class="rounded-2xl bg-[#f9f4ef] p-4">
                    <div class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Status</div>
                    <div class="mt-2 inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </div>
                </div>
            </div>

            <dl class="mt-8 grid gap-5 md:grid-cols-2">
                <div class="rounded-2xl bg-[#f9f4ef] p-4">
                    <dt class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Kategori</dt>
                    <dd class="mt-2 text-lg font-medium text-[#2d241d]">{{ $product->category?->name ?? '-' }}</dd>
                </div>
                <div class="rounded-2xl bg-[#f9f4ef] p-4">
                    <dt class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Harga</dt>
                    <dd class="mt-2 text-lg font-medium text-[#2d241d]">Rp {{ number_format($product->price, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-2xl bg-[#f9f4ef] p-4">
                    <dt class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Stok</dt>
                    <dd class="mt-2 text-lg font-medium text-[#2d241d]">{{ $product->stock }} unit</dd>
                </div>
                <div class="rounded-2xl bg-[#f9f4ef] p-4">
                    <dt class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Dibuat</dt>
                    <dd class="mt-2 text-lg font-medium text-[#2d241d]">{{ $product->created_at->format('d M Y') }}</dd>
                </div>
            </dl>

            <div class="mt-8 rounded-2xl bg-[#f9f4ef] p-4">
                <div class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Deskripsi</div>
                <p class="mt-3 text-base leading-7 text-[#3f3129]">{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
