<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Materi: ') }} {{ $lesson->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.chapters.lessons.update', [$chapter, $lesson]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div x-data="{ 
                        type: '{{ old('type', $lesson->type) }}', 
                        videoSource: '{{ old('video_source', $lesson->video_source ?? 'upload') }}' 
                    }">

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Materi')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $lesson->title)" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="type" :value="__('Tipe Materi')" />
                            <select x-model="type" name="type" id="type" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="video">Video (Upload/Youtube)</option>
                                <option value="text">Teks Bacaan</option>
                                <option value="pdf">Dokumen PDF</option>
                            </select>
                        </div>

                        <div x-show="type === 'video'" x-transition class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                            <h3 class="font-bold text-indigo-700 mb-3 text-sm uppercase">Pengaturan Video</h3>
                            
                            <div class="flex gap-6 mb-4">
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
                                <x-input-label for="video_file" :value="__('File Video (MP4)')" />
                                
                                @if($lesson->type == 'video' && $lesson->video_source == 'upload' && $lesson->file_path)
                                    <div class="mb-2 p-2 bg-indigo-100 text-indigo-700 text-xs rounded border border-indigo-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Video sudah terupload. Biarkan kosong jika tidak ingin mengganti.</span>
                                    </div>
                                @endif

                                <input type="file" name="video_file" id="video_file" accept="video/mp4,video/quicktime" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-600 file:text-white
                                hover:file:bg-indigo-700 mt-1 cursor-pointer">
                                <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file tergantung server (misal: 50MB).</p>
                                <x-input-error :messages="$errors->get('video_file')" class="mt-2" />
                            </div>

                            <div x-show="videoSource === 'youtube'">
                                <x-input-label for="video_url" :value="__('Youtube URL')" />
                                <x-text-input id="video_url" class="block mt-1 w-full" type="text" name="video_url" 
                                    :value="old('video_url', ($lesson->type == 'video' && $lesson->video_source == 'youtube' ? $lesson->file_path : ''))" 
                                    placeholder="Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ" />
                                <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                            </div>
                        </div>

                        <div x-show="type === 'pdf'" x-transition class="mb-6 p-4 bg-red-50 border border-red-100 rounded-lg">
                            <h3 class="font-bold text-red-700 mb-3 text-sm uppercase">Upload Dokumen</h3>
                            
                            <div class="mb-4">
                                <x-input-label for="pdf_file" :value="__('File PDF')" />

                                @if($lesson->type == 'pdf' && $lesson->file_path)
                                    <div class="mb-2 p-2 bg-red-100 text-red-700 text-xs rounded border border-red-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <span>PDF sudah tersimpan. <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="underline font-bold">Lihat File</a>. Biarkan kosong jika tidak ingin mengganti.</span>
                                    </div>
                                @endif

                                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-red-600 file:text-white
                                hover:file:bg-red-700 mt-1 cursor-pointer">
                                <p class="text-xs text-gray-500 mt-1">Format wajib .pdf (Maksimal 10MB).</p>
                                <x-input-error :messages="$errors->get('pdf_file')" class="mt-2" />
                            </div>
                        </div>

                        <div x-show="type === 'text'" x-transition>
                            <div class="mb-4">
                                <x-input-label for="content" :value="__('Isi Konten Bacaan')" />
                                <div class="mt-1">
                                    <textarea id="content" name="content" class="block w-full border-gray-300 rounded-md shadow-sm">{{ old('content', $lesson->content) }}</textarea>
                                </div>
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>
                        </div>

                    </div> 
                    
                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>{{ __('Perbarui Materi') }}</x-primary-button>
                        <a href="{{ route('admin.chapters.lessons.index', $chapter) }}" class="text-gray-600 hover:text-gray-900 text-sm">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

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