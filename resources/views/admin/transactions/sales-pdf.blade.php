<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Penjualan</title>
    <style>
        @page { margin: 28px 32px; }
        body { font-family: DejaVu Sans, sans-serif; color: #2d241d; font-size: 11px; }
        h1 { margin: 0 0 5px; font-size: 22px; }
        .subtitle { color: #75665e; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f4ece5; text-align: left; color: #57483f; }
        th, td { padding: 9px 8px; border: 1px solid #e5d8cb; }
        .number { text-align: right; }
        tfoot td { font-weight: bold; background: #faf7f4; }
        .empty { text-align: center; padding: 24px; color: #75665e; }
    </style>
</head>
<body>
    <h1>GlowCare — Transaksi Penjualan</h1>
    <div class="subtitle">Diekspor {{ now()->format('d M Y H:i') }} WIB</div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th class="number">Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->invoice }}</td>
                    <td>{{ $transaction->customer }}</td>
                    <td>{{ $transaction->created_at?->format('d M Y H:i') }} WIB</td>
                    <td class="number">Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}</td>
                    <td>{{ $transaction->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">Belum ada transaksi penjualan.</td>
                </tr>
            @endforelse
        </tbody>
        @if ($transactions->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="3">Total Penjualan</td>
                    <td class="number">Rp {{ number_format((float) $transactions->sum('total'), 0, ',', '.') }}</td>
                    <td>{{ $transactions->count() }} transaksi</td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>