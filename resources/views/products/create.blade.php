<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Produk</p>
            <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Tambah Produk</h1>
        </div>

        <form action="{{ route('products.store') }}" method="POST" class="rounded-[2rem] border border-[#eadcc9] bg-white p-6 shadow-sm">
            @csrf
            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none" required>
                    @error('name') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Kategori</label>
                    <select name="category_id" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Harga</label>
                    <input type="number" name="price" value="{{ old('price') }}" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none" required>
                    @error('price') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none" required>
                    @error('stock') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Status</label>
                    <select name="status" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-[#3f3129]">Deskripsi</label>
                    <textarea name="description" rows="4" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none">{{ old('description') }}</textarea>
                    @error('description') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('products.index') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-5 py-2.5 text-sm font-medium text-[#2d241d]">Batal</a>
                <button type="submit" class="rounded-full bg-[#2d241d] px-5 py-2.5 text-sm font-medium text-[#f8efe8]">Simpan Produk</button>
            </div>
        </form>
    </div>
</x-app-layout>
