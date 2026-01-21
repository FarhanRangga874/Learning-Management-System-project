<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Soal: {{ $lesson->title }}
            </h2>
            <a href="{{ route('admin.courses.chapters.index', $lesson->chapter->course_id) }}" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Kembali ke Materi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- FORM TAMBAH SOAL --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Buat Soal Baru</h3>
                
                <form action="{{ route('admin.lessons.questions.store', $lesson->id) }}" method="POST" x-data="{ type: 'multiple_choice' }">
                    @csrf
                    
                    {{-- Input Pertanyaan --}}
                    <div class="mb-4">
                        <x-input-label for="question_text" :value="__('Pertanyaan')" />
                        <textarea name="question_text" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required placeholder="Tulis pertanyaan di sini..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- Pilih Tipe --}}
                        <div>
                            <x-input-label for="type" :value="__('Tipe Soal')" />
                            <select name="type" x-model="type" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                                <option value="multiple_choice">Pilihan Ganda</option>
                                <option value="essay">Essay</option>
                            </select>
                        </div>
                        {{-- Input Poin --}}
                        <div>
                            <x-input-label for="points" :value="__('Bobot Poin')" />
                            <x-text-input type="number" name="points" value="10" class="w-full mt-1" required />
                        </div>
                    </div>

                    {{-- Area Opsi (Muncul jika PG) --}}
                    <div x-show="type === 'multiple_choice'" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                        <p class="font-bold text-sm mb-3 text-gray-700">Opsi Jawaban:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500">Opsi A</label>
                                <x-text-input type="text" name="option_a" class="w-full mt-1" placeholder="Jawaban A" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500">Opsi B</label>
                                <x-text-input type="text" name="option_b" class="w-full mt-1" placeholder="Jawaban B" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500">Opsi C</label>
                                <x-text-input type="text" name="option_c" class="w-full mt-1" placeholder="Jawaban C" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500">Opsi D</label>
                                <x-text-input type="text" name="option_d" class="w-full mt-1" placeholder="Jawaban D" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="correct_answer" :value="__('Kunci Jawaban Benar')" />
                            <select name="correct_answer" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Simpan Soal</x-primary-button>
                    </div>
                </form>
            </div>

            {{-- LIST SOAL --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-bold mb-4 text-gray-900">Daftar Soal ({{ $questions->count() }})</h3>
                <div class="space-y-4">
                    @forelse($questions as $index => $q)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $q->type == 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $q->type == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                                    </span>
                                    <span class="ml-2 text-xs font-bold text-gray-500">Points: {{ $q->points }}</span>
                                    
                                    <p class="mt-2 font-bold text-gray-800">{{ $index + 1 }}. {{ $q->question_text }}</p>
                                    
                                    {{-- Tampilkan Opsi jika PG --}}
                                    @if($q->type == 'multiple_choice')
                                        <div class="ml-4 mt-2 text-sm text-gray-600 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($q->options as $key => $val)
                                                <div class="{{ $q->correct_answer == $key ? 'text-green-600 font-bold' : '' }}">
                                                    {{ $key }}. {{ $val }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">Belum ada soal yang dibuat.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>