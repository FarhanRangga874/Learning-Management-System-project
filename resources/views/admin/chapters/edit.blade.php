<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.courses.chapters.index', $course) }}" class="p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Bab') }}: {{ $chapter->title }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kursus: <span class="font-bold text-indigo-600">{{ $course->title }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ route('admin.courses.chapters.update', [$course, $chapter]) }}">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    
                    <div class="mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Perbarui Detail Bab</h3>
                        <p class="text-sm text-gray-500">Ubah nama bab jika diperlukan.</p>
                    </div>

                    <div class="space-y-6">
                        
                        <div>
                            <x-input-label for="title" :value="__('Judul Bab')" class="text-gray-700 font-semibold" />
                            <div class="mt-2 relative">
                                <x-text-input id="title" class="block w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pl-4 pr-10 py-3" 
                                              type="text" name="title" :value="old('title', $chapter->title)" required autofocus 
                                              placeholder="Contoh: Pengenalan, Instalasi, atau Konsep Dasar" />
                                
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-2">
                                Pastikan judul menggambarkan isi materi yang ada di dalamnya.
                            </p>
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-4 pt-8 mt-4 border-t border-gray-50">
                        <a href="{{ route('admin.courses.chapters.index', $course) }}" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <span>Update Bab</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-app-layout>