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
            
            {{-- Header Title --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Buat Materi Baru</h1>
                <p class="text-slate-500 text-sm mt-1">Tambahkan konten pembelajaran ke dalam bab <strong>{{ $chapter->title }}</strong>.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- KOLOM KIRI (FORM) --}}
                <div class="lg:col-span-8">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        
                        <form method="POST" action="{{ route('admin.chapters.lessons.store', $chapter->id) }}" enctype="multipart/form-data" class="p-6">
                            @csrf

                            {{-- Alpine Data --}}
                            <div x-data="{ 
                                type: '{{ old('type', $type ?? 'video') }}', 
                                videoSource: 'upload',
                                questions: [],
                                addQuestion() {
                                    this.questions.push({ text: '', type: 'multiple_choice', points: 10, options: {A:'', B:'', C:'', D:''}, correct_answer: 'A' });
                                    this.$nextTick(() => {
                                        const questionContainer = document.getElementById('questions-container');
                                        if(questionContainer && questionContainer.lastElementChild) {
                                            questionContainer.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        }
                                    });
                                }
                            }">

                                {{-- 1. Judul Materi --}}
                                <div class="mb-6">
                                    <x-input-label for="title" :value="__('Judul Materi')" class="mb-1" />
                                    <x-text-input id="title" class="block w-full" type="text" name="title" :value="old('title')" placeholder="Contoh: Pengenalan Dasar" required autofocus />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                {{-- 2. Tipe Konten (Visual Cards) --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-bold text-slate-700 mb-3">Tipe Konten</label>
                                    <input type="hidden" name="type" x-model="type">
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        {{-- Video --}}
                                        <div @click="type = 'video'" class="cursor-pointer p-4 rounded-xl border-2 transition-all duration-200 flex flex-col items-center gap-2" 
                                             :class="type === 'video' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-slate-100 hover:border-indigo-200 text-slate-600'">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="text-xs font-bold">Video</span>
                                        </div>
                                        
                                        {{-- Artikel --}}
                                        <div @click="type = 'text'" class="cursor-pointer p-4 rounded-xl border-2 transition-all duration-200 flex flex-col items-center gap-2" 
                                             :class="type === 'text' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-slate-100 hover:border-indigo-200 text-slate-600'">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="text-xs font-bold">Artikel</span>
                                        </div>

                                        {{-- PDF --}}
                                        <div @click="type = 'pdf'" class="cursor-pointer p-4 rounded-xl border-2 transition-all duration-200 flex flex-col items-center gap-2" 
                                             :class="type === 'pdf' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-slate-100 hover:border-indigo-200 text-slate-600'">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs font-bold">PDF</span>
                                        </div>

                                        {{-- Tugas --}}
                                        <div @click="type = 'assignment'" class="cursor-pointer p-4 rounded-xl border-2 transition-all duration-200 flex flex-col items-center gap-2" 
                                             :class="type === 'assignment' ? 'border-purple-600 bg-purple-50 text-purple-700' : 'border-slate-100 hover:border-purple-200 text-slate-600'">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            <span class="text-xs font-bold">Tugas</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Area Input Dinamis --}}
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-5 mb-6">
                                    
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
                                            <input type="file" name="video_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50 file:border-slate-200 transition cursor-pointer">
                                            <p class="text-xs text-slate-400 mt-2">Maksimal ukuran file sesuai konfigurasi server.</p>
                                        </div>

                                        <div x-show="videoSource === 'youtube'">
                                            <x-text-input class="block w-full" type="text" name="video_url" placeholder="https://youtube.com/watch?v=..." />
                                        </div>

                                        {{-- [FIX] Menambahkan Kolom Deskripsi Video Disini --}}
                                        <div class="mt-4">
                                            <x-input-label for="video_description" :value="__('Deskripsi Video (Opsional)')" />
                                            <textarea id="video_description" name="video_description" rows="4"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                placeholder="Tuliskan ringkasan atau catatan penting tentang video ini...">{{ old('video_description') }}</textarea>
                                        </div>
                                    </div>

                                    {{-- PDF Input --}}
                                    <div x-show="type === 'pdf'" x-transition style="display: none;">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Upload File PDF</label>
                                        <input type="file" name="pdf_file" accept="application/pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-red-700 hover:file:bg-red-50 file:border-slate-200 transition cursor-pointer">
                                    </div>

                                    {{-- Text / Assignment Content --}}
                                    <div x-show="type === 'text' || type === 'assignment'" x-transition style="display: none;">
                                        <div x-show="type === 'assignment'" class="mb-3 p-3 bg-purple-50 text-purple-800 rounded-lg text-sm border border-purple-100 flex items-start gap-2">
                                            <svg class="w-5 h-5 text-purple-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <div>
                                                <strong>Instruksi Tugas:</strong> Jelaskan detail tugas di sini. Anda dapat menambahkan soal kuis di bagian bawah.
                                            </div>
                                        </div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2" x-text="type === 'assignment' ? 'Deskripsi Tugas' : 'Isi Artikel'"></label>
                                        <textarea id="content" name="content" class="block w-full border-gray-300 rounded-md shadow-sm h-40"></textarea>
                                    </div>

                                </div>

                                {{-- 4. Builder Soal (Assignment) --}}
                                <div x-show="type === 'assignment'" class="bg-white border border-purple-200 rounded-xl shadow-sm overflow-hidden mb-6" x-transition>
                                    
                                    {{-- Header Builder --}}
                                    <div class="bg-purple-50 px-6 py-4 border-b border-purple-100 flex justify-between items-center">
                                        <div>
                                            <h3 class="font-bold text-purple-900 text-lg">Kelola Soal Kuis</h3>
                                            <p class="text-xs text-purple-700">Tambahkan soal pilihan ganda atau essay.</p>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        
                                        {{-- Toggle Visibility Hasil --}}
                                        <div class="mb-6 flex items-center justify-between p-4 bg-white border border-slate-200 rounded-lg hover:border-indigo-200 transition-colors">
                                            <div>
                                                <h4 class="text-sm font-bold text-slate-800">Tampilkan Kunci Jawaban</h4>
                                                <p class="text-xs text-slate-500 mt-0.5">Jika aktif, siswa dapat melihat jawaban benar setelah selesai.</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="show_results" value="1" class="sr-only peer" checked>
                                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        </div>

                                        {{-- Daftar Soal --}}
                                        <div class="space-y-6">
                                            <template x-if="questions.length === 0">
                                                <div class="text-center py-8 border-2 border-dashed border-slate-200 rounded-xl">
                                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                    <p class="text-slate-400 text-sm font-medium">Belum ada soal yang ditambahkan.</p>
                                                </div>
                                            </template>
                                            
                                            <div id="questions-container" class="space-y-6">
                                                <template x-for="(q, index) in questions" :key="index">
                                                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 relative transition hover:border-purple-200 hover:shadow-sm">
                                                        
                                                        <div class="flex justify-between items-center mb-4">
                                                            <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-md border border-purple-200" x-text="'Soal #' + (index+1)"></span>
                                                            <button type="button" @click="questions.splice(index, 1)" class="text-red-500 hover:text-red-700 font-bold text-xs flex items-center gap-1 transition">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                Hapus
                                                            </button>
                                                        </div>

                                                        <div class="mb-4">
                                                            <label class="block text-xs font-bold text-slate-500 mb-1">Pertanyaan</label>
                                                            <input type="text" :name="'questions['+index+'][text]'" x-model="q.text" class="w-full text-sm border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="Tulis pertanyaan di sini..." required>
                                                        </div>
                                                        
                                                        <div class="flex gap-4 mb-4 items-end">
                                                            <div class="w-1/2">
                                                                <label class="block text-xs font-bold text-slate-500 mb-1">Jenis Soal</label>
                                                                <select :name="'questions['+index+'][type]'" x-model="q.type" class="w-full text-sm border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                                                    <option value="multiple_choice">Pilihan Ganda</option>
                                                                    <option value="essay">Essay</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="w-1/2">
                                                                <label class="block text-xs font-bold text-slate-500 mb-1">Bobot Poin</label>
                                                                <div x-show="q.type === 'essay'">
                                                                    <input type="number" :name="'questions['+index+'][points]'" x-model="q.points" class="w-full text-sm border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-center font-bold text-purple-700" placeholder="Contoh: 20">
                                                                    <p class="text-[10px] text-slate-400 mt-1">*Wajib isi untuk Essay</p>
                                                                </div>
                                                                <div x-show="q.type === 'multiple_choice'">
                                                                    <div class="w-full h-[38px] bg-slate-100 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 text-xs font-bold italic cursor-not-allowed select-none">
                                                                        Auto Calculated
                                                                    </div>
                                                                    <p class="text-[10px] text-indigo-400 mt-1">*Otomatis dari sisa bobot</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div x-show="q.type === 'multiple_choice'" class="bg-white p-4 rounded-lg border border-slate-200">
                                                            <label class="block text-xs font-bold text-slate-500 mb-3">Opsi Jawaban & Kunci</label>
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                <template x-for="opt in ['A', 'B', 'C', 'D']">
                                                                    <div class="flex items-center gap-2">
                                                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold text-xs shrink-0" x-text="opt"></div>
                                                                        <input type="text" :name="'questions['+index+'][options]['+opt+']'" x-model="q.options[opt]" 
                                                                            class="w-full text-sm border-slate-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" :placeholder="'Jawaban ' + opt">
                                                                        <div class="shrink-0" title="Tandai sebagai jawaban benar">
                                                                            <input type="radio" :name="'questions['+index+'][correct_answer]'" :value="opt" x-model="q.correct_answer" class="text-purple-600 focus:ring-purple-500 w-4 h-4 cursor-pointer">
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <p class="text-[10px] text-slate-400 mt-2 italic text-right">*Pilih radio button di kanan untuk kunci jawaban.</p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <button type="button" @click="addQuestion()" 
                                                class="w-full py-4 rounded-xl border-2 border-dashed border-purple-200 text-purple-600 font-bold hover:bg-purple-50 hover:border-purple-300 transition flex flex-col items-center justify-center gap-2 group">
                                                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                </div>
                                                <span>Tambah Soal Baru</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Footer Tombol --}}
                                <div class="mt-8 pt-6 border-t border-slate-200 flex items-center gap-4">
                                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200">
                                        {{ __('Simpan Materi') }}
                                    </x-primary-button>
                                    <a href="{{ route('admin.courses.chapters.index', $course->id) }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-50 transition">
                                        Batal
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- SIDEBAR KANAN --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        {{-- Isi Sidebar (Tanpa Padding agar menempel) --}}
                        <div>
                             @include('admin.chapters.sidebar')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script CKEditor --}}
    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#content'))
            .then(editor => {
                editor.ui.view.editable.element.style.minHeight = '300px';
            })
            .catch(error => { console.error(error); });
    </script>
    <style>
        .ck-content ul { list-style-type: disc; padding-left: 1.5rem; }
        .ck-content ol { list-style-type: decimal; padding-left: 1.5rem; }
    </style>
    @endpush
</x-app-layout>
@include('layouts.footer') 