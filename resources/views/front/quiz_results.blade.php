<x-app-layout>
    <div class="bg-slate-50 min-h-screen py-8 font-sans text-slate-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 1. Navigasi Atas --}}
            <div class="mb-6">
                <a href="{{ route('front.learning', [$course->slug, $lesson->id]) }}" 
                   class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors font-medium text-sm group">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:border-indigo-200 transition-colors">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    Kembali ke Materi
                </a>
            </div>

            {{-- 2. Header Ringkasan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 mb-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Hasil Pengerjaan</h1>
                    <p class="text-slate-500 mt-2 text-sm font-medium">
                        Materi: <span class="text-indigo-600">{{ $lesson->title }}</span>
                    </p>
                </div>

                {{-- Stats Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Total Skor</p>
                        @php $totalScore = $questions->sum(fn($q) => $q->user_answer->score ?? 0); @endphp
                        <p class="text-3xl font-black text-slate-900">{{ $totalScore }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-green-50 border border-green-100 text-center">
                        <p class="text-[10px] uppercase font-bold text-green-600 tracking-wider mb-1">Benar</p>
                        <p class="text-3xl font-black text-green-700">
                            {{ $questions->where('type', 'multiple_choice')->filter(fn($q) => $q->user_answer && $q->user_answer->is_correct)->count() }}
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-red-50 border border-red-100 text-center">
                        <p class="text-[10px] uppercase font-bold text-red-600 tracking-wider mb-1">Salah</p>
                        <p class="text-3xl font-black text-red-700">
                            {{ $questions->where('type', 'multiple_choice')->filter(fn($q) => $q->user_answer && !$q->user_answer->is_correct)->count() }}
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-purple-50 border border-purple-100 text-center">
                        <p class="text-[10px] uppercase font-bold text-purple-600 tracking-wider mb-1">Essay</p>
                        <p class="text-3xl font-black text-purple-700">
                             {{ $questions->where('type', 'essay')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3. Detail Soal Loop --}}
            <div class="space-y-8">
                @foreach($questions as $index => $q)
                    @php
                        $userAns = $q->user_answer; 
                        $isCorrect = $userAns && $userAns->is_correct;
                        
                        // Styling Header Soal
                        if ($q->type == 'multiple_choice') {
                            $borderColor = $isCorrect ? 'border-green-200' : 'border-red-200';
                            $headerBg = $isCorrect ? 'bg-green-50' : 'bg-red-50';
                            $statusIcon = $isCorrect 
                                ? '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' 
                                : '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                            $statusText = $isCorrect ? 'Benar' : 'Salah';
                            $statusTextColor = $isCorrect ? 'text-green-800' : 'text-red-800';
                        } else {
                            $borderColor = 'border-purple-200';
                            $headerBg = 'bg-purple-50';
                            $statusIcon = '<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';
                            $statusText = 'Essay';
                            $statusTextColor = 'text-purple-800';
                        }
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border {{ $borderColor }} overflow-hidden">
                        
                        {{-- A. Header Soal (Nomor & Status) --}}
                        <div class="px-6 py-4 {{ $headerBg }} border-b {{ $borderColor }} flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-700 font-bold text-sm shadow-sm">
                                    {{ $index + 1 }}
                                </span>
                                <span class="font-bold {{ $statusTextColor }} flex items-center gap-2 text-sm">
                                    {!! $statusIcon !!} {{ $statusText }}
                                </span>
                            </div>
                            <span class="text-[10px] font-bold bg-white/60 px-3 py-1 rounded-full border border-slate-200 text-slate-500 uppercase tracking-wide">
                                {{ $q->points }} Poin
                            </span>
                        </div>

                        <div class="p-6 md:p-8">
                            
                            {{-- B. Teks Soal --}}
                            <div class="mb-6">
                                <p class="text-lg font-bold text-slate-900 leading-relaxed">{{ $q->question_text }}</p>
                            </div>

                            {{-- C. Opsi Jawaban (Tampilan Full) --}}
                            @if($q->type == 'multiple_choice')
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                                        @php
                                            $optionText = $q->options[$opt] ?? '';
                                            $isUserSelected = ($userAns && $userAns->answer == $opt);
                                            $isCorrectKey = ($q->correct_answer == $opt);

                                            // Logika Kelas CSS
                                            $divClass = "relative flex items-start gap-3 p-4 rounded-xl border-2 transition-all duration-200 ";
                                            $badgeClass = "w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 ";
                                            
                                            if ($isUserSelected && $isCorrectKey) {
                                                // USER BENAR
                                                $divClass .= "bg-green-50 border-green-500 ring-1 ring-green-500 z-10";
                                                $badgeClass .= "bg-green-500 text-white";
                                            } elseif ($isUserSelected && !$isCorrectKey) {
                                                // USER SALAH
                                                $divClass .= "bg-red-50 border-red-500 ring-1 ring-red-500 z-10";
                                                $badgeClass .= "bg-red-500 text-white";
                                            } elseif (!$isUserSelected && $isCorrectKey) {
                                                // KUNCI JAWABAN (Yg dilewatkan user)
                                                $divClass .= "bg-white border-green-400 border-dashed";
                                                $badgeClass .= "bg-green-100 text-green-700 border border-green-200";
                                            } else {
                                                // OPSI NETRAL
                                                $divClass .= "bg-white border-slate-100 opacity-60 grayscale-[50%]";
                                                $badgeClass .= "bg-slate-100 text-slate-500";
                                            }
                                        @endphp

                                        <div class="{{ $divClass }}">
                                            {{-- Huruf Opsi (A/B/C/D) --}}
                                            <div class="{{ $badgeClass }}">
                                                {{ $opt }}
                                            </div>

                                            {{-- Teks Opsi --}}
                                            <div class="flex-grow text-sm md:text-base font-medium text-slate-800 leading-snug pt-1">
                                                {{ $optionText }}
                                            </div>

                                            {{-- Ikon Status (Pojok Kanan) --}}
                                            @if($isUserSelected)
                                                <div class="shrink-0 flex items-center gap-1 text-xs font-bold uppercase tracking-wider 
                                                    {{ $isCorrectKey ? 'text-green-600' : 'text-red-600' }}">
                                                    @if($isCorrectKey)
                                                        <span>Jawaban Anda</span> <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    @else
                                                        <span>Anda Salah</span> <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    @endif
                                                </div>
                                            @elseif($isCorrectKey)
                                                <div class="shrink-0 flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-green-600">
                                                    <span>Kunci Benar</span> <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                            {{-- D. Tampilan Essay --}}
                            @else
                                <div class="bg-slate-50 rounded-xl border border-slate-200 p-5">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Jawaban Anda:</p>
                                    @if($userAns)
                                        <div class="prose prose-sm max-w-none text-slate-800">
                                            {{ $userAns->answer }}
                                        </div>
                                    @else
                                        <p class="text-red-500 text-sm italic font-medium">Tidak dijawab</p>
                                    @endif
                                </div>

                                {{-- Feedback Essay --}}
                                <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between">
                                    <span class="text-xs font-medium text-slate-500">Nilai Instruktur:</span>
                                    @if($userAns && $userAns->score !== null && $userAns->score > 0)
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                            {{ $userAns->score }} / {{ $q->points }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Menunggu Koreksi
                                        </span>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>