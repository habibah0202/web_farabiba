<x-app-layout>
    <style>
        @media print {
            @page { size: 80mm auto; margin: 4mm; }
            body * { visibility: hidden !important; }
            .receipt-print, .receipt-print * { visibility: visible !important; }
            .receipt-print { position: absolute; left: 0; top: 0; width: 72mm; margin: 0 !important; padding: 0 !important; border: 0 !important; box-shadow: none !important; }
            .no-print { display: none !important; }
        }
    </style>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="no-print mb-5 flex items-center justify-between gap-3">
            <a href="{{ route('admin.transactions.receipts') }}" class="rounded-full border border-[#d9c5b4] bg-[#f7f1ea] px-4 py-2 text-sm font-medium text-[#2d241d]">Kembali ke daftar</a>
            <button type="button" onclick="window.print()" class="rounded-full bg-[#2d241d] px-5 py-2.5 text-sm font-medium text-white">Cetak Struk</button>
        </div>

        <article class="receipt-print mx-auto max-w-sm rounded-2xl border border-[#eadcc9] bg-white p-6 text-[#2d241d] shadow-sm">
            <header class="border-b border-dashed border-[#cdb9a8] pb-4 text-center">
                <h1 class="font-serif text-2xl">GlowCare</h1>
                <p class="mt-1 text-xs text-[#6f5d55]">Struk Pembelian</p>
            </header>

            <div class="space-y-1 border-b border-dashed border-[#cdb9a8] py-4 text-xs">
                <div class="flex justify-between gap-3"><span>Invoice</span><strong class="text-right">{{ $transaction->invoice }}</strong></div>
                <div class="flex justify-between gap-3"><span>Pelanggan</span><span class="text-right">{{ $transaction->customer }}</span></div>
                <div class="flex justify-between gap-3"><span>Tanggal</span><span class="text-right">{{ $transaction->created_at->format('d M Y H:i') }}</span></div>
                <div class="flex justify-between gap-3"><span>Status</span><span>{{ $transaction->status }}</span></div>
            </div>

            <div class="space-y-3 border-b border-dashed border-[#cdb9a8] py-4">
                @forelse ($transaction->items ?? [] as $item)
                    @php
                        $quantity = (int) ($item['quantity'] ?? $item['qty'] ?? 1);
                        $unitPrice = (float) ($item['price'] ?? 0);
                    @endphp
                    <div class="text-xs">
                        <div class="font-medium">{{ $item['name'] ?? 'Produk' }}</div>
                        <div class="mt-1 flex justify-between gap-3 text-[#6f5d55]">
                            <span>{{ $quantity }} x Rp {{ number_format($unitPrice, 0, ',', '.') }}</span>
                            <span>Rp {{ number_format($quantity * $unitPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#6f5d55]">Tidak ada rincian produk.</p>
                @endforelse
            </div>

            <div class="flex justify-between gap-3 pt-4 text-sm font-semibold">
                <span>Total</span>
                <span>Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}</span>
            </div>
            <p class="mt-5 text-center text-[10px] text-[#6f5d55]">Terima kasih telah berbelanja di GlowCare.</p>
        </article>
    </div>
</x-app-layout>