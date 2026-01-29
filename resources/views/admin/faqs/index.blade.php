<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">Kelola FAQ (QNA)</h2>
            <a href="{{ route('admin.faqs.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-indigo-700">+ Tambah Tanya Jawab</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-3 px-4 font-bold text-gray-700">Pertanyaan</th>
                            <th class="py-3 px-4 font-bold text-gray-700">Jawaban</th>
                            <th class="py-3 px-4 font-bold text-gray-700 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faqs as $faq)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 align-top font-medium">{{ $faq->question }}</td>
                            <td class="py-3 px-4 align-top text-gray-600 text-sm max-w-lg">{{ Str::limit($faq->answer, 100) }}</td>
                            <td class="py-3 px-4 align-top text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">Edit</a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Hapus QNA ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900 font-bold text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>