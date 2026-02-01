<x-app-layout>
    {{-- Header Slot dengan Navigasi Kembali --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.courses.index') }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors w-fit">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali ke Daftar</span>
            </a>
            
            <div class="hidden md:block h-6 w-px bg-slate-300 mx-2"></div>
            
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Detail Kursus') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. KARTU INFORMASI UTAMA KURSUS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    
                    {{-- Kolom Kiri: Thumbnail --}}
                    <div class="w-full md:w-1/3 lg:w-1/4 relative bg-slate-100">
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover min-h-[240px]">
                        @else
                            <div class="w-full h-full min-h-[240px] flex items-center justify-center text-slate-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        {{-- Overlay Gradient Mobile Only --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent md:hidden"></div>
                        
                        {{-- Badge Kategori di atas gambar (Mobile) --}}
                        <div class="absolute bottom-4 left-4 md:hidden">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-indigo-600 text-white shadow-sm uppercase tracking-wide">
                                {{ $course->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Detail Info --}}
                    <div class="w-full md:w-2/3 lg:w-3/4 p-6 md:p-8 flex flex-col">
                        
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            {{-- Badge Kategori (Desktop) --}}
                            <span class="hidden md:inline-flex px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wide">
                                {{ $course->category->name ?? 'Uncategorized' }}
                            </span>

                            {{-- Badge Tipe Akses --}}
                            @if($course->access_type == 'code')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Kode Akses: <span class="font-mono text-slate-800">{{ $course->access_code }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                    Akses Terbuka
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3 leading-tight">
                            {{ $course->title }}
                        </h1>
                        
                        {{-- [FIX] Menggunakan {!! !!} untuk render HTML dari WYSIWYG --}}
                        <div class="prose prose-sm prose-slate max-w-none text-slate-500 mb-8">
                            {!! $course->description !!}
                        </div>

                        {{-- Statistik Card Kecil --}}
                        <div class="mt-auto grid grid-cols-2 sm:grid-cols-3 gap-4 pt-6 border-t border-slate-100">
                            
                            {{-- Stat 1: Total Siswa --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Peserta</p>
                                    <p class="text-base font-bold text-slate-900">{{ $students->count() }} Orang</p>
                                </div>
                            </div>

                            {{-- Stat 2: Tanggal Dibuat --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Dibuat</p>
                                    <p class="text-base font-bold text-slate-900">{{ $course->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. TABEL DAFTAR PESERTA --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                {{-- Header Tabel --}}
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">Daftar Peserta</h3>
                            <p class="text-xs text-slate-500">Siswa yang terdaftar dalam kursus ini.</p>
                        </div>
                    </div>
                </div>

                {{-- Tabel Konten --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider w-16 text-center">#</th>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider">Nama Siswa</th>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider">Kontak</th>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider">Bergabung</th>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($students as $index => $student)
                            <tr class="hover:bg-slate-50 transition duration-150">
                                
                                {{-- Nomor --}}
                                <td class="px-6 py-4 text-center text-slate-400 font-medium">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Nama & Avatar --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @if($student->avatar)
                                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white border border-slate-200" src="{{ Storage::url($student->avatar) }}" alt="{{ $student->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-bold text-sm ring-2 ring-white border border-indigo-50">
                                                    {{ substr($student->name, 0, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $student->name }}</div>
                                            <div class="text-xs text-slate-400">ID: {{ $student->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $student->email }}
                                    </div>
                                </td>

                                {{-- Tanggal Join --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-slate-900 font-medium">
                                            {{ $student->pivot->joined_at ? \Carbon\Carbon::parse($student->pivot->joined_at)->format('d M Y') : '-' }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            {{ $student->pivot->joined_at ? \Carbon\Carbon::parse($student->pivot->joined_at)->format('H:i') . ' WIB' : '' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                        Aktif
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                        </div>
                                        <h3 class="text-slate-900 font-bold text-lg">Belum Ada Peserta</h3>
                                        <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">
                                            Kursus ini belum memiliki siswa yang terdaftar. Bagikan kode akses atau promosikan kursus ini untuk mendapatkan peserta.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>