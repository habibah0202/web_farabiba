<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Transaksi</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Detail Transaksi &amp; Struk</h1>
            </div>
            <button type="button" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-5 py-3 text-sm font-medium text-[#2d241d] shadow-sm">
                Struk Cetak
            </button>
        </div>

        <div class="space-y-6">
            @forelse ($transactions as $transaction)
                <div class="rounded-[2rem] border border-[#eadcc9] bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-[#f0e3d8] pb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Invoice</div>
                            <div class="mt-2 text-xl font-semibold text-[#2d241d]">{{ $transaction['invoice'] }}</div>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $transaction['status'] === 'Lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $transaction['status'] }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                        <div>
                            <h2 class="text-lg font-semibold text-[#2d241d]">Rincian Produk</h2>
                            <div class="mt-4 space-y-3">
                                @forelse ($transaction['items'] as $item)
                                    <div class="flex items-center justify-between rounded-2xl border border-[#eadcc9] bg-[#f9f4ef] p-3">
                                        <div>
                                            <div class="font-medium text-[#2d241d]">{{ $item['name'] ?? 'Produk' }}</div>
                                            <div class="text-xs text-[#6f5d55]">Qty: {{ $item['qty'] ?? 1 }}</div>
                                        </div>
                                        <div class="text-sm font-medium text-[#2d241d]">
                                            Rp {{ number_format((float) ($item['price'] ?? 0), 0, ',', '.') }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-[#d9c5b4] bg-[#f9f4ef] p-4 text-sm text-[#6f5d55]">
                                        Tidak ada item dalam transaksi ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-[1.5rem] border border-[#eadcc9] bg-[#f9f4ef] p-5">
                            <h2 class="text-lg font-semibold text-[#2d241d]">Total Pembayaran</h2>
                            <div class="mt-4 space-y-3 text-sm text-[#3f3129]">
                                <div class="flex items-center justify-between">
                                    <span>Pelanggan</span>
                                    <span class="font-medium text-[#2d241d]">{{ $transaction['customer'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Tanggal</span>
                                    <span>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d M Y H:i') }}</span>
                                </div>
                                <div class="flex items-center justify-between border-t border-[#eadcc9] pt-3">
                                    <span class="text-base font-semibold text-[#2d241d]">Total</span>
                                    <span class="text-base font-semibold text-[#2d241d]">Rp {{ number_format((float) $transaction['total'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-[2rem] border border-dashed border-[#d9c5b4] bg-[#f9f4ef] p-8 text-center text-[#6f5d55]">
                    Belum ada detail transaksi dan struk.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
