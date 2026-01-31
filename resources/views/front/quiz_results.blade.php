<x-app-layout>
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Hasil Pengerjaan</h1>
                    <p class="text-gray-500 text-sm mt-1">{{ $lesson->title }}</p>
                </div>
                <a href="{{ route('front.learning', [$course->slug, $lesson->id]) }}" class="text-sm font-bold text-indigo-600 hover:underline">
                    &larr; Kembali ke Materi
                </a>
            </div>

            <div class="space-y-6">
                @foreach($questions as $index => $q)
                    @php
                        $userAns = $q->user_answer; // Jawaban siswa
                        $isCorrect = $userAns && $userAns->is_correct;
                        
                        // Styling Logic
                        $borderColor = $q->type == 'multiple_choice' ? ($isCorrect ? 'border-green-200' : 'border-red-200') : 'border-gray-200';
                        $bgColor = $q->type == 'multiple_choice' ? ($isCorrect ? 'bg-green-50' : 'bg-red-50') : 'bg-slate-50';
                        $textColor = $q->type == 'multiple_choice' ? ($isCorrect ? 'text-green-700' : 'text-red-700') : 'text-slate-700';
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border {{ $borderColor }} overflow-hidden">
                        
                        {{-- Soal --}}
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="text-gray-900 font-bold text-lg mb-2">{{ $q->question_text }}</p>
                                    <span class="text-xs font-semibold px-2 py-1 rounded bg-gray-100 text-gray-500 uppercase tracking-wider">
                                        {{ $q->type == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Jawaban Siswa --}}
                        <div class="p-6 {{ $bgColor }}">
                            <p class="text-xs font-bold text-gray-500 uppercase mb-2">Jawaban Anda:</p>
                            
                            @if($userAns)
                                <div class="flex items-start gap-2">
                                    @if($q->type == 'multiple_choice')
                                        {{-- Ikon Benar/Salah --}}
                                        @if($isCorrect)
                                            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        @endif
                                        
                                        <span class="font-bold {{ $textColor }} text-lg">{{ $userAns->answer }}</span>
                                    @else
                                        {{-- Essay --}}
                                        <p class="text-gray-800 whitespace-pre-line">{{ $userAns->answer }}</p>
                                        <div class="mt-3 flex items-center gap-2">
                                            @if($userAns->score > 0)
                                                <span class="text-xs font-bold bg-green-100 text-green-700 px-2 py-1 rounded">Nilai: {{ $userAns->score }} / {{ $q->points }}</span>
                                            @else
                                                <span class="text-xs font-bold bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Menunggu Koreksi / Belum Dinilai</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-red-500 italic">Tidak dijawab</p>
                            @endif
                        </div>

                        {{-- Kunci Jawaban (Hanya muncul jika PG & Salah) --}}
                        @if($q->type == 'multiple_choice' && !$isCorrect)
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Kunci Jawaban Benar:</p>
                                <p class="text-gray-900 font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $q->correct_answer }}
                                </p>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>