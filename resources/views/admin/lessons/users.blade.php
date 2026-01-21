<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hasil Tugas: {{ $lesson->title }}
            </h2>
            <a href="{{ route('admin.chapters.lessons.index', $lesson->chapter_id) }}" class="text-sm text-gray-500 hover:text-gray-700">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="font-bold text-lg mb-6">Daftar Pengumpulan User</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nama User</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3 text-center">Total Nilai</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-indigo-100 text-indigo-800 text-sm font-bold px-3 py-1 rounded-full">
                                        {{ $user->total_score }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Link ke Detail Koreksi --}}
                                    <a href="{{ route('admin.lessons.users.show', [$lesson->id, $user->id]) }}" class="font-medium text-blue-600 hover:underline">
                                        Koreksi / Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada user yang mengerjakan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>