<x-app-layout>
        <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.index') }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali</span>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight border-l border-slate-300 pl-4 ml-2">
                {{ __('Edit Course') }}
            </h2>
        </div>
    </x-slot>
    <div class="pb-10 bg-slate-50 min-h-screen">
        
        <form method="POST" action="{{ route('admin.courses.update', $course->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    {{-- ==================== KOLOM KIRI (UTAMA) ==================== --}}
                    <div class="lg:col-span-2 space-y-8">
                        
                        {{-- 1. Detail Kursus --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-5 border-b border-slate-100 pb-3">Informasi Umum</h3>
                            
                            <div class="space-y-5">
                                {{-- Judul --}}
                                <div>
                                    <x-input-label for="title" :value="__('Judul Kursus')" class="mb-1" />
                                    <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                                        class="block w-full px-4 py-2.5 text-base text-slate-900 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400 transition" 
                                        placeholder="Contoh: Master Laravel 11 dari Nol">
                                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                                </div>

                                {{-- Deskripsi dengan WYSIWYG --}}
                                <div class="prose max-w-none">
                                    <x-input-label for="description" :value="__('Deskripsi Lengkap')" class="mb-1" />
                                    <textarea name="description" id="description" rows="8" 
                                        class="block w-full px-4 py-3 text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400 transition">{{ old('description', $course->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        {{-- 2. Keypoints --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                                <h3 class="text-lg font-bold text-slate-800">Poin Pembelajaran</h3>
                                
                                {{-- TOMBOL TAMBAH BARIS (Style Konsisten) --}}
                                <button type="button" onclick="addKeypoint()" 
                                    class="text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 px-3 py-1.5 rounded-lg transition flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Baris
                                </button>
                            </div>

                            <div id="keypoints-container" class="space-y-3">
                                {{-- 
                                    LOOP DATA LAMA DARI DATABASE
                                    Pastikan nama relasi di Model Course adalah 'keypoints' atau sesuaikan dengan nama kolom JSON Anda
                                --}}
                                @forelse($course->keypoints ?? [] as $keypoint)
                                    <div class="flex items-center gap-3 animate-fade-in-down">
                                        <div class="w-8 h-8 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center font-bold text-xs border border-slate-200 flex-shrink-0">{{ $loop->iteration }}</div>
                                        
                                        {{-- Cek apakah $keypoint berbentuk Object atau String --}}
                                        <input type="text" name="course_keypoints[]" value="{{ is_string($keypoint) ? $keypoint : $keypoint->name }}" 
                                            class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                                        
                                        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-red-500 p-2 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                @empty
                                    {{-- Tampilkan 1 Input Kosong Jika Data Kosong --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center font-bold text-xs border border-slate-200 flex-shrink-0">1</div>
                                        <input type="text" name="course_keypoints[]" placeholder="Contoh: Memahami konsep MVC..." 
                                            class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>
                                @endforelse
                            </div>
                            <p class="text-xs text-slate-400 mt-3">* Masukkan poin-poin utama yang akan dipelajari pengguna.</p>
                        </div>

                    </div>

                    {{-- ==================== KOLOM KANAN (SIDEBAR) ==================== --}}
                    <div class="lg:col-span-1 space-y-6">
                        
                        {{-- 1. Thumbnail --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
                            <h3 class="text-sm font-bold text-slate-900 mb-3 uppercase tracking-wider">Thumbnail</h3>
                            <div class="relative w-full h-44 rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-400 transition overflow-hidden group cursor-pointer" id="drop-area">
                                <input id="thumbnail" name="thumbnail" type="file" class="absolute inset-0 w-full h-full opacity-0 z-50 cursor-pointer" onchange="previewImage(event)">
                                
                                {{-- Placeholder (Sembunyikan jika gambar sudah ada) --}}
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 {{ $course->thumbnail ? 'hidden' : '' }}" id="placeholder">
                                    <svg class="w-8 h-8 text-slate-400 group-hover:text-indigo-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-medium text-slate-600">Klik untuk ganti gambar</span>
                                    <span class="text-[10px] text-slate-400 mt-1 block">Max 2MB (JPG/PNG)</span>
                                </div>

                                {{-- Preview Gambar Lama/Baru --}}
                                <img id="preview" src="{{ $course->thumbnail ? Storage::url($course->thumbnail) : '' }}" 
                                     class="absolute inset-0 w-full h-full object-cover {{ $course->thumbnail ? '' : 'hidden' }}" />
                            </div>
                            <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                        </div>

                        {{-- 2. Pengaturan Kursus (Grouped) --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 space-y-6">
                            
                            {{-- ============ CUSTOM DROPDOWN KATEGORI ============ --}}
                            {{-- Inisialisasi SelectedId dan SelectedName dari Database --}}
                            <div x-data="{
                                open: false,
                                search: '',
                                selectedId: '{{ old('category_id', $course->category_id) }}',
                                selectedName: '{{ $course->category ? $course->category->name : 'Pilih Kategori' }}',
                                categories: {{ $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toJson() }},
                                get filteredCategories() {
                                    if (this.search === '') return this.categories;
                                    return this.categories.filter(item => item.name.toLowerCase().includes(this.search.toLowerCase()));
                                }
                            }">
                                {{-- LABEL + TOMBOL BUAT BARU (Style Konsisten) --}}
                                <div class="flex items-center justify-between mb-2">
                                    <x-input-label :value="__('Kategori')" />
                                    
                                    <a href="{{ route('admin.categories.create') }}" 
                                       class="text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 px-3 py-1.5 rounded-lg transition flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Buat Baru
                                    </a>
                                </div>
                                
                                <input type="hidden" name="category_id" :value="selectedId">

                                <div class="relative">
                                    <button type="button" @click="open = !open" 
                                        class="relative w-full py-2.5 pl-3 pr-10 text-left bg-white border border-slate-300 rounded-lg cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm hover:border-slate-400 transition-colors"
                                        aria-haspopup="listbox" 
                                        :aria-expanded="open">
                                        <span class="block truncate font-medium" 
                                                :class="selectedId ? 'text-slate-900' : 'text-slate-500'"
                                                x-text="selectedName"></span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div x-show="open" @click.away="open = false" 
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden" 
                                         style="display: none;">
                                        
                                        <div class="sticky top-0 z-10 bg-white p-2 border-b border-slate-100">
                                            <input type="text" x-model="search" 
                                                class="block w-full px-3 py-2 text-sm border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 placeholder-slate-400" 
                                                placeholder="Cari kategori...">
                                        </div>

                                        <ul class="py-1 overflow-auto max-h-48">
                                            <template x-for="item in filteredCategories" :key="item.id">
                                                <li class="text-slate-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50 hover:text-indigo-900 transition-colors" 
                                                    @click="selectedId = item.id; selectedName = item.name; open = false; search = ''">
                                                    <span class="block truncate" :class="selectedId == item.id ? 'font-bold' : 'font-normal'" x-text="item.name"></span>
                                                    <span x-show="selectedId == item.id" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                    </span>
                                                </li>
                                            </template>
                                            <li x-show="filteredCategories.length === 0" class="text-slate-500 cursor-default select-none relative py-2 pl-3 pr-9 text-sm text-center">Tidak ditemukan.</li>
                                        </ul>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                            </div>
                            
                            <hr class="border-slate-100">

                            {{-- Aksesibilitas (Dengan Init Data dari DB) --}}
                            <div x-data="{ accessType: '{{ old('access_type', $course->access_type) }}' }">
                                <label class="text-sm font-bold text-slate-900 mb-3 block">Akses Kursus</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all"
                                        :class="accessType === 'open' ? 'border-indigo-600 bg-indigo-50' : 'border-slate-200 hover:border-slate-300'">
                                        <input type="radio" name="access_type" value="open" x-model="accessType" class="text-indigo-600 focus:ring-indigo-500">
                                        <div class="flex-1"><span class="block text-sm font-bold text-slate-900">Terbuka (Gratis)</span></div>
                                    </label>
                                    <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all"
                                        :class="accessType === 'code' ? 'border-indigo-600 bg-indigo-50' : 'border-slate-200 hover:border-slate-300'">
                                        <input type="radio" name="access_type" value="code" x-model="accessType" class="text-indigo-600 focus:ring-indigo-500">
                                        <div class="flex-1"><span class="block text-sm font-bold text-slate-900">Privat (Kode)</span></div>
                                    </label>
                                </div>
                                <div x-show="accessType === 'code'" x-collapse class="mt-3">
                                    <input type="text" name="access_code" value="{{ old('access_code', $course->access_code) }}" placeholder="Misal: KLS-VIP" 
                                        class="block w-full px-3 py-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 uppercase tracking-wide placeholder-slate-400">
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            {{-- Sertifikat (Dengan Init Data dari DB) --}}
                            <div x-data="{ certPolicy: '{{ old('certificate_policy', $course->certificate_policy) }}' }">
                                <label class="text-sm font-bold text-slate-900 mb-3 block">Penerbitan Sertifikat</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="certificate_policy" value="manual" x-model="certPolicy" class="peer sr-only">
                                        <div class="text-center px-2 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 peer-checked:bg-amber-50 peer-checked:text-amber-700 peer-checked:border-amber-300 transition select-none">Manual</div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="certificate_policy" value="auto" x-model="certPolicy" class="peer sr-only">
                                        <div class="text-center px-2 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 peer-checked:bg-green-50 peer-checked:text-green-700 peer-checked:border-green-300 transition select-none">Otomatis</div>
                                    </label>
                                </div>
                                <div class="mt-2 bg-slate-50 p-2 rounded text-[10px] text-slate-500 leading-tight border border-slate-100">
                                    <span x-show="certPolicy === 'manual'">Admin harus memverifikasi secara manual.</span>
                                    <span x-show="certPolicy === 'auto'">Sertifikat terbit otomatis saat selesai.</span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- ==================== STATIC ACTION BAR ==================== --}}
                <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col-reverse sm:flex-row justify-end items-center gap-3">
                    <a href="{{ route('admin.courses.index') }}" class="w-full sm:w-auto text-center px-6 py-3 rounded-lg border border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-50 transition">
                        Batal
                    </a>

                    <button type="submit" class="w-full sm:w-auto flex justify-center items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-sm transition shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </div>

        </form>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

    <script>
        // Init CKEditor untuk Deskripsi
        ClassicEditor
            .create(document.querySelector('#description'))
            .then(editor => {
                editor.ui.view.editable.element.style.minHeight = '300px';
            })
            .catch(error => { console.error(error); });

        function addKeypoint() {
            const container = document.getElementById('keypoints-container');
            const count = container.children.length + 1;
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 animate-fade-in-down';
            div.innerHTML = `
                <div class="w-8 h-8 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center font-bold text-xs border border-slate-200 flex-shrink-0">${count}</div>
                <input type="text" name="course_keypoints[]" placeholder="Poin pembelajaran selanjutnya..." class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-red-500 p-2 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
            `;
            container.appendChild(div);
        }

        function previewImage(event) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden'); // Sembunyikan placeholder jika ada preview baru
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
    <style>
        .animate-fade-in-down { animation: fadeInDown 0.3s ease-out; }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        
        .overflow-auto::-webkit-scrollbar { width: 6px; }
        .overflow-auto::-webkit-scrollbar-track { background: #f1f5f9; }
        .overflow-auto::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }

        /* Fix CKEditor Tailwind Reset */
        .ck-content ul { list-style-type: disc; padding-left: 1.5rem; }
        .ck-content ol { list-style-type: decimal; padding-left: 1.5rem; }
        .ck-content a { color: #4f46e5; text-decoration: underline; }
        .ck-editor__editable { 
            border-radius: 0 0 0.5rem 0.5rem !important; 
            min-height: 300px !important;
        }
        .ck-toolbar { border-radius: 0.5rem 0.5rem 0 0 !important; }
    </style>
    @endpush
</x-app-layout>
@include('layouts.footer') 