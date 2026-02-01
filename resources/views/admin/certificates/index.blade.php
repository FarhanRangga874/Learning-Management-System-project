<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.index') }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali</span>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight border-l border-slate-300 pl-4 ml-2">
                {{ __('Kelola Sertifikat') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- === 1. SIDEBAR KIRI (NAVIGASI & PENGATURAN) === --}}
                <div class="w-full lg:w-64 flex-shrink-0">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 sticky top-24">
                        
                        {{-- Judul Sidebar --}}
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-gray-900 text-xs uppercase tracking-wider">Menu Kontrol</h3>
                        </div>

                        {{-- List Menu --}}
                        <div class="p-3 space-y-1">
                            
                            {{-- Menu 1: Daftar Permintaan (Halaman Aktif saat ini) --}}
                            <a href="#" class="flex items-center justify-between px-4 py-3 bg-indigo-50 text-indigo-700 rounded-xl font-bold text-sm transition group">
                                <div class="flex items-center gap-3">
                                    {{-- Icon List --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span>Permintaan</span>
                                </div>
                                {{-- Badge Counter --}}
                                @if($certificates->where('status', 'pending')->count() > 0)
                                    <span class="bg-indigo-200 text-indigo-800 py-0.5 px-2 rounded-full text-[10px]">{{ $certificates->where('status', 'pending')->count() }}</span>
                                @endif
                            </a>

                            {{-- Menu 2: Tombol Pengaturan (Desain Template) --}}
                            <a href="{{ route('admin.certificates.settings') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl font-medium text-sm transition group">
                                {{-- Icon Settings/Paint --}}
                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                <span>Desain Template</span>
                            </a>

                        </div>

                        {{-- Statistik Ringkas di Sidebar --}}
                        <div class="border-t border-gray-100 p-5 mt-2">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">Ringkasan Data</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Disetujui</span>
                                    <span class="font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md">{{ $certificates->where('status', 'approved')->count() }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Ditolak</span>
                                    <span class="font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md">{{ $certificates->where('status', 'rejected')->count() }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- === 2. KONTEN UTAMA (KANAN - TABEL) === --}}
                <div class="flex-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                        
                        <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">Daftar Permintaan</h3>
                                <p class="text-xs text-gray-500">Kelola persetujuan sertifikat siswa.</p>
                            </div>
                            
                            {{-- Search --}}
                            <div class="relative w-full sm:w-64">
                                <input type="text" placeholder="Cari siswa..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 font-bold">Siswa</th>
                                        <th class="px-6 py-4 font-bold">Kursus</th>
                                        <th class="px-6 py-4 font-bold">Tanggal</th>
                                        <th class="px-6 py-4 font-bold text-center">Status</th>
                                        <th class="px-6 py-4 font-bold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($certificates as $cert)
                                    <tr class="bg-white hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs border border-slate-200">
                                                    {{ substr($cert->user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900">{{ $cert->user->name }}</div>
                                                    <div class="text-xs text-gray-400">{{ $cert->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-900 font-medium truncate max-w-[200px]">{{ $cert->course->title }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-500 font-mono text-xs">{{ $cert->created_at->format('d/m/Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($cert->status == 'approved')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">APPROVED</span>
                                            @elseif($cert->status == 'rejected')
                                                <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">REJECTED</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100 animate-pulse">PENDING</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($cert->status == 'pending')
                                                <div class="flex justify-center gap-1">
                                                    <form action="{{ route('admin.certificates.update', $cert->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="p-1.5 text-green-600 hover:bg-green-100 rounded transition" title="Setujui">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.certificates.update', $cert->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-100 rounded transition" title="Tolak">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada data permintaan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100">{{ $certificates->links() }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
@include('layouts.footer') 