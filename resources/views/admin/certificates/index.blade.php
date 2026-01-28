<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Kelola Sertifikat') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Tinjau dan setujui permintaan sertifikat siswa.</p>
            </div>
            
            {{-- Statistik Ringkas (Opsional) --}}
            <div class="flex gap-4">
                <div class="text-right">
                    <div class="text-xs text-gray-400 font-bold uppercase">Pending</div>
                    <div class="text-xl font-bold text-yellow-600">{{ $certificates->where('status', 'pending')->count() }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400 font-bold uppercase">Disetujui</div>
                    <div class="text-xl font-bold text-green-600">{{ $certificates->where('status', 'approved')->count() }}</div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                
                {{-- Table Header / Filter --}}
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-gray-800 text-lg">Permintaan Terbaru</h3>
                    
                    {{-- Search / Filter Placeholder --}}
                    <div class="relative w-full sm:w-64">
                        <input type="text" placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 font-bold">Siswa</th>
                                <th class="px-6 py-4 font-bold">Kursus Diselesaikan</th>
                                <th class="px-6 py-4 font-bold">Tanggal Request</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($certificates as $cert)
                            <tr class="bg-white hover:bg-gray-50 transition duration-150">
                                
                                {{-- Kolom Siswa --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                            {{ substr($cert->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $cert->user->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $cert->user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom Kursus --}}
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-medium">{{ $cert->course->title }}</div>
                                    <span class="text-xs text-gray-400">{{ $cert->course->category->name ?? 'Umum' }}</span>
                                </td>

                                {{-- Kolom Tanggal --}}
                                <td class="px-6 py-4">
                                    <div class="text-gray-900">{{ $cert->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $cert->created_at->format('H:i') }} WIB</div>
                                </td>

                                {{-- Kolom Status --}}
                                <td class="px-6 py-4 text-center">
                                    @if($cert->status == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Approved
                                        </span>
                                    @elseif($cert->status == 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Aksi --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        @if($cert->status == 'pending')
                                            {{-- Tombol Approve --}}
                                            <form action="{{ route('admin.certificates.update', $cert->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition border border-green-200" title="Setujui">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>

                                            {{-- Tombol Reject --}}
                                            <form action="{{ route('admin.certificates.update', $cert->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-200" title="Tolak">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Selesai</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-gray-900 font-medium text-lg">Belum ada permintaan</h3>
                                        <p class="text-gray-500 text-sm mt-1">Saat ini belum ada siswa yang mengajukan sertifikat.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $certificates->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>