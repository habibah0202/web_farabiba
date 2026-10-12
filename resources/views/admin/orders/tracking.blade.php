<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Pelanggan</p>
            <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Pelacakan Pesanan</h1>
            <p class="mt-2 text-sm text-[#6f5d55]">Perbarui tahap pesanan agar pelanggan dapat melihat perkembangannya.</p>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="space-y-5">
            @forelse ($transactions as $transaction)
                <article class="rounded-[1.75rem] border border-[#eadcc9] bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-[#7d685f]">{{ $transaction->invoice }}</p>
                            <h2 class="mt-1 text-xl font-semibold text-[#2d241d]">{{ $transaction->customer }}</h2>
                            <p class="mt-1 text-sm text-[#6f5d55]">{{ $transaction->created_at->format('d M Y H:i') }} · Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}</p>
                        </div>

                        <form action="{{ route('admin.orders.tracking.update', $transaction) }}" method="POST" class="flex flex-col gap-2 sm:flex-row sm:items-end">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="order_status_{{ $transaction->id }}" class="mb-1 block text-xs font-medium text-[#6f5d55]">Status pesanan</label>
                                <select id="order_status_{{ $transaction->id }}" name="order_status" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 text-sm text-[#2d241d] sm:min-w-56">
                                    @foreach (\App\Models\Transaction::ORDER_STATUSES as $statusValue => $statusLabel)
                                        <option value="{{ $statusValue }}" {{ $transaction->order_status === $statusValue ? 'selected' : '' }}>{{ $statusLabel }}</option>
                                    @endforeach
                                </select>
                                @error('order_status') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="rounded-full bg-[#2d241d] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#1d1815]">Simpan Status</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-[1.75rem] border border-dashed border-[#d9c5b4] bg-[#f9f4ef] p-8 text-center text-[#6f5d55]">Belum ada pesanan untuk dilacak.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>