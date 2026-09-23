<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'GlowCare') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f6f3ee] text-[#2a211b] antialiased">
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-6">
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Checkout</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Checkout</h1>
            </div>

            @if (empty($cart))
                <div class="rounded-[1.6rem] border border-dashed border-[#d9c7b5] bg-[#f9f4ef] p-8 text-center text-[#5d4d45]">
                    Keranjang masih kosong. Silakan tambah produk terlebih dahulu.
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="space-y-4 rounded-[1.8rem] border border-[#e7d9cb] bg-[#f9f4ef] p-5">
                        @foreach ($cart as $item)
                            <div class="flex items-center justify-between border-b border-[#eadcc9] pb-3 last:border-b-0 last:pb-0">
                                <div>
                                    <div class="font-medium text-[#2d241d]">{{ $item['name'] }}</div>
                                    <div class="text-sm text-[#5d4d45]">Qty: {{ $item['quantity'] }}</div>
                                </div>
                                <div class="font-medium text-[#2d241d]">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="rounded-[1.8rem] border border-[#e7d9cb] bg-white p-6 shadow-sm">
                        <div class="text-lg font-semibold text-[#2d241d]">Ringkasan</div>
                        <div class="mt-5 space-y-3 text-sm text-[#5d4d45]">
                            <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between"><span>Ongkir</span><span>Rp 0</span></div>
                            <div class="flex justify-between"><span>Diskon</span><span>Rp 0</span></div>
                        </div>
                        <div class="mt-5 border-t border-[#eadcc9] pt-4 text-lg font-semibold text-[#2d241d]">
                            <div class="flex justify-between"><span>Total Pembayaran</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                        </div>

                        <form method="POST" action="{{ route('customer.checkout.store') }}" class="mt-6">
                            @csrf
                            <button type="submit" class="w-full rounded-full bg-[#2d241d] px-6 py-3 text-sm font-medium text-white">Konfirmasi Pembayaran</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </body>
</html>
