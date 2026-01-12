<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.courses.index') }}" class="p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 transition" title="Kembali ke Daftar Kursus">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manage Chapters') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Mengelola bab untuk kursus: <span class="font-bold text-indigo-600">{{ $course->title }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 text-sm font-medium">Total Bab</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $chapters->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center justify-between border border-gray-100">
                    <div>
                        <h3 class="font-bold text-gray-900">Tambah Bab Baru</h3>
                        <p class="text-xs text-gray-500">Buat struktur materi pembelajaran Anda.</p>
                    </div>
                    <a href="{{ route('admin.courses.chapters.create', $course) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/30">
                        + Buat Bab
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold">Judul Bab</th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">Tipe Fokus</th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">Jumlah Materi</th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($chapters as $chapter)
                            <tr class="bg-white hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-base">{{ $chapter->title }}</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100 capitalize">
                                        {{ $chapter->type ?? 'General' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-lg font-bold text-gray-800">{{ $chapter->lessons->count() ?? 0 }}</span>
                                        <span class="text-xs text-gray-400">Lessons</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        
                                        <a href="{{ route('admin.chapters.lessons.index', $chapter) }}" 
                                           class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition border border-blue-200" 
                                           title="Kelola Materi (Lessons)">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </a>

                                        <a href="{{ route('admin.courses.chapters.edit', [$course, $chapter]) }}" 
                                           class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition border border-yellow-200" 
                                           title="Edit Bab">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <form action="{{ route('admin.courses.chapters.destroy', [$course, $chapter]) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-200" 
                                                    onclick="return confirm('Hapus bab ini? Semua materi di dalamnya akan ikut terhapus.')" 
                                                    title="Hapus Bab">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        <p class="text-gray-500 text-lg font-medium">Belum ada Bab yang dibuat.</p>
                                        <p class="text-gray-400 text-sm mb-4">Mulai dengan menambahkan bab pertama untuk kursus ini.</p>
                                        <a href="{{ route('admin.courses.chapters.create', $course) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold hover:underline">
                                            + Tambah Bab Sekarang
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