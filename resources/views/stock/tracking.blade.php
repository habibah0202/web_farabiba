<x-app-layout>
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Stok</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Tracking Barang Otomatis</h1>
            </div>
            <a href="{{ route('stock.index') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-4 py-2 text-sm font-medium text-[#2d241d]">Kembali</a>
        </div>

        <div class="rounded-[2rem] border border-[#eadcc9] bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="font-serif text-2xl text-[#2d241d]">Riwayat Perubahan Stok</h2>
                <span class="rounded-full bg-[#f5e7dc] px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-[#6a564d]">Live</span>
            </div>

            @if (empty($stockLogs))
                <div class="rounded-2xl border border-dashed border-[#d9c5b4] bg-[#f9f4ef] p-6 text-center text-[#6f5d55]">
                    Belum ada riwayat stok.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($stockLogs as $log)
                        <div class="flex items-start gap-3 rounded-2xl border border-[#eadcc9] bg-[#f9f4ef] p-4">
                            <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-full {{ $log['type'] === 'masuk' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} font-semibold">
                                {{ strtoupper(substr($log['type'], 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 text-sm">
                                    <span class="font-semibold text-[#2d241d]">{{ $log['product_name'] }}</span>
                                    <span class="rounded-full bg-white px-2 py-1 text-[10px] font-medium uppercase tracking-[0.18em] text-[#7d685f]">{{ $log['category_name'] }}</span>
                                    <span class="rounded-full {{ $log['type'] === 'masuk' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} px-2 py-1 text-[10px] font-medium uppercase tracking-[0.18em]">
                                        {{ $log['type'] === 'masuk' ? 'Masuk' : 'Keluar' }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-[#5d5049]">
                                    {{ $log['type'] === 'masuk' ? 'Penambahan stok' : 'Pengurangan stok' }} sebanyak {{ $log['quantity'] }} unit.
                                    @if ($log['note'])
                                        <span class="text-[#3f3129]">({{ $log['note'] }})</span>
                                    @endif
                                </div>
                                <div class="mt-2 text-xs text-[#7d685f]">
                                    {{ \Carbon\Carbon::parse($log['created_at'])->translatedFormat('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
