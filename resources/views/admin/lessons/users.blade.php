<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.assignments', $lesson->chapter->course_id) }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali ke Daftar Tugas</span>
            </a>
        </div>
    </x-slot>

    <div class="pb-24 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            {{-- Header & Stats --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Hasil Pengumpulan Tugas</h1>
                        <p class="text-slate-500 text-sm mt-1">Materi: <strong>{{ $lesson->title }}</strong></p>
                    </div>
                    
                    {{-- [FIX] Filter Dropdown Berfungsi --}}
                    <div class="flex items-center gap-2">
                        <form method="GET" action="{{ route('admin.lessons.users.index', $lesson->id) }}">
                            <div class="flex items-center gap-2">
                                <select name="status" onchange="this.form.submit()" class="text-sm border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm cursor-pointer min-w-[150px]">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Perlu Koreksi</option>
                                    <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Sudah Dinilai</option>
                                </select>

                                {{-- Tombol Reset (Hanya muncul jika sedang filter) --}}
                                @if(request('status'))
                                    <a href="{{ route('admin.lessons.users.index', $lesson->id) }}" class="p-2 bg-white border border-slate-300 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition" title="Reset Filter">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Quick Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Total Siswa --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Total Mengumpulkan</p>
                            <p class="text-xl font-bold text-slate-900">{{ $users->total() }}</p>
                        </div>
                    </div>

                    {{-- Perlu Koreksi --}}
                    @php
                        // Hitung jumlah pending (bisa dari query controller untuk akurasi, tapi ini visual saja)
                        $pendingCount = $users->where('grading_status', 'Perlu Koreksi')->count(); 
                    @endphp
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Perlu Koreksi</p>
                            <p class="text-xl font-bold text-amber-600">{{ $pendingCount }}</p>
                        </div>
                    </div>

                    {{-- Rata-rata Nilai --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Rata-rata Kelas</p>
                            <p class="text-xl font-bold text-emerald-600">{{ number_format($users->avg('total_score'), 1) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                    <th class="px-6 py-4 font-semibold">Siswa</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status Koreksi</th>
                                    <th class="px-6 py-4 font-semibold text-center">Nilai Akhir</th>
                                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($users as $user)
                                    <tr class="hover:bg-indigo-50/30 transition duration-150">
                                        {{-- Kolom Siswa --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-sm uppercase">
                                                    {{ substr($user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-900">{{ $user->name }}</p>
                                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom Status --}}
                                        <td class="px-6 py-4 text-center">
                                            @if($user->grading_status == 'Sudah Dinilai')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 animate-pulse">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Perlu Koreksi
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Kolom Nilai --}}
                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex flex-col items-center">
                                                <span class="text-lg font-extrabold {{ $user->total_score >= 70 ? 'text-indigo-600' : 'text-slate-600' }}">
                                                    {{ $user->total_score }}
                                                </span>
                                                <span class="text-[10px] text-slate-400">Poin</span>
                                            </div>
                                        </td>

                                        {{-- Kolom Aksi --}}
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.lessons.users.show', [$lesson->id, $user->id]) }}" 
                                               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm
                                               {{ $user->grading_status == 'Sudah Dinilai' 
                                                  ? 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50' 
                                                  : 'bg-indigo-600 border border-indigo-600 text-white hover:bg-indigo-700 hover:shadow-md' }}">
                                                
                                                @if($user->grading_status == 'Sudah Dinilai')
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Lihat Detail
                                                @else
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    Koreksi Sekarang
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $users->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="py-16 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-slate-900 font-bold text-lg">Tidak ada data ditemukan</h3>
                        <p class="text-slate-500 text-sm mt-1">Belum ada siswa dengan status ini atau belum ada yang mengumpulkan.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
@include('layouts.footer') 