<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#7d685f]">Pelanggan</p>
            <h1 class="mt-2 font-serif text-4xl text-[#2d241d]">Kelola Profil Pelanggan</h1>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-[#eadcc9] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#f0e3d8] text-left text-sm text-[#3f3129]">
                    <thead class="bg-[#f9f4ef] text-[#6f5d55]">
                        <tr>
                            <th class="px-4 py-3 font-medium">Nama</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium">Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0e3d8]">
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="px-4 py-3 font-medium text-[#2d241d]">{{ $customer->name }}</td>
                                <td class="px-4 py-3">{{ $customer->email }}</td>
                                <td class="px-4 py-3">{{ $customer->created_at?->format('d M Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-[#6f5d55]">Belum ada pelanggan yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
