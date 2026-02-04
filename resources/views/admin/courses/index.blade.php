<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Manage Courses') }}
            </h2>
            <div class="text-sm text-gray-500">
                Selamat datang kembali, {{ Auth::user()->name }}!
            </div>
        </div>
    </x-slot>

    {{-- Script Chart.js (CDN) --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- WRAPPER UTAMA UNTUK AJAX --}}
            <div id="main-ajax-wrapper" 
                 data-enrollment="{{ json_encode($enrollmentTrend) }}"
                 data-category="{{ json_encode($chartData) }}">

                {{-- ======================================================= --}}
                {{-- SECTION 1: MENU CEPAT --}}
                {{-- ======================================================= --}}
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-800">Menu Cepat</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        {{-- Card 1: Buat Kursus Baru --}}
                        <a href="{{ route('admin.courses.create') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-100 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                            <div class="relative z-10">
                                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <h3 class="font-bold text-lg text-gray-900 mb-1">Buat Kursus Baru</h3>
                                <p class="text-sm text-gray-500">Tambahkan materi kursus.</p>
                            </div>
                        </a>

                        {{-- Card 2: Kelola Kategori --}}
                        <a href="{{ route('admin.categories.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-100 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                            <div class="relative z-10">
                                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <h3 class="font-bold text-lg text-gray-900 mb-1">Kelola Kategori</h3>
                                <p class="text-sm text-gray-500">Atur kategori kursus.</p>
                            </div>
                        </a>

                        {{-- Card 3: Kelola Sertifikat --}}
                        <a href="{{ route('admin.certificates.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-yellow-100 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-yellow-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                            <div class="relative z-10">
                                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="font-bold text-lg text-gray-900 mb-1">Kelola Sertifikat</h3>
                                <p class="text-sm text-gray-500">Pengaturan sertifikat.</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- ======================================================= --}}
                {{-- SECTION 1.5: UTILITY (FAQ & USER) --}}
                {{-- ======================================================= --}}
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-pink-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-800">Utilitas Admin</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- 1. UTILITY: KELOLA FAQ --}}
                        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 group hover:border-pink-200 transition-all">
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <div class="w-10 h-10 rounded-lg bg-pink-50 text-pink-500 flex items-center justify-center flex-shrink-0 group-hover:bg-pink-100 transition-colors">
                                    {{-- Icon Question Mark --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">Pusat Bantuan (FAQ)</h4>
                                    <p class="text-xs text-gray-500">Atur pertanyaan umum di halaman depan.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.faqs.index') }}" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200 transition-all text-center">
                                Kelola FAQ
                            </a>
                        </div>
        
                        {{-- 2. UTILITY: KELOLA PENGGUNA --}}
                        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 group hover:border-sky-200 transition-all">
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-500 flex items-center justify-center flex-shrink-0 group-hover:bg-sky-100 transition-colors">
                                    {{-- Icon Users Group --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">Kelola Pengguna</h4>
                                    <p class="text-xs text-gray-500">Melihat detail pengguna</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-sky-50 hover:text-sky-600 hover:border-sky-200 transition-all text-center">
                                Lihat User
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ======================================================= --}}
                {{-- SECTION 2: DAFTAR KURSUS --}}
                {{-- ======================================================= --}}
                <div class="mb-10">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                        {{-- Header Tabel + Search Bar --}}
                        <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                                <h3 class="font-bold text-gray-800 text-lg">Daftar Kursus</h3>
                                <div class="text-xs text-gray-500 ml-2">({{ $courses->count() }} data)</div>
                            </div>

                            {{-- Search Bar --}}
                            <form method="GET" action="{{ route('admin.courses.index') }}" class="relative w-full md:w-80">
                                {{-- Hidden Input untuk menjaga range saat searching --}}
                                <input type="hidden" name="range" value="{{ request('range', 'month') }}">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                        class="block w-full pl-10 pr-24 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition" 
                                        placeholder="Cari judul kursus...">
                                    <div class="absolute right-1 top-1 bottom-1">
                                        <button type="submit" class="h-full px-4 bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold rounded-lg transition">
                                            Cari
                                        </button>
                                    </div>
                                </div>
                                @if(request('search'))
                                    <div class="mt-2 text-right">
                                        <a href="{{ route('admin.courses.index', ['range' => request('range', 'month')]) }}" class="text-xs text-red-500 hover:text-red-700 font-semibold">
                                            &times; Reset Pencarian
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                        
                        {{-- Tabel Data --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 font-bold w-24">Cover</th>
                                        <th scope="col" class="px-6 py-4 font-bold">Detail Kursus</th>
                                        {{-- [MODIFIKASI] Menambahkan Kolom Tanggal --}}
                                        <th scope="col" class="px-6 py-4 font-bold">Tanggal Dibuat</th>
                                        <th scope="col" class="px-6 py-4 font-bold">Akses</th>
                                        <th scope="col" class="px-6 py-4 font-bold text-center">User</th>
                                        <th scope="col" class="px-6 py-4 font-bold text-center">Kelulusan</th>
                                        <th scope="col" class="px-6 py-4 font-bold text-center">Nilai Avg</th>
                                        <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($courses as $course)
                                    <tr class="bg-white hover:bg-gray-50/80 transition duration-150 group">
                                        <td class="px-6 py-4 align-top">
                                            <div class="h-16 w-24 rounded-lg overflow-hidden bg-gray-200 shadow-sm border border-gray-200 relative">
                                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition transform group-hover:scale-105 duration-500">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-bold text-gray-900 text-base mb-1 leading-tight">{{ $course->title }}</div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $course->category->name }}
                                            </span>
                                        </td>
                                        {{-- [MODIFIKASI] Menampilkan Tanggal Y-m-d --}}
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-bold text-gray-800 text-sm">
                                                {{ $course->created_at->format('Y-m-d') }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 mt-1">
                                                {{ $course->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            @if($course->access_type == 'open')
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                    <span class="text-xs font-semibold text-gray-700">Terbuka</span>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-semibold text-gray-700">Privat</div>
                                                        <div class="text-[10px] text-gray-400 font-mono mt-0.5">Kode: {{ $course->access_code }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 align-top text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $course->students_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-top text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm font-bold {{ $course->completion_rate >= 50 ? 'text-green-600' : 'text-orange-500' }}">
                                                    {{ $course->completion_rate }}%
                                                </span>
                                                <span class="text-[10px] text-gray-400">({{ $course->certificates_count }} Lulus)</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top text-center">
                                             <span class="px-2 py-1 rounded text-xs font-bold 
                                                {{ $course->average_score >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $course->average_score }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-middle">
                                            <div class="flex justify-center items-center gap-2">
                                                <a href="{{ route('admin.courses.assignments', $course) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-purple-600 hover:bg-purple-50 hover:border-purple-200 transition shadow-sm" title="Lihat Tugas & Nilai">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                </a>
                                                <a href="{{ route('admin.courses.chapters.index', $course) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition shadow-sm" title="Kelola Materi">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                </a>
                                                <a href="{{ route('admin.courses.show', $course) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-green-600 hover:bg-green-50 hover:border-green-200 transition shadow-sm" title="Lihat Siswa">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                <a href="{{ route('admin.courses.edit', $course) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm" title="Edit Kursus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-200 transition shadow-sm" onclick="return confirm('Hapus kursus ini permanen?')" title="Hapus Kursus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        {{-- Update Colspan menjadi 8 karena ada kolom baru --}}
                                        <td colspan="8" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                </div>
                                                
                                                @if(request('search'))
                                                    <h3 class="text-gray-900 text-lg font-bold">Pencarian Tidak Ditemukan</h3>
                                                    <p class="text-gray-500 text-sm mt-2 mb-6 max-w-sm mx-auto">Kami tidak dapat menemukan kursus dengan kata kunci "<span class="font-bold">{{ request('search') }}</span>".</p>
                                                    <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">
                                                        Reset Filter
                                                    </a>
                                                @else
                                                    <h3 class="text-gray-900 text-lg font-bold">Belum Ada Kursus</h3>
                                                    <p class="text-gray-500 text-sm mt-2 mb-6 max-w-sm mx-auto">Anda belum membuat kursus apapun. Mulailah berbagi pengetahuan sekarang.</p>
                                                    <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                                                        + Buat Kursus Pertama
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 ajax-pagination">
                            {{ $courses->links() }}
                        </div>
                    </div>
                </div>

                {{-- ======================================================= --}}
                {{-- SECTION 3: ANALITIK & LAPORAN --}}
                {{-- ======================================================= --}}
                <div class="mb-10">
                    
                    {{-- HEADER & GLOBAL FILTER --}}
                    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Laporan & Analitik</h3>
                                {{-- [MODIFIKASI] Teks Tanggal di Header Laporan --}}
                                <p class="text-xs text-gray-500">
                                    Menampilkan data untuk: 
                                    <span class="font-bold text-indigo-600">
                                        @if(request('range') == 'today') 
                                            Hari Ini ({{ date('Y-m-d') }})
                                        @elseif(request('range') == 'week') 
                                            Minggu Ini
                                        @elseif(request('range') == 'year') 
                                            Tahun {{ date('Y') }}
                                        @elseif(request('range') == 'all') 
                                            Sepanjang Waktu
                                        @else 
                                            Bulan {{ date('Y-m') }} ({{ date('Y-m-d') }})
                                        @endif
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- GLOBAL FILTER BUTTONS --}}
                        <div class="bg-white p-1 rounded-xl shadow-sm border border-gray-200 flex">
                            @php
                                $filters = [
                                    'today' => 'Hari', 
                                    'week' => 'Minggu', 
                                    'month' => 'Bulan', 
                                    'year' => 'Tahun',
                                    'all' => 'Semua'
                                ];
                                $currentRange = request('range', 'month');
                            @endphp
                            @foreach($filters as $key => $label)
                            <a href="{{ route('admin.courses.index', array_merge(request()->except(['page', 'cat_page']), ['range' => $key])) }}" 
                               class="ajax-link px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200 
                                      {{ $currentRange == $key ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                                {{ $label }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- KPI Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        {{-- Card 1: User Baru --}}
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between relative overflow-hidden">
                            <div class="relative z-10">
                                <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">User Baru</p>
                                <h4 class="text-3xl font-black text-indigo-600 mt-2">{{ $totalEnrollmentsInPeriod }}</h4>
                            </div>
                            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                        </div>

                        {{-- Card 2: Total Kursus --}}
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Kursus</p>
                                <h4 class="text-3xl font-black text-gray-800 mt-2">{{ $totalCourses }}</h4>
                            </div>
                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                        </div>

                        {{-- Card 3: Total User --}}
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total User</p>
                                <h4 class="text-3xl font-black text-gray-800 mt-2">{{ $totalUsers ?? 0 }}</h4>
                            </div>
                            <div class="w-12 h-12 bg-sky-50 rounded-full flex items-center justify-center text-sky-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Chart Trend & Top Kursus --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        {{-- Chart Pendaftaran --}}
                        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg">Trend Pendaftaran</h4>
                                    <p class="text-xs text-gray-500">Visualisasi data siswa bergabung.</p>
                                </div>
                            </div>
                            <div class="relative h-72 w-full">
                                <canvas id="enrollmentChart"></canvas>
                            </div>
                        </div>

                        {{-- Top Kursus --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden h-full">
                            <div class="p-5 border-b border-gray-50 bg-gray-50/30">
                                <h4 class="font-bold text-gray-800 text-sm">Top Kursus</h4>
                                <p class="text-xs text-gray-500">Paling diminati periode ini.</p>
                            </div>
                            <div class="flex-1 overflow-y-auto p-2 max-h-[300px] lg:max-h-none">
                                @forelse($recapCourses as $index => $rc)
                                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-xl transition mb-1 group">
                                    <div class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center font-bold text-xs {{ $index < 3 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-sm font-bold text-gray-800 truncate" title="{{ $rc->title }}">{{ $rc->title }}</h5>
                                        <p class="text-[10px] text-gray-400 truncate">{{ $rc->category->name ?? 'Uncategorized' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-indigo-600 text-sm">{{ $rc->recent_students_count }}</div>
                                        <div class="text-[9px] text-gray-400 uppercase">Siswa</div>
                                    </div>
                                </div>
                                @empty
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                                    <span class="text-xs">Belum ada data.</span>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- REKAP KATEGORI --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        {{-- Tabel Statistik Kategori --}}
                        <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                                <h4 class="font-bold text-gray-800 text-lg">Statistik Kategori</h4>
                                <p class="text-xs text-gray-500 mt-1">Performa berdasarkan pengelompokan materi.</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-4 font-bold">Kategori</th>
                                            <th scope="col" class="px-6 py-4 font-bold text-center">Jumlah Kursus</th>
                                            <th scope="col" class="px-6 py-4 font-bold text-center">Total Siswa</th>
                                            <th scope="col" class="px-6 py-4 font-bold text-center">Sertifikat</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($categoryStats as $stat)
                                        <tr class="bg-white hover:bg-gray-50/80 transition duration-150">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $stat->name }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                    {{ $stat->courses_count }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="text-gray-700 font-semibold">{{ $stat->students_count }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="text-emerald-600 font-bold">{{ $stat->certificates_count }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">Belum ada data kategori.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Pagination Kategori --}}
                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 ajax-pagination">
                                {{ $categoryStats->appends(request()->except('cat_page'))->links() }} 
                            </div>
                        </div>
        
                        {{-- Chart Donat Interaktif --}}
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center items-center">
                            <div class="w-full flex justify-between items-center mb-4">
                                <h4 class="font-bold text-gray-800 text-sm">Proporsi Kategori</h4>
                                
                                <div class="flex bg-slate-100 rounded-lg p-0.5" id="chartFilter">
                                    <button onclick="updateChart('courses')" class="px-2 py-1 text-[10px] font-bold rounded-md transition-all bg-white text-indigo-600 shadow-sm filter-btn" data-type="courses">
                                        Kursus
                                    </button>
                                    <button onclick="updateChart('students')" class="px-2 py-1 text-[10px] font-bold rounded-md transition-all text-slate-500 hover:text-gray-800 filter-btn" data-type="students">
                                        User
                                    </button>
                                    <button onclick="updateChart('certificates')" class="px-2 py-1 text-[10px] font-bold rounded-md transition-all text-slate-500 hover:text-gray-800 filter-btn" data-type="certificates">
                                        Sertif
                                    </button>
                                </div>
                            </div>

                            <div class="relative w-full h-64">
                                <canvas id="categoryChart"></canvas>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="text-xs text-gray-400" id="chartLabelInfo">Menampilkan proporsi berdasarkan Jumlah Kursus</p>
                            </div>
                        </div>
                    </div>

                    {{-- STATISTIK GLOBAL & TIPS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        {{-- Card Rata-rata Kelulusan --}}
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rata-rata Kelulusan</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Semua Kursus</p>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <h4 class="text-4xl font-black text-gray-800">{{ $averageCompletionRate ?? 0 }}%</h4>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-3">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $averageCompletionRate ?? 0 }}%"></div>
                            </div>
                        </div>

                        {{-- Card Total Nilai Average --}}
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Nilai Avg</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Semua Kursus</p>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <h4 class="text-4xl font-black text-gray-800">{{ $overallAverageScore ?? 0 }}</h4>
                                <span class="text-sm text-gray-400">/ 100</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-3">
                                <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $overallAverageScore ?? 0 }}%"></div>
                            </div>
                        </div>

                        {{-- Info Tambahan / Tips --}}
                        <div class="bg-indigo-600 p-6 rounded-2xl shadow-lg shadow-indigo-200 text-white flex flex-col justify-center">
                            <h4 class="font-bold text-lg mb-2">Tips Pengelolaan</h4>
                            <p class="text-indigo-100 text-sm leading-relaxed mb-4">
                                Pantau terus performa kursus Anda. Kursus dengan kelulusan rendah mungkin perlu materi tambahan.
                            </p>
                            <a href="{{ route('admin.courses.create') }}" class="inline-block text-center px-4 py-2 bg-white text-indigo-700 text-xs font-bold rounded-lg hover:bg-indigo-50 transition w-auto self-start">
                                Buat Kursus Baru
                            </a>
                        </div>
                    </div>
                </div>

            </div> {{-- End #main-ajax-wrapper --}}

        </div>
    </div>

    {{-- Script untuk render Chart dan AJAX Logic --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Variabel Global untuk Chart Instances agar bisa di-destroy sebelum re-render
            let enrollmentChartInstance = null;
            let categoryChartInstance = null;

            // =================================================
            // 1. Fungsi Inisialisasi/Render Chart
            // =================================================
            function renderCharts() {
                // Ambil data dari pembungkus AJAX
                const wrapper = document.getElementById('main-ajax-wrapper');
                const rawDataEnrollment = JSON.parse(wrapper.dataset.enrollment);
                const chartDataRaw = JSON.parse(wrapper.dataset.category);

                // --- A. Chart Trend Pendaftaran ---
                const ctxEnrollment = document.getElementById('enrollmentChart').getContext('2d');
                const labelsEnrollment = rawDataEnrollment.map(item => item.label); 
                const dataValuesEnrollment = rawDataEnrollment.map(item => item.count);

                if(enrollmentChartInstance) {
                    enrollmentChartInstance.destroy();
                }

                enrollmentChartInstance = new Chart(ctxEnrollment, {
                    type: 'line',
                    data: {
                        labels: labelsEnrollment,
                        datasets: [{
                            label: 'User Bergabung',
                            data: dataValuesEnrollment,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4f46e5',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                titleFont: { size: 13 },
                                bodyFont: { size: 13 },
                                padding: 10,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' User Bergabung';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { stepSize: 1 },
                                grid: { borderDash: [2, 4], color: '#e5e7eb' }
                            },
                            x: { 
                                grid: { display: false } 
                            }
                        }
                    }
                });

                // --- B. Chart Kategori ---
                const ctxCategory = document.getElementById('categoryChart').getContext('2d');
                const labels = chartDataRaw.map(item => item.name);
                const dataCourses = chartDataRaw.map(item => item.courses_count || 0);
                const dataStudents = chartDataRaw.map(item => item.students_count || 0); 
                const dataCertificates = chartDataRaw.map(item => item.certificates_count || 0);
                const catColors = ['#6366f1', '#0ea5e9', '#8b5cf6', '#f59e0b', '#ec4899', '#10b981', '#3b82f6', '#f43f5e'];

                if(categoryChartInstance) {
                    categoryChartInstance.destroy();
                }

                categoryChartInstance = new Chart(ctxCategory, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: dataCourses, // Default view: Courses
                            backgroundColor: catColors,
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { usePointStyle: true, padding: 20, font: { size: 11, family: "'Inter', sans-serif" } }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) { label += ': '; }
                                        let value = context.parsed;
                                        let total = context.chart._metasets[context.datasetIndex].total;
                                        let percentage = Math.round((value / total) * 100) + '%';
                                        return label + value + ' (' + percentage + ')';
                                    }
                                }
                            }
                        },
                        cutout: '75%',
                        layout: { padding: 10 }
                    }
                });

                // --- Fungsi Update Chart Donat ---
                window.updateChart = function(type) {
                    document.querySelectorAll('.filter-btn').forEach(btn => {
                        if(btn.dataset.type === type) {
                            btn.classList.remove('text-slate-500', 'hover:text-gray-800');
                            btn.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
                        } else {
                            btn.classList.add('text-slate-500', 'hover:text-gray-800');
                            btn.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                        }
                    });

                    let newData = [];
                    let labelText = "";
                    let totalCount = 0;

                    if (type === 'courses') {
                        newData = dataCourses;
                        labelText = 'Menampilkan proporsi berdasarkan Jumlah Kursus';
                    } else if (type === 'students') {
                        newData = dataStudents;
                        labelText = 'Menampilkan proporsi berdasarkan Total User';
                    } else if (type === 'certificates') {
                        newData = dataCertificates;
                        labelText = 'Menampilkan proporsi berdasarkan Sertifikat';
                    }

                    totalCount = newData.reduce((a, b) => a + b, 0);

                    categoryChartInstance.data.datasets[0].data = newData;
                    categoryChartInstance.update();

                    document.getElementById('chartLabelInfo').innerText = labelText + ' (Total: ' + totalCount + ')';
                };
                
                let defaultTotal = dataCourses.reduce((a, b) => a + b, 0);
                document.getElementById('chartLabelInfo').innerText = 'Menampilkan proporsi berdasarkan Jumlah Kursus (Total: ' + defaultTotal + ')';
            }

            renderCharts();

            // =================================================
            // 2. Logic AJAX (Fetch tanpa refresh halaman)
            // =================================================
            const mainContent = document.getElementById('main-ajax-wrapper');

            document.addEventListener('click', function(e) {
                const targetLink = e.target.closest('.ajax-link') || e.target.closest('.ajax-pagination a');

                if (targetLink) {
                    e.preventDefault();
                    
                    const url = targetLink.getAttribute('href');
                    if(url && url !== '#') {
                        fetchContent(url);
                    }
                }
            });

            function fetchContent(url) {
                mainContent.style.opacity = '0.5';

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newContent = doc.getElementById('main-ajax-wrapper').innerHTML;
                        const newEnrollmentData = doc.getElementById('main-ajax-wrapper').dataset.enrollment;
                        const newCategoryData = doc.getElementById('main-ajax-wrapper').dataset.category;

                        mainContent.innerHTML = newContent;
                        mainContent.dataset.enrollment = newEnrollmentData;
                        mainContent.dataset.category = newCategoryData;

                        window.history.pushState({path: url}, '', url);
                        renderCharts();
                        mainContent.style.opacity = '1';
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        mainContent.style.opacity = '1';
                    });
            }

            window.addEventListener('popstate', function(e) {
                fetchContent(window.location.href);
            });
        });
    </script>
    @endpush
</x-app-layout>

@include('layouts.footer')