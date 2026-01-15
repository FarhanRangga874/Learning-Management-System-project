<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.courses.chapters.index', $course) }}" class="p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Tambah Bab Baru') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Menambahkan bab untuk kursus: <span class="font-bold text-indigo-600">{{ $course->title }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- PERHATIKAN ACTION ROUTE-NYA: HANYA $course, TANPA $chapter --}}
            <form method="POST" action="{{ route('admin.courses.chapters.store', $course) }}">
                @csrf
                {{-- TIDAK ADA @method('PUT') KARENA INI CREATE --}}

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <div class="mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Detail Bab</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="title" :value="__('Judul Bab')" />
                            <div class="mt-2 relative">
                                {{-- PERHATIKAN VALUE: HANYA old('title'), JANGAN PAKAI $chapter->title --}}
                                <x-text-input id="title" class="block w-full" type="text" name="title" :value="old('title')" required autofocus placeholder="Contoh: Pengenalan" />
                            </div>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-8 mt-4 border-t border-gray-50">
                        <a href="{{ route('admin.courses.chapters.index', $course) }}" class="text-gray-600 font-bold hover:text-gray-900">Batal</a>
                        <x-primary-button>Simpan Bab</x-primary-button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>