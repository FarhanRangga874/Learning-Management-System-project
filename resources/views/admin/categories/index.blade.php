<x-app-layout>
    <x-slot name="header">
        {{-- Container Header: Mobile Flex Column, Desktop Flex Row --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            
            {{-- SISI KIRI: Tombol Kembali & Judul --}}
            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('admin.courses.index') }}" class="group flex flex-shrink-0 items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    {{-- Teks Kembali disembunyikan di HP sangat kecil, muncul di SM ke atas --}}
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                
                <div class="h-6 w-px bg-slate-300 mx-1 hidden sm:block"></div>
                
                <h2 class="font-bold text-xl text-gray-800 leading-tight truncate">
                    {{ __('Kelola Kategori') }}
                </h2>
            </div>

            {{-- SISI KANAN: Tombol Tambah (Full width di HP, Auto di Desktop) --}}
            <div class="w-full md:w-auto">
                <a href="{{ route('admin.categories.create') }}" 
                   class="flex justify-center items-center gap-2 w-full md:w-auto px-5 py-3 md:py-2.5 bg-indigo-600 border border-transparent rounded-xl md:rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kategori
                </a>
            </div>

        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tabel Wrapper --}}
            <div class="bg-white border border-gray-200 overflow-hidden shadow-sm rounded-xl sm:rounded-2xl">
                
                {{-- Header Tabel --}}
                <div class="px-4 md:px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-700 text-sm md:text-base">List Kategori</h3>
                    </div>
                    <span class="text-xs font-medium bg-white border border-gray-200 text-gray-500 px-3 py-1 rounded-full shadow-sm">
                        Total: {{ $categories->count() }}
                    </span>
                </div>

                {{-- Responsive Table Container --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                {{-- Hidden di Mobile --}}
                                <th scope="col" class="hidden md:table-cell px-6 py-4 font-bold tracking-wider w-16 text-center">#</th>
                                
                                <th scope="col" class="px-4 md:px-6 py-4 font-bold tracking-wider">Nama Kategori</th>
                                
                                {{-- Hidden di Mobile --}}
                                <th scope="col" class="hidden md:table-cell px-6 py-4 font-bold tracking-wider">Slug</th>
                                
                                <th scope="col" class="px-4 md:px-6 py-4 font-bold tracking-wider text-center md:w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($categories as $index => $category)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out group">
                                
                                {{-- Nomor (Hidden di Mobile) --}}
                                <td class="hidden md:table-cell px-6 py-4 text-center text-gray-400 font-medium">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Nama Kategori --}}
                                <td class="px-4 md:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                            {{ substr($category->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-900">{{ $category->name }}</span>
                                            {{-- Slug muncul di bawah nama KHUSUS di mobile --}}
                                            <span class="md:hidden text-xs text-gray-400 font-mono mt-0.5">{{ $category->slug }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Slug (Hidden di Mobile karena sudah ditaruh di bawah nama) --}}
                                <td class="hidden md:table-cell px-6 py-4">
                                    <code class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-mono border border-gray-200">
                                        {{ $category->slug }}
                                    </code>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 md:px-6 py-4 text-center">
                                    {{-- 
                                        LOGIKA RESPONSIVE TOMBOL:
                                        - md:opacity-0 : Di desktop transparan (hidden) kecuali hover.
                                        - opacity-100  : Di mobile SELALU terlihat (karena tidak ada hover).
                                    --}}
                                    <div class="flex items-center justify-center gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="p-2 rounded-full bg-yellow-50 text-yellow-600 hover:bg-yellow-100 border border-yellow-100 transition shadow-sm"
                                           title="Edit Kategori">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 rounded-full bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 transition shadow-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $category->name }}?')"
                                                    title="Hapus Kategori">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        </div>
                                        <h3 class="text-gray-900 font-medium text-lg">Belum ada kategori</h3>
                                        <p class="text-gray-500 text-sm mt-1 mb-4">Mulai dengan menambahkan kategori baru.</p>
                                        <a href="{{ route('admin.categories.create') }}" class="text-indigo-600 font-semibold hover:text-indigo-800 hover:underline">
                                            + Tambah Kategori Sekarang
                                        </a>
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