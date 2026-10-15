<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profil | {{ config('app.name', 'GlowCare') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f3ee] text-[#2a211b] antialiased">
    <main class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Akun Pembeli</p>
                <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Edit Profil</h1>
            </div>
            <a href="{{ route('customer.profile') }}" class="rounded-full border border-[#d9c7b5] bg-white px-4 py-2 text-sm font-medium">Kembali</a>
        </div>

        <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data" class="space-y-5 rounded-[1.8rem] border border-[#e7d9cb] bg-[#f9f4ef] p-6 shadow-sm sm:p-8">
            @csrf
            @method('PATCH')

            @if (session('status') === 'profile-updated')
                <p class="rounded-xl bg-green-50 px-4 py-3 text-sm text-green-800">Profil berhasil diperbarui.</p>
            @endif

            <div>
                <label for="name" class="mb-2 block text-sm font-medium">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" class="w-full rounded-xl border border-[#d9c7b5] bg-white px-4 py-3 focus:border-[#2d241d] focus:outline-none">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email" class="w-full rounded-xl border border-[#d9c7b5] bg-white px-4 py-3 focus:border-[#2d241d] focus:outline-none">
                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="foto" class="mb-2 block text-sm font-medium">Foto profil</label>
                <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-[#d9c7b5] bg-white px-4 py-3 text-sm">
                <p class="mt-1 text-xs text-[#7d685f]">Opsional, format JPG, PNG, atau WebP (maksimal 2 MB).</p>
                @error('foto') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('customer.profile') }}" class="rounded-full border border-[#d9c7b5] bg-white px-5 py-3 text-sm font-medium">Batal</a>
                <button type="submit" class="rounded-full bg-[#2d241d] px-5 py-3 text-sm font-medium text-white">Simpan Profil</button>
            </div>
        </form>
    </main>
</body>
</html>
