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
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Profil</p>
                    <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Profil Pelanggan</h1>
                </div>
                <a href="{{ route('customer.index') }}" class="rounded-full border border-[#d9c7b5] bg-white px-4 py-2 text-sm font-medium text-[#2d241d]">Home</a>
            </div>

            <div class="rounded-[1.8rem] border border-[#e7d9cb] bg-[#f9f4ef] p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#2d241d] text-xl font-semibold text-[#f7efe9]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-serif text-3xl text-[#2d241d]">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-[#5d4d45]">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[1.4rem] border border-[#eadcc9] bg-white p-4">
                        <div class="text-[10px] font-semibold uppercase tracking-[0.25em] text-[#7b675d]">Nama</div>
                        <div class="mt-2 text-lg font-semibold text-[#2d241d]">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="rounded-[1.4rem] border border-[#eadcc9] bg-white p-4">
                        <div class="text-[10px] font-semibold uppercase tracking-[0.25em] text-[#7b675d]">Email</div>
                        <div class="mt-2 text-lg font-semibold text-[#2d241d]">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('profile.edit') }}" class="rounded-full bg-[#2d241d] px-5 py-3 text-sm font-medium text-white">Edit Profil</a>
                    <a href="{{ route('customer.transactions') }}" class="rounded-full border border-[#d9c7b5] bg-white px-5 py-3 text-sm font-medium text-[#2d241d]">Riwayat Transaksi</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-[#d9c7b5] bg-white px-5 py-3 text-sm font-medium text-[#2d241d]">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
