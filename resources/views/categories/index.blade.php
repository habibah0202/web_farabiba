<x-app-layout>
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Kategori</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Daftar Kategori</h1>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[2rem] border border-[#eadcc9] bg-white p-6 shadow-sm">
                <h2 class="font-serif text-2xl text-[#2d241d]">Tambah Kategori</h2>
                <form action="{{ route('categories.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-medium text-[#3f3129]">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 focus:border-[#2d241d] focus:outline-none" required>
                        @error('name') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="rounded-full bg-[#2d241d] px-5 py-2.5 text-sm font-medium text-[#f8efe8]">Simpan</button>
                </form>
            </div>

            <div class="rounded-[2rem] border border-[#eadcc9] bg-white p-6 shadow-sm">
                <h2 class="font-serif text-2xl text-[#2d241d]">List Kategori</h2>
                <div class="mt-5 space-y-3">
                    @forelse ($categories as $category)
                        @php
                            $categoryInUse = $category->products()->exists();
                        @endphp
                        <div class="flex items-center justify-between rounded-2xl border border-[#eadcc9] bg-[#f9f4ef] p-3">
                            <span class="font-medium text-[#2d241d]">{{ $category->name }}</span>
                            <div class="flex items-center gap-2">
                                @if ($categoryInUse)
                                    <span class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-700">Dipakai Produk</span>
                                @else
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-600">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-[#6f5d55]">Belum ada kategori.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
