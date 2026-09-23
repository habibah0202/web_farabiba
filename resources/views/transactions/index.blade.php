<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Transaksi</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Daftar Transaksi</h1>
            </div>
            <button class="rounded-full bg-[#2d241d] px-5 py-3 text-sm font-medium text-[#f8efe8] shadow-sm hover:bg-[#1d1815]">
                + Buat Transaksi
            </button>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-[#eadcc9] bg-white shadow-sm">
            <table class="min-w-full divide-y divide-[#f0e3d8] text-left text-sm">
                <thead class="bg-[#f9f4ef] text-[#6f5d55]">
                    <tr>
                        <th class="px-4 py-3 font-medium">Invoice</th>
                        <th class="px-4 py-3 font-medium">Pelanggan</th>
                        <th class="px-4 py-3 font-medium">Total</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e3d8] bg-white">
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="px-4 py-3 font-medium text-[#2d241d]">{{ $transaction['invoice'] }}</td>
                            <td class="px-4 py-3">{{ $transaction['customer'] }}</td>
                            <td class="px-4 py-3">{{ $transaction['total'] }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-medium {{ $transaction['status'] === 'Lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $transaction['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
