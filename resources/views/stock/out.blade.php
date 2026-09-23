<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Stok</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Stok Keluar</h1>
            </div>
            <a href="{{ route('stock.index') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-4 py-2 text-sm font-medium text-[#2d241d]">Kembali</a>
        </div>

        <form action="{{ route('stock.out.store') }}" method="POST" class="rounded-[2rem] border border-[#eadcc9] bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Produk</label>
                    <select name="product_id" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none" required>
                        <option value="">Pilih produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->category?->name ?? 'Tanpa kategori' }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Jumlah</label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none" required>
                    @error('quantity') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Catatan</label>
                    <input type="text" name="note" value="{{ old('note') }}" placeholder="Contoh: Penjualan retail" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none">
                    @error('note') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('stock.index') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-5 py-2.5 text-sm font-medium text-[#2d241d]">Batal</a>
                <button type="submit" class="rounded-full bg-[#2d241d] px-5 py-2.5 text-sm font-medium text-[#f8efe8]">Simpan Stok Keluar</button>
            </div>
        </form>
    </div>
</x-app-layout>
