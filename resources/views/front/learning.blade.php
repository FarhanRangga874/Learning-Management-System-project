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
        
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
    </style>
    @endpush

    {{-- Logic PHP Helper: Ekstrak YouTube ID --}}
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

            {{-- EMPTY STATE CHECK --}}
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
                    
                    {{-- 1. TAMPILAN ASSIGNMENT --}}
                    @if($currentLesson->type == 'assignment')
                        
                        <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-10 shadow-sm text-center">
                            <div class="inline-flex items-center justify-center w-24 h-24 bg-indigo-50 text-indigo-600 rounded-full mb-6 ring-8 ring-indigo-50/50">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Tugas: {{ $currentLesson->title }}</h3>
                            
                            <div class="prose prose-sm max-w-2xl mx-auto text-gray-500 mb-8 leading-relaxed">
                                {!! $currentLesson->content ?? 'Silakan kerjakan tugas ini sesuai instruksi.' !!}
                            </div>

                            @if(isset($hasSubmitted) && $hasSubmitted)
                                <div class="bg-green-50 border border-green-200 rounded-2xl p-6 max-w-md mx-auto mb-6">
                                    <p class="text-green-800 font-bold flex items-center justify-center gap-2 text-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Tugas Selesai
                                    </p>
                                    <div class="mt-4 border-t border-green-200 pt-4">
                                        <p class="text-green-600 text-sm font-medium">Skor Pilihan Ganda:</p>
                                        <p class="text-4xl font-extrabold text-green-700 mt-1">{{ $totalScore ?? 0 }}</p>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-3 italic">*Nilai essay menunggu koreksi instruktur.</p>
                                </div>
                                <button disabled class="px-8 py-3 bg-gray-100 text-gray-400 rounded-xl font-bold cursor-not-allowed border border-gray-200 shadow-none">
                                    Tidak Dapat Diulang
                                </button>
                            @else
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

                    {{-- 2. TAMPILAN MATERI STANDAR --}}
                    @else
                    
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
                            {{-- Banner Text --}}
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

                        {{-- Info Bar --}}
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
                            
                            @if(Auth::check() && Auth::user()->role == 'admin' && session('dev_mode'))
                                <a href="{{ route('admin.chapters.lessons.edit', [$currentLesson->chapter_id, $currentLesson->id]) }}" class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-md hover:bg-yellow-200 transition font-bold flex items-center gap-1">
                                    Edit Materi
                                </a>
                            @endif
                        </div>

                        {{-- Konten Teks --}}
                        @if($currentLesson->content)
                            <div class="ck-content prose prose-lg prose-slate max-w-none">
                                {!! $currentLesson->content !!}
                            </div>
                        @endif

                        {{-- Tombol Tandai Selesai --}}
                        <div class="mt-8 flex justify-end border-t border-gray-100 pt-6">
                            @if(isset($isCompleted) && $isCompleted)
                                <form action="{{ route('front.lesson.complete', [$course->slug, $currentLesson->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg font-bold flex items-center gap-2 transition">
                                        <span>Lanjut Materi Berikutnya</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('front.lesson.complete', [$course->slug, $currentLesson->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Tandai Selesai & Lanjut
                                    </button>
                                </form>
                            @endif
                        </div>

                    @endif

                </div>

                {{-- KOLOM KANAN: SIDEBAR & PROGRESS --}}
                <div class="lg:col-span-4 order-2 lg:order-2">
                    <div class="lg:sticky lg:top-24">
                        
                        {{-- 1. PROGRESS BAR --}}
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-6 relative overflow-hidden group">
                            
                            <div class="flex justify-between items-end mb-3 relative z-10">
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Status Penyelesaian</span>
                                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $progress }}%</span>
                                </div>
                                
                                @if($progress == 100)
                                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 ring-1 ring-indigo-100 shadow-sm transition-transform transform group-hover:scale-110 duration-500">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2 mb-5 overflow-hidden relative z-10">
                                <div class="h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden {{ $progress == 100 ? 'bg-indigo-600' : 'bg-gray-800' }}" 
                                     style="width: {{ $progress }}%">
                                     <div class="absolute top-0 left-0 bottom-0 right-0 bg-white/20 w-full -translate-x-full animate-[shimmer_2s_infinite]"></div>
                                </div>
                            </div>

                            {{-- === LOGIC TOMBOL SERTIFIKAT === --}}
                            @if($progress == 100)
                                
                                {{-- Cek Status Sertifikat --}}
                                @php
                                    $myCert = \App\Models\Certificate::where('user_id', Auth::id())
                                                ->where('course_id', $course->id)->first();
                                @endphp

                                <div class="relative z-10 text-center">
                                    <div class="py-3 px-4 bg-slate-50 rounded-lg border border-slate-100 mb-4">
                                        <h4 class="text-slate-800 font-bold text-sm">Kompetensi Tercapai</h4>
                                        <p class="text-slate-500 text-[11px] mt-0.5">Anda telah menyelesaikan seluruh materi.</p>
                                    </div>
                                    
                                    {{-- 1. BELUM REQUEST --}}
                                    @if(!$myCert)
                                        <form action="{{ route('front.certificate.request', $course->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-slate-900 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-800 transition shadow-lg shadow-slate-200 hover:shadow-xl transform hover:-translate-y-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Klaim Sertifikat
                                            </button>
                                        </form>
                                    
                                    {{-- 2. SUDAH REQUEST (PENDING) --}}
                                    @elseif($myCert->status == 'pending')
                                        <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-yellow-100 text-yellow-800 text-xs font-bold uppercase tracking-wider rounded-xl cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Menunggu Verifikasi
                                        </button>
                                    
                                    {{-- 3. APPROVED (DOWNLOAD) --}}
                                    @elseif($myCert->status == 'approved')
                                        <a href="{{ route('front.certificate.download', $myCert->id) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-200 hover:shadow-xl transform hover:-translate-y-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download Sertifikat
                                        </a>
                                    
                                    {{-- 4. REJECTED --}}
                                    @elseif($myCert->status == 'rejected')
                                        <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-100 text-red-800 text-xs font-bold uppercase tracking-wider rounded-xl cursor-not-allowed">
                                            Permintaan Ditolak
                                        </button>
                                    @endif

                                </div>
                                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-indigo-50 rounded-full opacity-50 z-0"></div>
                                <div class="absolute -top-6 -left-6 w-20 h-20 bg-slate-50 rounded-full opacity-50 z-0"></div>

                            @else
                                {{-- TAMPILAN SEDANG BELAJAR --}}
                                <div class="flex justify-between text-xs text-gray-500 font-medium relative z-10">
                                    @php
                                        // Helper count untuk tampilan
                                        $totalLessonsCount = $course->chapters->flatMap->lessons->count();
                                        $completedLessonsCount = \App\Models\LessonCompletion::where('user_id', Auth::id())
                                            ->where('course_id', $course->id)->count();
                                    @endphp
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $totalLessonsCount - $completedLessonsCount }} materi tersisa
                                    </span>
                                    <span class="text-indigo-600">Lanjutkan &rarr;</span>
                                </div>
                            @endif
                        </div>

                        {{-- 2. DAFTAR MATERI --}}
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" x-data="{ open: true }">
                            
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
                                                @php 
                                                    $isActive = isset($currentLesson) && $currentLesson->id == $lesson->id;
                                                    // Query cek completion untuk sidebar
                                                    $isLessonDone = \App\Models\LessonCompletion::where('user_id', Auth::id())
                                                        ->where('lesson_id', $lesson->id)
                                                        ->exists();
                                                @endphp
                                                <a href="{{ route('front.learning', [$course->slug, $lesson->id]) }}" 
                                                   class="group relative flex items-center justify-between px-3 py-2.5 ml-2 rounded-lg transition-all duration-200 {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-medium ring-1 ring-indigo-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                    <div class="flex items-center gap-2.5 overflow-hidden">
                                                        <div class="flex-shrink-0">
                                                            @if($isLessonDone)
                                                                <div class="w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                                </div>
                                                            @else
                                                                <div class="{{ $isActive ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}">
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
                                                            @endif
                                                        </div>
                                                        <span class="text-sm truncate w-full {{ $isLessonDone ? 'text-gray-400 line-through decoration-gray-300' : '' }}">{{ $lesson->title }}</span>
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