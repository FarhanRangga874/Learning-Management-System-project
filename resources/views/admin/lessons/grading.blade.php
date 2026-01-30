<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.lessons.users.index', $lesson->id) }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </x-slot>

    <div class="pb-24 bg-slate-50 min-h-screen">
        <form action="{{ route('admin.lessons.users.gradeAll', [$lesson->id, $user->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                {{-- LAYOUT GRID --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    {{-- ================= KOLOM KANAN (SIDEBAR INFO & ACTION) ================= --}}
                    <div class="lg:col-span-4 order-1 lg:order-2 lg:sticky lg:top-6 space-y-6">
                        
                        {{-- Header Sidebar (Judul) --}}
                        <div class="hidden lg:flex items-center justify-between mb-2 h-8">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Informasi Siswa</h3>
                        </div>

                        {{-- Card 1: Profil Siswa & Skor --}}
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="h-1.5 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                            
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 font-bold text-xl border-4 border-white shadow-sm uppercase shrink-0">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <h3 class="font-bold text-slate-900 text-lg truncate" title="{{ $user->name }}">{{ $user->name }}</h3>
                                        <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="mb-6 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Materi Tugas</p>
                                    <p class="text-sm font-medium text-slate-800 line-clamp-2">{{ $lesson->title }}</p>
                                </div>

                                <div class="text-center pt-4 border-t border-slate-100">
                                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Total Nilai Saat Ini</p>
                                    <div class="flex items-baseline justify-center gap-1">
                                        <span class="text-5xl font-black text-indigo-600 tracking-tight">
                                            {{ $answers->sum('score') }}
                                        </span>
                                        <span class="text-sm font-bold text-slate-400">/ {{ $answers->sum('question.points') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Tombol Simpan (Desktop Only) --}}
                        <div class="hidden lg:block bg-white border border-slate-200 rounded-2xl shadow-sm p-4">
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Penilaian
                            </button>
                            <p class="text-[10px] text-center text-slate-400 mt-3">
                                Pastikan Anda telah memeriksa semua jawaban essay sebelum menyimpan.
                            </p>
                        </div>
                    </div>

                    {{-- ================= KOLOM KIRI (DAFTAR SOAL) ================= --}}
                    <div class="lg:col-span-8 order-2 lg:order-1 space-y-6">
                        
                        {{-- HEADER LEMBAR JAWABAN --}}
                        <div class="flex items-center justify-between mb-2 h-8">
                            <div class="flex items-center gap-2">
                                <h2 class="text-lg font-bold text-slate-800">Lembar Jawaban</h2>
                                <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-indigo-200">
                                    {{ $answers->count() }} Soal
                                </span>
                            </div>
                        </div>

                        @forelse($answers as $index => $ans)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:border-indigo-300 transition-all duration-300 group hover:shadow-md">
                                
                                {{-- Header Card Soal --}}
                                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-start gap-4">
                                    <div class="flex gap-3 w-full">
                                        <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 font-bold text-sm shadow-sm">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="w-full pt-1">
                                            <div class="flex items-center justify-between w-full mb-2">
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border {{ $ans->question->type == 'multiple_choice' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-purple-50 text-purple-600 border-purple-100' }}">
                                                    {{ $ans->question->type == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                                                </span>
                                            </div>
                                            <div class="text-slate-800 font-medium leading-relaxed prose prose-sm max-w-none text-base">
                                                {!! $ans->question->question_text !!} 
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Body Jawaban --}}
                                <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                                    
                                    {{-- Kolom Jawaban (Kiri) --}}
                                    <div class="md:col-span-8 space-y-2">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            Jawaban Siswa
                                        </p>
                                        
                                        @if($ans->question->type == 'multiple_choice')
                                            <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <div class="w-10 h-10 flex items-center justify-center rounded-lg text-lg font-mono font-bold border-2 shrink-0 {{ $ans->answer == $ans->question->correct_answer ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                                                    {{ $ans->answer }}
                                                </div>
                                                <div>
                                                    @if($ans->answer == $ans->question->correct_answer)
                                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-md border border-green-100">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                            Benar
                                                        </span>
                                                    @else
                                                        <div class="flex flex-col gap-1">
                                                            <span class="inline-flex items-center gap-1 text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-md border border-red-100 w-fit">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                Salah
                                                            </span>
                                                            <span class="text-xs text-slate-500 mt-0.5">
                                                                Kunci: <strong class="text-green-600 font-mono">{{ $ans->question->correct_answer }}</strong>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-slate-700 text-sm leading-relaxed whitespace-pre-line min-h-[80px]">
                                                {{ $ans->answer ?: '(Tidak ada jawaban)' }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Kolom Input Nilai (Kanan) --}}
                                    <div class="md:col-span-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6 flex flex-col justify-center">
                                        @if($ans->question->type == 'multiple_choice')
                                            {{-- Jika Pilihan Ganda: Tampilkan Nilai Otomatis (Tanpa Input) --}}
                                            <div class="text-center">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Nilai Otomatis</p>
                                                @php
                                                    $autoScore = ($ans->answer === $ans->question->correct_answer) ? $ans->question->points : 0;
                                                @endphp
                                                <div class="text-3xl font-black {{ $autoScore > 0 ? 'text-green-600' : 'text-slate-400' }}">
                                                    {{ $autoScore }}
                                                </div>
                                            </div>
                                        @else
                                            {{-- Jika Essay: Tampilkan Input Nilai --}}
                                            <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                                                <label class="block text-xs font-bold text-indigo-900 mb-2 uppercase tracking-wide">Beri Nilai</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="number" name="scores[{{ $ans->id }}]" value="{{ $ans->score }}" max="{{ $ans->question->points }}" min="0" 
                                                        class="w-full text-center font-bold text-xl text-indigo-700 bg-white border-indigo-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm py-2 placeholder-indigo-200" placeholder="0">
                                                </div>
                                                <div class="text-center mt-2 border-t border-indigo-100 pt-2">
                                                    <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wide">Maksimal: {{ $ans->question->points }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-slate-500 font-medium">Tidak ada jawaban yang perlu dinilai.</p>
                            </div>
                        @endforelse

                    </div>

                </div>
            </div>

            {{-- Floating Action Bar (Mobile Only) --}}
            <div class="lg:hidden fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-slate-200 shadow-[0_-4px_20px_-5px_rgba(0,0,0,0.1)] z-50">
                <button type="submit" class="w-full py-3 px-6 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Penilaian
                </button>
            </div>

        </form>
    </div>
</x-app-layout>