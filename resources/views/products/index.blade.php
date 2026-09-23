<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Produk</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Daftar Produk</h1>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('categories.index') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-5 py-2.5 text-sm font-medium text-[#2d241d] shadow-sm transition hover:bg-white">
                    + Tambah Kategori
                </a>
                <a href="{{ route('products.create') }}" class="rounded-full bg-[#2d241d] px-5 py-2.5 text-sm font-medium text-[#f8efe8] shadow-sm transition hover:bg-[#1d1815]">
                    + Tambah Produk
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('products.index') }}" method="GET" class="mb-6 rounded-[1.75rem] border border-[#eadcc9] bg-white p-4 shadow-sm">
            <div class="grid gap-3 lg:grid-cols-[1.4fr_0.9fr_0.8fr_auto] lg:items-end">
                <div>
                    <label for="search" class="mb-1 block text-xs font-semibold uppercase tracking-[0.22em] text-[#7d685f]">Cari</label>
                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari produk atau kategori..."
                        class="w-full rounded-full border border-[#e1d2c0] bg-[#faf7f4] px-4 py-3 text-sm text-[#2d241d] focus:border-[#2d241d] focus:outline-none"
                    >
                </div>

                <div>
                    <label for="category_id" class="mb-1 block text-xs font-semibold uppercase tracking-[0.22em] text-[#7d685f]">Kategori</label>
                    <select id="category_id" name="category_id" class="w-full rounded-full border border-[#e1d2c0] bg-[#faf7f4] px-4 py-3 text-sm text-[#2d241d] focus:border-[#2d241d] focus:outline-none">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) ($categoryId ?? '') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-[0.22em] text-[#7d685f]">Status</label>
                    <select id="status" name="status" class="w-full rounded-full border border-[#e1d2c0] bg-[#faf7f4] px-4 py-3 text-sm text-[#2d241d] focus:border-[#2d241d] focus:outline-none">
                        <option value="">Semua</option>
                        <option value="active" {{ ($statusFilter ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ ($statusFilter ?? '') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="rounded-full bg-[#2d241d] px-5 py-3 text-sm font-medium text-[#f8efe8] transition hover:bg-[#1d1815]">
                        Cari
                    </button>

                    @if ($search || $categoryId || ($statusFilter !== null && $statusFilter !== ''))
                        <a href="{{ route('products.index') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-5 py-3 text-sm font-medium text-[#2d241d]">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <div class="mb-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] border border-[#eadcc9] bg-[#f9f4ef] p-4">
                <div class="text-[10px] font-semibold uppercase tracking-[0.24em] text-[#7d685f]">Produk</div>
                <div class="mt-2 text-2xl font-semibold text-[#2d241d]">{{ $products->count() }}</div>
            </div>
            <div class="rounded-[1.5rem] border border-[#eadcc9] bg-[#f9f4ef] p-4">
                <div class="text-[10px] font-semibold uppercase tracking-[0.24em] text-[#7d685f]">Aktif</div>
                <div class="mt-2 text-2xl font-semibold text-[#2d241d]">{{ $products->where('status', 'active')->count() }}</div>
            </div>
            <div class="rounded-[1.5rem] border border-[#eadcc9] bg-[#f9f4ef] p-4">
                <div class="text-[10px] font-semibold uppercase tracking-[0.24em] text-[#7d685f]">Kategori</div>
                <div class="mt-2 text-2xl font-semibold text-[#2d241d]">{{ $categories->count() }}</div>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($products as $product)
                <article class="rounded-[1.75rem] border {{ $product->stock <= 10 ? 'border-red-200 bg-red-50/40' : 'border-[#eadcc9] bg-white' }} p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="rounded-full bg-[#f5e7dc] px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-[#6a564d]">
                            {{ $product->category?->name ?? 'Tanpa Kategori' }}
                        </span>
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-medium {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    @if ($product->stock <= 10)
                        <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-red-700">
                            <span class="h-2 w-2 rounded-full bg-red-600"></span>
                            Stok menipis
                        </div>
                    @endif

                    <h2 class="mt-4 text-2xl font-serif text-[#2d241d]">{{ $product->name }}</h2>
                    <p class="mt-3 text-sm leading-6 text-[#5d5049]">
                        {{ $product->description ? \Illuminate\Support\Str::limit($product->description, 110) : 'Tidak ada deskripsi.' }}
                    </p>

                    <div class="mt-5 space-y-2 rounded-2xl {{ $product->stock <= 10 ? 'bg-red-50' : 'bg-[#f9f4ef]' }} p-3 text-sm text-[#3f3129]">
                        <div class="flex items-center justify-between">
                            <span class="text-[#7d685f]">Stok</span>
                            <span class="font-semibold {{ $product->stock <= 10 ? 'text-red-700' : 'text-[#2d241d]' }}">{{ $product->stock }} pcs</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#7d685f]">Harga</span>
                            <span class="font-semibold text-[#2d241d]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <a href="{{ route('products.show', $product) }}" class="rounded-lg border border-[#d9c5b4] bg-[#f7f1ea] px-3 py-1.5 text-xs font-medium text-[#2d241d]">Detail</a>
                        <a href="{{ route('products.edit', $product) }}" class="rounded-lg border border-[#d9c5b4] bg-white px-3 py-1.5 text-xs font-medium text-[#2d241d]">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600">Hapus</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-[1.75rem] border border-dashed border-[#d9c5b4] bg-[#f9f4ef] p-8 text-center text-[#6f5d55]">
                    Belum ada produk yang cocok dengan pencarian Anda.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
