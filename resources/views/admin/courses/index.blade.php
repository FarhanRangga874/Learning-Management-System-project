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
            
            {{-- ======================================================= --}}
            {{-- SECTION 1: MENU & UTILITAS --}}
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

                {{-- Utilities --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 group hover:border-pink-200 transition-all">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="w-10 h-10 rounded-lg bg-pink-50 text-pink-500 flex items-center justify-center flex-shrink-0 group-hover:bg-pink-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Pusat Bantuan (FAQ)</h4>
                                <p class="text-xs text-gray-500">Atur pertanyaan umum.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.faqs.index') }}" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200 transition-all text-center">
                            Kelola FAQ
                        </a>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 group hover:border-sky-200 transition-all">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-500 flex items-center justify-center flex-shrink-0 group-hover:bg-sky-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Kelola Pengguna</h4>
                                <p class="text-xs text-gray-500">Lihat data pengguna.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-sky-50 hover:text-sky-600 hover:border-sky-200 transition-all text-center">
                            Lihat User
                        </a>
                    </div>
                </div>
            </div>

            {{-- ======================================================= --}}
            {{-- SECTION 2: ANALITIK & LAPORAN --}}
            {{-- ======================================================= --}}
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-lg font-bold text-gray-800">Laporan & Analitik</h3>
                </div>

                {{-- KPI Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pengguna baru ({{ ucfirst(request('range', 'month')) }})</p>
                            <h4 class="text-3xl font-black text-indigo-600 mt-2">{{ $totalEnrollmentsInPeriod }}</h4>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Kursus</p>
                            <h4 class="text-3xl font-black text-gray-800 mt-2">{{ $totalCourses }}</h4>
                        </div>
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>

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
                    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg">Trend Pendaftaran</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-3xl font-black text-indigo-600 leading-none">{{ $totalEnrollmentsInPeriod }}</span>
                                    <div class="flex flex-col leading-none">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Join</span>
                                        <span class="text-xs text-gray-500 font-medium">{{ request('range') == 'week' ? 'Minggu Ini' : (request('range') == 'year' ? 'Tahun Ini' : 'Bulan Ini') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                @php
                                    $filters = ['week' => 'Minggu', 'month' => 'Bulan', 'year' => 'Tahun'];
                                    $currentRange = request('range', 'month');
                                @endphp
                                @foreach($filters as $key => $label)
                                <a href="{{ route('admin.courses.index', array_merge(request()->all(), ['range' => $key])) }}" 
                                   class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ $currentRange == $key ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                                    {{ $label }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="relative h-72 w-full">
                            <canvas id="enrollmentChart"></canvas>
                        </div>
                    </div>

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

                {{-- REKAP KATEGORI (UPDATED: Chart Donat dengan Tombol Filter) --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    {{-- Tabel Statistik Kategori --}}
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-lg">Statistik Kategori</h4>
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
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                            {{ $categoryStats->appends(request()->except('cat_page'))->links() }} 
                        </div>
                    </div>
    
                    {{-- Chart Donat Interaktif --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center items-center">
                        <div class="w-full flex justify-between items-center mb-4">
                            <h4 class="font-bold text-gray-800 text-sm">Proporsi Kategori</h4>
                            
                            {{-- Tombol Filter Chart --}}
                            <div class="flex bg-slate-100 rounded-lg p-0.5" id="chartFilter">
                                <button onclick="updateChart('courses')" class="px-2 py-1 text-[10px] font-bold rounded-md transition-all bg-white text-indigo-600 shadow-sm filter-btn" data-type="courses">
                                    Kursus
                                </button>
                                <button onclick="updateChart('students')" class="px-2 py-1 text-[10px] font-bold rounded-md transition-all text-slate-500 hover:text-gray-800 filter-btn" data-type="students">
                                    Siswa
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
            </div>

            {{-- ======================================================= --}}
            {{-- SECTION 3: DAFTAR KURSUS --}}
            {{-- ======================================================= --}}
            <div>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Kursus</h3>
                    </div>

                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('admin.courses.index') }}" class="relative w-full md:w-96">
                        <input type="hidden" name="range" value="{{ request('range', 'month') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="block w-full pl-10 pr-24 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition" 
                                placeholder="Cari judul kursus...">
                            <div class="absolute right-1.5 top-1.5 bottom-1.5">
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

                {{-- Tabel Daftar Kursus & Laporan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 text-sm">Data Kursus & Performa</h3>
                        <div class="text-xs text-gray-500">Menampilkan {{ $courses->count() }} data terbaru</div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-bold w-24">Cover</th>
                                    <th scope="col" class="px-6 py-4 font-bold">Detail Kursus</th>
                                    <th scope="col" class="px-6 py-4 font-bold">Akses</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-center">Siswa</th>
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
                                    <td colspan="7" class="px-6 py-16 text-center">
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
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $courses->links() }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Script untuk render Chart --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // =================================================
            // 1. Chart Trend Pendaftaran (Line Chart)
            // =================================================
            const ctxEnrollment = document.getElementById('enrollmentChart').getContext('2d');
            const rawDataEnrollment = @json($enrollmentTrend);
            const labelsEnrollment = rawDataEnrollment.map(item => item.label); 
            const dataValuesEnrollment = rawDataEnrollment.map(item => item.count);

            new Chart(ctxEnrollment, {
                type: 'line',
                data: {
                    labels: labelsEnrollment,
                    datasets: [{
                        label: 'Siswa Bergabung',
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
                                    return context.parsed.y + ' Siswa Bergabung';
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

            // =================================================
            // 2. Chart Kategori Interaktif (Doughnut Chart)
            // =================================================
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            
            // Ambil data lengkap (bukan paginate) yang dikirim dari controller sebagai 'chartData'
            // Format data diharapkan: [{ name: 'Kategori A', courses_count: 10 }, ...]
            // Karena kita mengirim $chartData dari controller, kita pakai itu.
            // (Pastikan controller mengirim $chartData yang berisi join lengkap termasuk students_count & certificates_count jika ingin dinamis)
            // UPDATE CONTROLLER DULU agar $chartData memuat semua kolom count!
            
            // NOTE: Di controller sebelumnya $chartData hanya select courses_count. 
            // Agar tombol berfungsi, kita perlu $chartData mengandung students_count & certificates_count juga.
            // Mari asumsikan $chartData di controller sudah diupdate atau kita pakai data JS di bawah ini.
            
            // UNTUK SEMENTARA (jika controller belum update query chartData), kita pakai logika di view ini:
            // Kita butuh data mentah lengkap untuk chart. 
            // Solusi terbaik: Update Controller agar $chartData punya 3 count.
            
            // DATA DARI CONTROLLER (Pastikan controller mengirim data lengkap)
            // Jika Controller Anda belum update, chart hanya akan jalan untuk 'courses'.
            // Tapi kode JS ini dirancang untuk support ketiganya jika data tersedia.
            const chartDataRaw = @json($chartData); // Data semua kategori (tanpa pagination)

            // Siapkan array untuk masing-masing dataset
            const labels = chartDataRaw.map(item => item.name);
            const dataCourses = chartDataRaw.map(item => item.courses_count || 0);
            
            // Perlu update query controller untuk dapat students_count & certificates_count di $chartData
            // Jika belum ada, default 0 untuk demo
            const dataStudents = chartDataRaw.map(item => item.students_count || 0); 
            const dataCertificates = chartDataRaw.map(item => item.certificates_count || 0);

            // Palet warna
            const catColors = [
                '#6366f1', '#0ea5e9', '#8b5cf6', '#f59e0b', '#ec4899', '#10b981', '#3b82f6', '#f43f5e'
            ];

            let categoryChart = new Chart(ctxCategory, {
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

            // Fungsi Update Chart
            window.updateChart = function(type) {
                // Update tombol aktif
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    if(btn.dataset.type === type) {
                        btn.classList.remove('text-slate-500', 'hover:text-gray-800');
                        btn.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
                    } else {
                        btn.classList.add('text-slate-500', 'hover:text-gray-800');
                        btn.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                    }
                });

                // Update Data Chart
                if (type === 'courses') {
                    categoryChart.data.datasets[0].data = dataCourses;
                    document.getElementById('chartLabelInfo').innerText = 'Menampilkan proporsi berdasarkan Jumlah Kursus';
                } else if (type === 'students') {
                    categoryChart.data.datasets[0].data = dataStudents;
                    document.getElementById('chartLabelInfo').innerText = 'Menampilkan proporsi berdasarkan Total Siswa';
                } else if (type === 'certificates') {
                    categoryChart.data.datasets[0].data = dataCertificates;
                    document.getElementById('chartLabelInfo').innerText = 'Menampilkan proporsi berdasarkan Sertifikat';
                }
                
                categoryChart.update();
            };
        });
    </script>
    @endpush
</x-app-layout>

@include('layouts.footer')