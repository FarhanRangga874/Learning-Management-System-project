<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">Tambah QNA Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.faqs.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan</label>
                        <input type="text" name="question" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: Apakah kursus ini gratis?">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jawaban</label>
                        <textarea name="answer" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Tulis jawaban lengkap di sini..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Urutan (Opsional)</label>
                        <input type="number" name="ordering" class="w-20 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="0">
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.faqs.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-bold">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold hover:bg-indigo-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>