<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.chapters.index', $course->id) }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali ke Bab</span>
            </a>
        </div>
    </x-slot>

    <div class="pb-24 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Buat Materi Baru</h1>
                <p class="text-slate-500 text-sm mt-1">Tambahkan konten pembelajaran ke dalam bab <strong>{{ $chapter->title }}</strong>.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- ==================== KOLOM KIRI (8/12): FORM INPUT ==================== --}}
                <div class="lg:col-span-8">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        
                        {{-- Form Wrapper --}}
                        <form method="POST" action="{{ route('admin.chapters.lessons.store', $chapter->id) }}" enctype="multipart/form-data" class="p-6">
                            @csrf

                            {{-- Alpine Data --}}
                            <div x-data="{ 
                                type: '{{ old('type', 'video') }}', 
                                videoSource: '{{ old('video_source', 'upload') }}' 
                            }">

                                {{-- 1. Judul Materi --}}
                                <div class="mb-6">
                                    <x-input-label for="title" :value="__('Judul Materi')" class="mb-1" />
                                    <x-text-input id="title" class="block w-full" type="text" name="title" :value="old('title')" placeholder="Contoh: Pengenalan Dasar" required autofocus />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                {{-- 2. Tipe Materi (Visual Cards) --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-bold text-slate-700 mb-3">Tipe Konten</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        {{-- Video --}}
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="type" value="video" x-model="type" class="peer sr-only">
                                            <div class="p-3 rounded-lg border-2 border-slate-100 hover:border-indigo-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                                <svg class="w-6 h-6 text-slate-400 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="text-xs font-bold text-slate-600 peer-checked:text-indigo-800">Video</span>
                                            </div>
                                        </label>
                                        {{-- Text --}}
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="type" value="text" x-model="type" class="peer sr-only">
                                            <div class="p-3 rounded-lg border-2 border-slate-100 hover:border-indigo-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                                <svg class="w-6 h-6 text-slate-400 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span class="text-xs font-bold text-slate-600 peer-checked:text-indigo-800">Artikel</span>
                                            </div>
                                        </label>
                                        {{-- PDF --}}
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="type" value="pdf" x-model="type" class="peer sr-only">
                                            <div class="p-3 rounded-lg border-2 border-slate-100 hover:border-indigo-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                                <svg class="w-6 h-6 text-slate-400 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-xs font-bold text-slate-600 peer-checked:text-indigo-800">PDF</span>
                                            </div>
                                        </label>
                                        {{-- Assignment --}}
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="type" value="assignment" x-model="type" class="peer sr-only">
                                            <div class="p-3 rounded-lg border-2 border-slate-100 hover:border-indigo-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                                <svg class="w-6 h-6 text-slate-400 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                <span class="text-xs font-bold text-slate-600 peer-checked:text-indigo-800">Tugas</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- 3. Area Input Dinamis --}}
                                <div class="bg-slate-50 border border-slate-100 rounded-lg p-5">
                                    
                                    {{-- Video Input --}}
                                    <div x-show="type === 'video'" x-transition>
                                        <div class="flex gap-4 mb-4">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" x-model="videoSource" name="video_source" value="upload" class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm font-medium text-gray-700">Upload Video (MP4)</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" x-model="videoSource" name="video_source" value="youtube" class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm font-medium text-gray-700">Youtube Link</span>
                                            </label>
                                        </div>

                                        <div x-show="videoSource === 'upload'">
                                            <input type="file" name="video_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50 file:border file:border-slate-200 transition cursor-pointer">
                                            <p class="text-xs text-slate-400 mt-2">Maksimal ukuran file: 100MB (sesuaikan server).</p>
                                            <x-input-error :messages="$errors->get('video_file')" class="mt-2" />
                                        </div>

                                        <div x-show="videoSource === 'youtube'">
                                            <x-text-input class="block w-full" type="text" name="video_url" placeholder="https://youtube.com/watch?v=..." />
                                            <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                                        </div>
                                    </div>

                                    {{-- PDF Input --}}
                                    <div x-show="type === 'pdf'" x-transition style="display: none;">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Upload File PDF</label>
                                        <input type="file" name="pdf_file" accept="application/pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-red-700 hover:file:bg-red-50 file:border file:border-slate-200 transition cursor-pointer">
                                        <x-input-error :messages="$errors->get('pdf_file')" class="mt-2" />
                                    </div>

                                    {{-- Text / Assignment Input --}}
                                    <div x-show="type === 'text' || type === 'assignment'" x-transition style="display: none;">
                                        <div x-show="type === 'assignment'" class="mb-3 p-3 bg-purple-100 text-purple-800 rounded-md text-sm border border-purple-200">
                                            <strong>Info:</strong> Tuliskan instruksi tugas di bawah ini. Soal quiz dapat dikelola setelah materi berhasil disimpan.
                                        </div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2" x-text="type === 'assignment' ? 'Instruksi Tugas' : 'Isi Artikel'"></label>
                                        <textarea id="content" name="content" class="block w-full border-gray-300 rounded-md shadow-sm">{{ old('content') }}</textarea>
                                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                    </div>

                                </div>

                            </div> {{-- End Alpine Data --}}

                            {{-- Tombol Simpan (Di dalam form agar stabil) --}}
                            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center gap-4">
                                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                                    {{ __('Simpan Materi') }}
                                </x-primary-button>
                                <a href="{{ route('admin.courses.chapters.index', $course->id) }}" class="text-sm text-slate-600 hover:text-slate-900 font-medium">
                                    Batal
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

    {{-- ==================== KOLOM KANAN (4/12): SIDEBAR ==================== --}}
    <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
        
        {{-- 1. Info Konteks (Desain Baru: Minimalis & Profesional) --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
            <div class="flex gap-4 items-start">
                {{-- Thumbnail Kursus (Lebih Kecil & Rapi) --}}
                <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                    <img src="{{ Storage::url($course->thumbnail) }}" class="w-full h-full object-cover" alt="Thumbnail">
                </div>

                {{-- Text Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider mb-0.5">
                        Bab Saat Ini
                    </p>
                    <h3 class="text-sm font-bold text-slate-900 truncate" title="{{ $chapter->title }}">
                        {{ $chapter->title }}
                    </h3>
                    
                    {{-- Breadcrumb like info --}}
                    <div class="flex items-center gap-1.5 mt-1 text-slate-500">
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="text-xs truncate max-w-[180px]" title="{{ $course->title }}">
                            {{ $course->title }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Sidebar Include (Kurikulum) --}}
        @if(view()->exists('admin.chapters.sidebar'))
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Struktur Bab</h3>
                    <span class="text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded font-bold">{{ $course->chapters->count() }} Bab</span>
                </div>
                <div>
                    @include('admin.chapters.sidebar', ['course' => $course, 'chapters' => $course->chapters])
                </div>
            </div>
        @endif

    </div>

            </div>
        </div>
    </div>

    {{-- Script CKEditor --}}
    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .then(editor => {
                editor.ui.view.editable.element.style.minHeight = '300px';
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <style>
        .ck-content ul { list-style-type: disc; padding-left: 20px; }
        .ck-content ol { list-style-type: decimal; padding-left: 20px; }
    </style>
    @endpush
</x-app-layout>