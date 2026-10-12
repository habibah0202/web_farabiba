<x-app-layout>
<style>
    @media print {
        @page { size: A4 landscape; margin: 12mm; }
        nav, .print-hide, .report-summary-cards { display: none !important; }
        body, .min-h-screen { background: #fff !important; }
        .report-print-area {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .report-table-container, .report-table-overflow {
            overflow: visible !important;
            box-shadow: none !important;
        }
        .report-table-container { border-radius: 0 !important; }
        .report-table-container table { width: 100% !important; }
        .report-table-container th, .report-table-container td {
            padding: 6px 8px !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
    <div class="report-print-area mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Laporan</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Laporan Penjualan</h1>
            </div>
            <button type="button" onclick="window.print()" class="print-hide rounded-full bg-[#2d241d] px-5 py-3 text-sm font-medium text-[#f8efe8] shadow-sm hover:bg-[#1d1815]">
                Cetak PDF
            </button>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-6 rounded-[2rem] border border-[#eadcc9] bg-white p-5 shadow-sm">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.2em] text-[#7d685f]">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 text-sm text-[#2d241d] focus:border-[#2d241d] focus:outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.2em] text-[#7d685f]">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 text-sm text-[#2d241d] focus:border-[#2d241d] focus:outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.2em] text-[#7d685f]">Periode</label>
                    <select name="period" class="w-full rounded-xl border border-[#e1d2c0] bg-[#faf7f4] px-3 py-2.5 text-sm text-[#2d241d] focus:border-[#2d241d] focus:outline-none">
                        <option value="harian" {{ ($period ?? 'harian') === 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="mingguan" {{ ($period ?? 'harian') === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulanan" {{ ($period ?? 'harian') === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="print-hide w-full rounded-xl bg-[#2d241d] px-4 py-3 text-sm font-medium text-[#f8efe8] shadow-sm hover:bg-[#1d1815]">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="report-summary-cards mb-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] border border-[#eadcc9] bg-[#f9f4ef] p-5">
                <div class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Harian</div>
                <div class="mt-3 text-2xl font-semibold text-[#2d241d]">Rp {{ number_format((float) ($dailyRevenue ?? 0), 0, ',', '.') }}</div>
            </div>
            <div class="rounded-[1.5rem] border border-[#eadcc9] bg-[#f9f4ef] p-5">
                <div class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Mingguan</div>
                <div class="mt-3 text-2xl font-semibold text-[#2d241d]">Rp {{ number_format((float) ($weeklyRevenue ?? 0), 0, ',', '.') }}</div>
            </div>
            <div class="rounded-[1.5rem] border border-[#eadcc9] bg-[#f9f4ef] p-5">
                <div class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#7d685f]">Bulanan</div>
                <div class="mt-3 text-2xl font-semibold text-[#2d241d]">Rp {{ number_format((float) ($monthlyRevenue ?? 0), 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="report-table-container overflow-hidden rounded-[2rem] border border-[#eadcc9] bg-white shadow-sm">
            <div class="report-table-overflow overflow-x-auto">
                <table class="min-w-full divide-y divide-[#f0e3d8] text-left text-sm text-[#3f3129]">
                    <thead class="bg-[#f9f4ef] text-[#6f5d55]">
                        <tr>
                            <th class="px-4 py-3 font-medium">Invoice</th>
                            <th class="px-4 py-3 font-medium">Pelanggan</th>
                            <th class="px-4 py-3 font-medium">Tanggal</th>
                            <th class="px-4 py-3 font-medium">Total</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0e3d8]">
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td class="px-4 py-3 font-medium text-[#2d241d]">{{ $transaction['invoice'] }}</td>
                                <td class="px-4 py-3">{{ $transaction['customer'] }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d M Y H:i') }} WIB</td>
                                <td class="px-4 py-3">Rp {{ number_format((float) $transaction['total'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs font-medium {{ $transaction['status'] === 'Lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $transaction['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-[#6f5d55]">Belum ada data laporan untuk periode tersebut.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
