<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">Edit QNA</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan</label>
                        <input type="text" name="question" value="{{ $faq->question }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jawaban</label>
                        <textarea name="answer" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ $faq->answer }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Urutan</label>
                        <input type="number" name="ordering" value="{{ $faq->ordering }}" class="w-20 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.faqs.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-bold">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold hover:bg-indigo-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>