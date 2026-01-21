<x-app-layout>
    {{-- Custom Style --}}
    @push('styles')
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
    @endpush

    {{-- Logic PHP: Ekstrak YouTube ID --}}
    @php
        $youtubeId = '';
        if($currentLesson && $currentLesson->type == 'video' && $currentLesson->video_source == 'youtube') {
            $url = $currentLesson->file_path; 
            $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
            if (preg_match($pattern, $url, $match)) {
                $youtubeId = $match[1];
            } else {
                $youtubeId = $url; 
            }
        }
    @endphp

    <div class="bg-white min-h-screen">
        
        {{-- HEADER NAVIGATION (STICKY) --}}
        <div class="border-b border-gray-100 bg-white sticky top-0 z-30 shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    {{-- KIRI: TOMBOL KEMBALI --}}
                    <div class="flex items-center h-full">
                        <a href="{{ route('front.details', $course->slug) }}" 
                           class="group flex items-center gap-2 px-3 py-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all duration-200 shadow-sm h-10"
                           title="Kembali ke Detail Kursus">
                            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden md:inline text-sm font-medium">Kembali</span>
                        </a>
                    </div>

                    {{-- KANAN: JUDUL KURSUS --}}
                    <div class="flex flex-col justify-center h-full text-right">
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider leading-none mb-0.5 hidden md:block">
                            Sedang Mempelajari
                        </div>
                        <div class="text-sm md:text-base font-bold text-gray-900 truncate max-w-[200px] md:max-w-md leading-tight">
                            {{ $course->title }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- MAIN CONTENT WRAPPER --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">
            
            {{-- BREADCRUMBS --}}
            <nav class="flex mb-6 overflow-x-auto no-scrollbar whitespace-nowrap pb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('front.index') }}" class="inline-flex items-center text-xs font-medium text-gray-500 hover:text-indigo-600 transition-colors group">
                            Home
                        </a>
                    </li>
                    <li><span class="text-gray-300 text-xs">/</span></li>
                    <li>
                        <a href="{{ route('front.index', ['category' => $course->category->id]) }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                            {{ $course->category->name }}
                        </a>
                    </li>
                    <li><span class="text-gray-300 text-xs">/</span></li>
                    <li aria-current="page">
                        <span class="text-xs font-semibold text-gray-900 truncate max-w-[200px] sm:max-w-xs block">
                            {{ $course->title }}
                        </span>
                    </li>
                </ol>
            </nav>

            {{-- EMPTY STATE --}}
            @if(!$currentLesson)
                <div class="flex flex-col items-center justify-center py-20 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Belum Ada Materi</h3>
                    <p class="text-gray-500 mt-2 max-w-md">Instruktur belum mengunggah materi untuk kursus ini.</p>
                    <a href="{{ route('front.details', $course->slug) }}" class="mt-6 px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                        Kembali ke Detail
                    </a>
                </div>
            @else
            
            {{-- HEADER JUDUL MATERI --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm border border-indigo-100">
                        @if($currentLesson->type == 'video')
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        @elseif($currentLesson->type == 'pdf')
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @elseif($currentLesson->type == 'assignment')
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        @else
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        @endif
                    </div>
                    
                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        {{ $currentLesson->title }}
                    </h1>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                
                {{-- KOLOM KIRI: KONTEN UTAMA --}}
                <div class="lg:col-span-8 order-1 lg:order-1">
                    
                    {{-- ============================================ --}}
                    {{-- 1. LOGIKA TAMPILAN: ASSIGNMENT (COVER TUGAS) --}}
                    {{-- ============================================ --}}
                    @if($currentLesson->type == 'assignment')
                        
                        <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-10 shadow-sm text-center">
                            
                            {{-- Ikon Besar --}}
                            <div class="inline-flex items-center justify-center w-24 h-24 bg-indigo-50 text-indigo-600 rounded-full mb-6 ring-8 ring-indigo-50/50">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Tugas: {{ $currentLesson->title }}</h3>
                            
                            {{-- Instruksi --}}
                            <div class="prose prose-sm max-w-2xl mx-auto text-gray-500 mb-8 leading-relaxed">
                                {!! $currentLesson->content ?? 'Silakan kerjakan tugas ini sesuai instruksi.' !!}
                            </div>

                            {{-- Cek Status Pengerjaan --}}
                            @if($hasSubmitted)
                                {{-- SUDAH DIKERJAKAN --}}
                                <div class="bg-green-50 border border-green-200 rounded-2xl p-6 max-w-md mx-auto mb-6">
                                    <p class="text-green-800 font-bold flex items-center justify-center gap-2 text-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Tugas Selesai
                                    </p>
                                    <div class="mt-4 border-t border-green-200 pt-4">
                                        <p class="text-green-600 text-sm font-medium">Skor Pilihan Ganda:</p>
                                        <p class="text-4xl font-extrabold text-green-700 mt-1">{{ $totalScore }}</p>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-3 italic">*Nilai essay menunggu koreksi instruktur.</p>
                                </div>
                                
                                <button disabled class="px-8 py-3 bg-gray-100 text-gray-400 rounded-xl font-bold cursor-not-allowed border border-gray-200 shadow-none">
                                    Tidak Dapat Diulang
                                </button>
                            @else
                                {{-- BELUM DIKERJAKAN --}}
                                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 max-w-md mx-auto mb-8 text-yellow-800 text-sm flex items-start gap-3 text-left">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <div>
                                        <span class="font-bold block mb-1">Perhatian:</span>
                                        Pastikan koneksi internet lancar. Setelah tombol "Mulai" ditekan, pengerjaan tidak dapat dibatalkan.
                                    </div>
                                </div>

                                <a href="{{ route('front.quiz', [$course->slug, $currentLesson->id]) }}" 
                                   class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 transition transform hover:-translate-y-1 text-lg">
                                    <span>Mulai Kerjakan Tugas</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </a>
                            @endif
                        </div>

                    {{-- ============================================ --}}
                    {{-- 2. LOGIKA TAMPILAN: MATERI STANDAR (VIDEO/PDF/TEKS) --}}
                    {{-- ============================================ --}}
                    @else
                    
                        {{-- A. MEDIA PLAYER (VIDEO / PDF) --}}
                        @if($currentLesson->type != 'text')
                            <div class="relative w-full {{ $currentLesson->type == 'pdf' ? 'h-[50vh] md:h-[80vh]' : 'aspect-video' }} bg-black rounded-2xl overflow-hidden shadow-lg border border-gray-100 z-10 mb-6 md:mb-8">
                                @if($currentLesson->type == 'video')
                                    @if($currentLesson->video_source == 'upload')
                                        <video class="w-full h-full object-contain" controls controlsList="nodownload">
                                            <source src="{{ Storage::url($currentLesson->file_path) }}" type="video/mp4" />
                                            Browser Anda tidak mendukung tag video.
                                        </video>
                                    @elseif($currentLesson->video_source == 'youtube')
                                        <iframe class="absolute top-0 left-0 w-full h-full"
                                                src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1&controls=1&showinfo=0" 
                                                title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                                    @endif
                                @elseif($currentLesson->type == 'pdf')
                                    <iframe src="{{ Storage::url($currentLesson->file_path) }}" class="absolute top-0 left-0 w-full h-full" frameborder="0">
                                        <a href="{{ Storage::url($currentLesson->file_path) }}" class="text-white underline">Download PDF</a>
                                    </iframe>
                                @endif
                            </div>
                        @else 
                            {{-- B. BANNER UNTUK TEKS ONLY --}}
                            <div class="w-full bg-gradient-to-r from-indigo-50 via-white to-white rounded-2xl p-6 md:p-8 border border-indigo-100 mb-8 flex flex-col md:flex-row items-center gap-6 shadow-sm overflow-hidden relative">
                                <div class="absolute top-0 left-0 w-32 h-32 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                                <div class="flex-shrink-0 relative z-10">
                                    <img src="https://illustrations.popsy.co/amber/woman-reading-tablet.svg" alt="Reading" class="w-32 h-32 md:w-40 md:h-40 object-contain hover:scale-105 transition-transform">
                                </div>
                                <div class="text-center md:text-left flex-1 relative z-10">
                                    <span class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-2">Mode Membaca</span>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">Siapkan Catatan Anda</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed">Materi ini berbentuk bacaan teks komprehensif.</p>
                                </div>
                            </div>
                        @endif

                        {{-- INFO BAR (HANYA UNTUK MATERI NON-ASSIGNMENT) --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-6 mb-6 gap-4">
                            <div class="text-sm text-gray-500">
                                @if($currentLesson->type == 'video')
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <span class="font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full border border-gray-200 text-xs md:text-sm">Video Pembelajaran</span>
                                        @if($currentLesson->video_source == 'youtube')
                                            <a href="https://www.youtube.com/watch?v={{ $youtubeId }}" target="_blank" class="flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-full border border-red-100 transition-colors">
                                                Buka di YouTube
                                            </a>
                                        @endif
                                    </div>
                                @elseif($currentLesson->type == 'pdf')
                                    <span class="font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full border border-gray-200 text-xs md:text-sm">Dokumen PDF</span>
                                @else
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full border border-gray-200 text-xs md:text-sm">Bacaan Teks</span>
                                        <span class="text-xs text-gray-400 flex items-center gap-1">
                                            {{ ceil(str_word_count(strip_tags($currentLesson->content)) / 200) }} Menit Baca
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- BUTTON EDIT (ADMIN ONLY) --}}
                            @if(Auth::check() && Auth::user()->role == 'admin' && session('dev_mode'))
                                <a href="{{ route('admin.chapters.lessons.edit', [$currentLesson->chapter_id, $currentLesson->id]) }}" class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-md hover:bg-yellow-200 transition font-bold flex items-center gap-1">
                                    Edit Materi
                                </a>
                            @endif
                        </div>

                        {{-- KONTEN CKEDITOR --}}
                        @if($currentLesson->content)
                            <div class="ck-content prose prose-lg prose-slate max-w-none">
                                {!! $currentLesson->content !!}
                            </div>
                        @endif

                    @endif {{-- END IF ASSIGNMENT --}}

                </div>

                {{-- KOLOM KANAN: SIDEBAR (TETAP SAMA) --}}
                <div class="lg:col-span-4 order-2 lg:order-2">
                    <div class="lg:sticky lg:top-24">
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" x-data="{ open: false }">
                            
                            <button @click="open = !open" class="w-full text-left p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center transition hover:bg-gray-100 lg:cursor-default lg:hover:bg-gray-50">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-bold text-gray-900">Daftar Materi</h3>
                                    <span class="text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200 px-2 py-0.5 rounded-full">
                                        {{ $course->chapters->sum(function($c){ return $c->lessons->count(); }) }}
                                    </span>
                                </div>
                                <div class="lg:hidden text-gray-500 transition-transform duration-300" :class="{'rotate-180': open}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>

                            <div :class="{'block': open, 'hidden': !open}" class="hidden lg:block max-h-[60vh] lg:max-h-[70vh] overflow-y-auto scrollbar-hide bg-white p-5 transition-all duration-300">
                                @foreach($course->chapters as $index => $chapter)
                                    <div class="mb-6 last:mb-0">
                                        <div class="flex items-center gap-3 mb-3">
                                            <span class="flex items-center justify-center w-6 h-6 rounded bg-gray-100 text-xs font-bold text-gray-500 border border-gray-200">{{ $index + 1 }}</span>
                                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider leading-tight">{{ $chapter->title }}</h4>
                                        </div>
                                        <div class="space-y-2 pl-2 border-l-2 border-gray-100 ml-3">
                                            @foreach($chapter->lessons as $lesson)
                                                @php $isActive = isset($currentLesson) && $currentLesson->id == $lesson->id; @endphp
                                                <a href="{{ route('front.learning', [$course->slug, $lesson->id]) }}" 
                                                   class="group relative flex items-center justify-between px-3 py-2.5 ml-2 rounded-lg transition-all duration-200 {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-medium ring-1 ring-indigo-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                    <div class="flex items-center gap-2.5 overflow-hidden">
                                                        <div class="flex-shrink-0 {{ $isActive ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}">
                                                            @if($lesson->type == 'video')
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                            @elseif($lesson->type == 'pdf')
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                            @elseif($lesson->type == 'assignment')
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                            @else
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            @endif
                                                        </div>
                                                        <span class="text-sm truncate w-full">{{ $lesson->title }}</span>
                                                    </div>
                                                    @if($isActive) <div class="lg:hidden absolute right-2 w-1.5 h-1.5 bg-indigo-600 rounded-full"></div> @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div x-show="!open" class="lg:hidden px-5 py-3 bg-gray-50 border-t border-gray-100 text-xs text-center text-gray-500 cursor-pointer hover:text-gray-700" @click="open = true">
                                Klik untuk melihat daftar materi lainnya
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endif {{-- END EMPTY STATE CHECK --}}

        </div>
    </div>
</x-app-layout>