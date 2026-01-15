<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Courses') }}
            </h2>
            
            <div class="flex items-center gap-3">
                {{-- Tombol Tambah Kategori --}}
                <a href="{{ route('admin.categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Tambah Kategori
                </a>

                {{-- Tombol Buat Kursus Baru --}}
                <a href="{{ route('admin.courses.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Kursus Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
    {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Card Total Kursus --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-sm font-medium">Total Kursus</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $courses->count() }}</div>
                </div>

                {{-- Card Total Pengguna (DENGAN TOMBOL) --}}
{{-- Card Total Pengguna --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Total Pengguna</div>
                            
                            {{-- PERBAIKAN: Gunakan $totalUsers --}}
                            <div class="text-2xl font-bold text-gray-800">{{ $totalUsers ?? 0 }}</div>
                        </div>
                        
                        {{-- Tombol Lihat Detail --}}
                        <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition shadow-sm border border-green-100">
                            <span class="text-xs font-semibold hidden sm:block">Lihat Semua</span>
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- SEARCH BAR (PROFESIONAL) --}}
            <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-col md:flex-row gap-4 items-center">
                    
                    {{-- Input Field Wrapper --}}
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out" 
                            placeholder="Cari kursus berdasarkan judul...">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 focus:ring-4 focus:ring-indigo-300 shadow-sm">
                            Cari
                        </button>
                        
                        @if(request('search'))
                            <a href="{{ route('admin.courses.index') }}" class="w-full md:w-auto flex items-center justify-center px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel Kursus --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold w-20">Thumbnail</th>
                                <th scope="col" class="px-6 py-4 font-bold">Informasi Kursus</th>
                                <th scope="col" class="px-6 py-4 font-bold">Akses</th>
                                <th scope="col" class="px-6 py-4 font-bold">Total Siswa</th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($courses as $course)
                            <tr class="bg-white hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="h-12 w-20 rounded-2xl overflow-hidden bg-gray-200 shadow-sm relative group">
                                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition transform group-hover:scale-110">
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-base mb-1">{{ $course->title }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">
                                        {{ $course->category->name }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if($course->access_type == 'open')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                                            Open Access
                                        </span>
                                    @else
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-gray-500 rounded-full"></span>
                                                Private
                                            </span>
                                            <span class="text-xs text-gray-500 font-mono bg-gray-50 px-1 rounded border border-gray-200">Code: {{ $course->access_code }}</span>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center text-gray-900 font-bold">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ $course->students_count }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        
                                        <a href="{{ route('admin.courses.chapters.index', $course) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition border border-indigo-200" title="Kelola Materi">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </a>

                                        <a href="{{ route('admin.courses.show', $course) }}" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition border border-green-200" title="Lihat Siswa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>

                                        <a href="{{ route('admin.courses.edit', $course) }}" class="p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition border border-gray-200" title="Edit Kursus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-200" onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini secara permanen?')" title="Hapus Kursus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            {{-- Empty State dengan Logika Search --}}
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        
                                        @if(request('search'))
                                            <p class="text-gray-500 text-lg font-medium">Tidak ada kursus ditemukan.</p>
                                            <p class="text-gray-400 text-sm mb-4">Coba cari dengan kata kunci lain.</p>
                                            <a href="{{ route('admin.courses.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold hover:underline">
                                                Reset Pencarian
                                            </a>
                                        @else
                                            <p class="text-gray-500 text-lg font-medium">Belum ada kursus yang dibuat.</p>
                                            <p class="text-gray-400 text-sm mb-4">Mulai dengan membuat kursus pertama Anda.</p>
                                            <a href="{{ route('admin.courses.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold hover:underline">
                                                + Buat Kursus Sekarang
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