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

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- BAGIAN 1: QUICK ACTIONS (MENU UTAMA) --}}
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
                        <p class="text-sm text-gray-500">Atur kategori untuk mengelompokkan kursus.</p>
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
                        <p class="text-sm text-gray-500">Pengaturan penerbitan sertifikat kursus.</p>
                    </div>
                </a>

            </div>

            {{-- 
                BAGIAN 1.5: UTILITIES (FAQ & USER MANAGEMENT)
                Desain: Slim Container (Grid 2 Kolom)
            --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
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
                    
                    {{-- TOMBOL DIPERBARUI: Link ke admin.users.index --}}
                    <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-sky-50 hover:text-sky-600 hover:border-sky-200 transition-all text-center">
                        Lihat User
                    </a>
                </div>

            </div>


            {{-- BAGIAN 2: STATISTIK & PENCARIAN --}}
            <div class="flex flex-col lg:flex-row gap-6 mb-8">
                
                {{-- Statistik Kecil --}}
                <div class="flex-1 grid grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
                        <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Kursus</span>
                        <span class="text-3xl font-extrabold text-gray-900 mt-1">{{ $courses->count() }}</span>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
                        <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Pengguna</span>
                        <span class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalUsers ?? 0 }}</span>
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="flex-[2]">
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 h-full flex items-center">
                        <form method="GET" action="{{ route('admin.courses.index') }}" class="w-full flex flex-col md:flex-row gap-3">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                    placeholder="Cari judul kursus...">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-gray-200">
                                    Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('admin.courses.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 transition">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 3: TABEL KURSUS --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Kursus</h3>
                    <div class="text-xs text-gray-500">Menampilkan {{ $courses->count() }} data terbaru</div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold w-24">Cover</th>
                                <th scope="col" class="px-6 py-4 font-bold">Detail Kursus</th>
                                <th scope="col" class="px-6 py-4 font-bold">Akses</th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">Statistik</th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($courses as $course)
                            <tr class="bg-white hover:bg-gray-50/80 transition duration-150 group">
                                
                                {{-- Thumbnail --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="h-16 w-24 rounded-lg overflow-hidden bg-gray-200 shadow-sm border border-gray-200 relative">
                                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition transform group-hover:scale-105 duration-500">
                                    </div>
                                </td>

                                {{-- Judul & Kategori --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="font-bold text-gray-900 text-base mb-1 leading-tight">{{ $course->title }}</div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $course->category->name }}
                                    </span>
                                </td>

                                {{-- Tipe Akses --}}
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

                                {{-- Statistik Siswa --}}
                                <td class="px-6 py-4 align-top text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-lg font-bold text-gray-900">{{ $course->students_count }}</span>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wide">Siswa</span>
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center items-center gap-2">
                                        
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
                                <td colspan="5" class="px-6 py-16 text-center">
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
            </div>

        </div>
    </div>
</x-app-layout>