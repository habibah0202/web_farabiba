<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Pelanggan</p>
            <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Kelola Profil Pelanggan</h1>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        <div class="overflow-hidden rounded-[2rem] border border-[#eadcc9] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#f0e3d8] text-left text-sm text-[#3f3129]">
                    <thead class="bg-[#f9f4ef] text-[#6f5d55]">
                        <tr>
                            <th class="px-4 py-3 font-medium">Nama</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium">Terdaftar</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 text-right font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0e3d8]">
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="px-4 py-3 font-medium text-[#2d241d]">{{ $customer->name }}</td>
                                <td class="px-4 py-3">{{ $customer->email }}</td>
                                <td class="px-4 py-3">{{ $customer->created_at?->format('d M Y') ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $customer->is_suspended ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $customer->is_suspended ? 'Disuspend' : 'Aktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('admin.customers.suspension', $customer) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg border border-[#d9c5b4] px-3 py-1.5 text-xs font-medium text-[#2d241d]">
                                                {{ $customer->is_suspended ? 'Aktifkan' : 'Suspend' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Hapus akun {{ addslashes($customer->name) }}? Riwayat pesanan tetap tersimpan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-[#6f5d55]">Belum ada pelanggan yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
