<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.courses.index') }}" class="p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Kursus') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-8">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-bold text-gray-900">Informasi Dasar</h3>
                            <p class="text-sm text-gray-500">Detail utama mengenai kursus ini.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="title" :value="__('Judul Kursus')" class="text-gray-700 font-semibold" />
                                <x-text-input id="title" class="block mt-2 w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" type="text" name="title" :value="old('title', $course->title)" required placeholder="Contoh: Master Laravel 11 dari Nol" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="category_id" :value="__('Kategori')" class="text-gray-700 font-semibold" />
                                <div class="relative mt-2">
                                    <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 appearance-none py-2.5 px-4 bg-white">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Deskripsi Singkat')" class="text-gray-700 font-semibold" />
                                <textarea name="description" id="description" rows="5" class="mt-2 w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-gray-700" placeholder="Jelaskan secara singkat apa yang akan dipelajari...">{{ old('description', $course->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-bold text-gray-900">Media & Visual</h3>
                            <p class="text-sm text-gray-500">Gambar sampul untuk menarik perhatian siswa.</p>
                        </div>

                        <div>
                            <x-input-label for="thumbnail" :value="__('Thumbnail Kursus')" class="text-gray-700 font-semibold mb-2" />
                            
                            <div class="flex items-start gap-6">
                                @if($course->thumbnail)
                                    <div class="shrink-0">
                                        <p class="text-xs text-gray-500 mb-2 font-medium">Saat Ini:</p>
                                        <img src="{{ Storage::url($course->thumbnail) }}" alt="Current Thumbnail" class="h-40 w-64 object-cover rounded-xl border border-gray-200 shadow-sm">
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <label class="block w-full cursor-pointer">
                                        <span class="sr-only">Choose file</span>
                                        <input id="thumbnail" type="file" name="thumbnail" class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          transition cursor-pointer
                                        "/>
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG. Disarankan rasio 16:9.</p>
                                    <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8" x-data="{ accessType: '{{ old('access_type', $course->access_type) }}' }">
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-bold text-gray-900">Aksesibilitas</h3>
                            <p class="text-sm text-gray-500">Siapa yang bisa mengakses kursus ini?</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="access_type" :value="__('Tipe Akses')" class="text-gray-700 font-semibold" />
                                <div class="mt-2 grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="access_type" value="open" x-model="accessType" class="peer sr-only">
                                        <div class="rounded-lg border border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 transition">
                                            <div class="font-bold text-sm">Open</div>
                                            <div class="text-xs text-gray-500 mt-1">Gratis untuk semua</div>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="access_type" value="code" x-model="accessType" class="peer sr-only">
                                        <div class="rounded-lg border border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                                            <div class="font-bold text-sm">Private</div>
                                            <div class="text-xs text-gray-500 mt-1">Butuh Kode Akses</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div x-show="accessType === 'code'" x-transition class="md:col-span-2">
                                <x-input-label for="access_code" :value="__('Kode Akses (Password)')" class="text-gray-700 font-semibold" />
                                <div class="mt-2 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <x-text-input id="access_code" class="pl-10 block w-full border-gray-300 rounded-lg" type="text" name="access_code" :value="old('access_code', $course->access_code)" placeholder="Masukkan kode unik..." />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div class="mb-6 border-b border-gray-100 pb-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Keypoints</h3>
                                <p class="text-sm text-gray-500">Poin-poin utama yang akan dipelajari siswa.</p>
                            </div>
                            <button type="button" onclick="addKeypoint()" class="text-sm bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg font-bold hover:bg-indigo-100 transition">
                                + Tambah Poin
                            </button>
                        </div>

                        <div id="keypoints-container" class="space-y-3">
                            @forelse($course->keypoints as $keypoint) 
                                <div class="flex gap-2 items-center group">
                                    <span class="text-gray-400 font-bold select-none">•</span>
                                    <input type="text" name="course_keypoints[]" value="{{ $keypoint->name }}" class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    
                                    @if(!$loop->first)
                                        <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 p-2 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <div class="flex gap-2 items-center">
                                    <span class="text-gray-400 font-bold select-none">•</span>
                                    <input type="text" name="course_keypoints[]" placeholder="Contoh: Memahami MVC pada Laravel" class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('admin.courses.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30 transition transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function addKeypoint() {
            const container = document.getElementById('keypoints-container');
            const inputDiv = document.createElement('div');
            inputDiv.className = 'flex gap-2 items-center group animate-fade-in-down';
            
            inputDiv.innerHTML = `
                <span class="text-gray-400 font-bold select-none">•</span>
                <input type="text" name="course_keypoints[]" placeholder="Tulis poin pembelajaran..." 
                    class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 p-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            `;
            
            container.appendChild(inputDiv);
        }
    </script>
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out;
        }
    </style>
    @endpush
</x-app-layout>