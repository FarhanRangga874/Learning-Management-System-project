<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hasil Tugas: {{ $lesson->title }}
            </h2>
            <a href="{{ route('admin.courses.chapters.index', $lesson->chapter_id) }}" class="text-sm text-gray-500 hover:text-gray-700 font-bold">&larr; Kembali ke Materi</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-slate-200">
                
                <h3 class="font-bold text-lg mb-6 text-slate-800">Daftar Pengumpulan Siswa</h3>

                @if($users->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4">Email</th>
                                    <th class="px-6 py-4 text-center">Total Nilai</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="bg-white border-b hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-indigo-100 text-indigo-800 text-sm font-bold px-3 py-1 rounded-full">
                                                {{ $user->total_score }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($user->grading_status == 'Sudah Dinilai')
                                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full">Selesai</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-1 rounded-full">Perlu Koreksi</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('admin.lessons.users.show', [$lesson->id, $user->id]) }}" 
                                               class="inline-flex items-center gap-1 text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-xs px-3 py-2 transition shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Koreksi
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-12 bg-slate-50 rounded-lg border border-dashed border-slate-300">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="mt-2 text-sm text-slate-500">Belum ada siswa yang mengumpulkan tugas ini.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>