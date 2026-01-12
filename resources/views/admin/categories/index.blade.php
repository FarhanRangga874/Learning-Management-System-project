<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Manage Categories') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Atur kategori untuk pengelompokan kursus.</p>
            </div>
            
            {{-- Tombol Tambah (Di Header) --}}
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md shadow-indigo-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tabel Kategori --}}
            <div class="bg-white border border-gray-200 overflow-hidden shadow-sm sm:rounded-2xl">
                
                {{-- Header Tabel --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-700">List Kategori</h3>
                    </div>
                    <span class="text-xs font-medium bg-white border border-gray-200 text-gray-500 px-3 py-1 rounded-full shadow-sm">
                        Total: {{ $categories->count() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider w-16 text-center">#</th>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider">Nama Kategori</th>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider">Slug</th>
                                <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($categories as $index => $category)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out group">
                                <td class="px-6 py-4 text-center text-gray-400 font-medium">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                            {{ substr($category->name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-900">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-mono border border-gray-200">
                                        {{ $category->slug }}
                                    </code>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
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
                                    {{-- Placeholder saat tidak hover agar layout tidak lompat (Mobile tetap muncul) --}}
                                    <div class="flex md:hidden items-center justify-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-yellow-600 font-medium">Edit</a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 font-medium">Hapus</button>
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