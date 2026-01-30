<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Koreksi: {{ $user->name }}
            </h2>
            <a href="{{ route('admin.lessons.users.index', $lesson->id) }}" class="text-sm text-gray-500 hover:text-indigo-600 font-bold">&larr; Kembali ke Daftar</a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Info Header --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6 mb-6 flex justify-between items-center shadow-sm">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">{{ $lesson->title }}</h3>
                    <p class="text-slate-500 text-sm">Menilai jawaban milik <strong>{{ $user->name }}</strong></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500 uppercase font-bold">Total Nilai</p>
                    <div class="text-4xl font-extrabold text-indigo-600">{{ $answers->sum('score') }}</div>
                </div>
            </div>

            {{-- FORM PEMBUNGKUS UTAMA --}}
            <form action="{{ route('admin.lessons.users.gradeAll', [$lesson->id, $user->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6 mb-8">
                    @forelse($answers as $index => $ans)
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 transition hover:shadow-md">
                            
                            {{-- SOAL --}}
                            <div class="mb-4 pb-4 border-b border-slate-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                                        {{ $ans->question->type == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-medium">Maksimal Poin: {{ $ans->question->points }}</span>
                                </div>
                                <p class="text-lg font-bold text-slate-800">{{ $index + 1 }}. {{ $ans->question->question_text }}</p>
                            </div>

                            {{-- JAWABAN SISWA --}}
                            <div class="mb-6 p-4 rounded-lg border-l-4 {{ $ans->is_correct ? 'bg-green-50 border-green-500' : 'bg-slate-50 border-slate-300' }}">
                                <p class="text-[10px] font-bold text-slate-500 uppercase mb-2">Jawaban Siswa:</p>
                                
                                @if($ans->question->type == 'multiple_choice')
                                    <div class="flex items-center gap-3">
                                        <div class="text-xl font-mono font-bold text-slate-800 bg-white px-3 py-1 rounded border border-slate-200 inline-block">
                                            {{ $ans->answer }}
                                        </div>
                                        @if($ans->answer != $ans->question->correct_answer)
                                            <span class="text-xs text-red-600 font-bold flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Salah (Kunci: {{ $ans->question->correct_answer }})
                                            </span>
                                        @else
                                            <span class="text-xs text-green-600 font-bold flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Benar
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-slate-800 whitespace-pre-line leading-relaxed text-sm">{{ $ans->answer }}</p>
                                @endif
                            </div>

                            {{-- INPUT NILAI (ARRAY) --}}
                            <div class="flex items-center justify-end gap-3 bg-slate-50 p-4 rounded-lg">
                                <label class="block text-xs font-bold text-slate-500">Nilai:</label>
                                {{-- Perhatikan name="scores[{{ $ans->id }}]" --}}
                                <input type="number" name="scores[{{ $ans->id }}]" value="{{ $ans->score }}" max="{{ $ans->question->points }}" min="0" 
                                    class="w-24 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-center text-indigo-700">
                                <span class="text-xs text-slate-400">/ {{ $ans->question->points }}</span>
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-500">Tidak ada data jawaban.</p>
                        </div>
                    @endforelse
                </div>

                {{-- TOMBOL SIMPAN GLOBAL (FLOATING BOTTOM) --}}
                <div class="sticky bottom-6 flex justify-end">
                    <div class="bg-white p-4 rounded-xl shadow-lg border border-slate-200 flex items-center gap-4">
                        <span class="text-sm text-slate-500">Pastikan semua nilai sudah benar.</span>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 text-sm font-bold transition shadow-md hover:shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Semua Nilai
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>