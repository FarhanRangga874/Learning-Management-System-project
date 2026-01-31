<x-app-layout>
    {{-- Custom Style --}}
    @push('styles')
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
        
        /* Styling Konten CKEditor agar rapi */
        .prose img { border-radius: 0.75rem; margin-left: auto; margin-right: auto; }
        .prose iframe { width: 100%; border-radius: 0.75rem; aspect-ratio: 16/9; }
    </style>
    @endpush

    {{-- Logic PHP Helper --}}
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

    <div class="bg-gray-50 min-h-screen font-sans antialiased text-gray-900">
        
        {{-- HEADER NAVIGATION (STICKY) --}}
        <div class="bg-white sticky top-0 z-40 border-b border-gray-100 shadow-sm/50 backdrop-blur-md bg-white/90 supports-[backdrop-filter]:bg-white/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    {{-- KIRI: TOMBOL KEMBALI --}}
                    <div class="flex items-center h-full">
                        <a href="{{ route('front.details', $course->slug) }}" 
                           class="group flex items-center gap-2 pr-4 pl-3 py-2 rounded-full bg-gray-50 border border-gray-200 text-gray-600 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all duration-200">
                            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="text-sm font-bold hidden md:inline">Kembali</span>
                        </a>
                    </div>

                    {{-- KANAN: JUDUL KURSUS --}}
                    <div class="flex flex-col items-end justify-center h-full">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider hidden md:block leading-none mb-1">Sedang Mempelajari</span>
                        <h1 class="text-sm md:text-base font-bold text-gray-900 truncate max-w-[200px] md:max-w-md leading-tight">
                            {{ $course->title }}
                        </h1>
                    </div>

                </div>
            </div>
        </div>

        {{-- MAIN CONTENT WRAPPER --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-20">
            
            {{-- BREADCRUMBS --}}
            <nav class="flex mb-8 overflow-x-auto scrollbar-hide pb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs md:text-sm">
                    <li><a href="{{ route('front.index') }}" class="text-gray-500 hover:text-indigo-600 transition font-medium">Home</a></li>
                    <li><span class="text-gray-300">/</span></li>
                    <li><a href="{{ route('front.index', ['category' => $course->category->id]) }}" class="text-gray-500 hover:text-indigo-600 transition font-medium whitespace-nowrap">{{ $course->category->name }}</a></li>
                    <li><span class="text-gray-300">/</span></li>
                    <li class="text-indigo-600 font-bold truncate max-w-[150px]">{{ $currentLesson ? $currentLesson->title : 'Selesai' }}</li>
                </ol>
            </nav>

            {{-- LAYOUT GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                
                {{-- KOLOM KIRI: KONTEN UTAMA --}}
                <div class="lg:col-span-8 order-1">
                    
                    @if(!$currentLesson)
                        {{-- EMPTY STATE --}}
                        <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200 shadow-sm">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Belum Ada Materi</h3>
                            <p class="text-gray-500 mt-2 max-w-sm">Instruktur belum mengunggah materi untuk kursus ini.</p>
                            <a href="{{ route('front.details', $course->slug) }}" class="mt-6 px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">Kembali ke Detail</a>
                        </div>
                    @else

                        {{-- ========================================== --}}
                        {{-- 1. TAMPILAN KHUSUS TUGAS (ASSIGNMENT) --}}
                        {{-- ========================================== --}}
                        @if($currentLesson->type == 'assignment')
                            
                           {{-- ... (Kode Assignment Anda sebelumnya tetap di sini - tidak saya ubah) ... --}}
                            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden relative">
                                <div class="h-28 bg-gradient-to-r from-indigo-600 to-purple-600 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-white/10 opacity-30" style="background-image: radial-gradient(circle, #fff 10%, transparent 10.5%); background-size: 20px 20px;"></div>
                                </div>
                                <div class="px-6 md:px-10 relative -mt-12 flex flex-col md:flex-row md:items-end gap-6 pb-8 border-b border-gray-100">
                                    <div class="w-24 h-24 bg-white rounded-2xl p-1.5 shadow-xl flex-shrink-0">
                                        <div class="w-full h-full bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 pb-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider border border-indigo-100">Tugas / Kuis</span>
                                        </div>
                                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">{{ $currentLesson->title }}</h1>
                                    </div>
                                </div>
                                <div class="p-6 md:p-10">
                                    <div class="flex items-center gap-2 mb-4">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Instruksi</h3>
                                    </div>
                                    <div class="prose prose-indigo prose-sm sm:prose-base max-w-none text-gray-600 leading-relaxed bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                        {!! $currentLesson->content ?? '<p class="italic text-gray-400">Tidak ada instruksi khusus.</p>' !!}
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-6 md:px-10 py-8 border-t border-gray-100">
                                    @if(isset($hasSubmitted) && $hasSubmitted)
                                        <div class="flex flex-col md:flex-row gap-6 items-stretch">
                                            <div class="flex-1 bg-white border border-green-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group">
                                                <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-10 -mt-10 opacity-50"></div>
                                                <div class="relative z-10">
                                                    <div class="flex items-center gap-3 mb-4">
                                                        <div class="p-2 bg-green-100 text-green-600 rounded-full"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                                        <span class="font-bold text-green-800 text-lg">Tugas Selesai</span>
                                                    </div>
                                                    <div class="pl-1">
                                                        @if(isset($totalScore))
                                                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Nilai Anda</p>
                                                            <span class="text-4xl font-black text-gray-900 tracking-tight">{{ $totalScore }}</span>
                                                        @else
                                                            <p class="text-sm text-gray-600 font-medium">Menunggu penilaian.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-1 flex flex-col justify-center gap-3">
                                                @if($currentLesson->show_results)
                                                    <a href="{{ route('front.quiz.results', [$course->slug, $currentLesson->id]) }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-white border-2 border-indigo-100 text-indigo-700 rounded-xl font-bold hover:bg-indigo-50 hover:border-indigo-200 transition shadow-sm group">
                                                        <span>Lihat Detail Jawaban</span>
                                                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                                    </a>
                                                @else
                                                    <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-xl text-yellow-800 text-sm flex gap-3 items-start"><svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg><p>Detail jawaban disembunyikan.</p></div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-white border border-indigo-100 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                                            <div class="flex gap-4 items-start">
                                                <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 shadow-sm flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                                <div><h4 class="text-lg font-bold text-gray-900">Siap Mengerjakan?</h4><p class="text-sm text-gray-600 leading-snug">Waktu pengerjaan dimulai setelah tombol ditekan.</p></div>
                                            </div>
                                            <a href="{{ route('front.quiz', [$course->slug, $currentLesson->id]) }}" class="w-full md:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2"><span>Mulai</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        {{-- ========================================== --}}
                        {{-- 2. TAMPILAN MATERI STANDAR (VIDEO/PDF/TEKS) --}}
                        {{-- ========================================== --}}
                        @else
                            
                            {{-- Header untuk Video/PDF --}}
                            @if($currentLesson->type != 'text')
                                <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">
                                        {{ $currentLesson->title }}
                                    </h1>
                                    
                                    {{-- Badge Tipe Materi (Desktop) --}}
                                    <div class="hidden md:flex items-center gap-2">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold uppercase tracking-wide border border-gray-200 flex items-center gap-1">
                                            @if($currentLesson->type == 'video') <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Video
                                            @elseif($currentLesson->type == 'pdf') <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> Dokumen
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            {{-- PLAYER --}}
                            @if($currentLesson->type != 'text')
                                <div class="relative w-full {{ $currentLesson->type == 'pdf' ? 'h-[80vh]' : 'aspect-video' }} bg-black rounded-2xl overflow-hidden shadow-2xl shadow-gray-200 border border-gray-100 mb-8 group">
                                    @if($currentLesson->type == 'video')
                                        @if($currentLesson->video_source == 'upload')
                                            <video class="w-full h-full object-contain" controls controlsList="nodownload">
                                                <source src="{{ Storage::url($currentLesson->file_path) }}" type="video/mp4" />
                                            </video>
                                        @elseif($currentLesson->video_source == 'youtube')
                                            <iframe class="absolute top-0 left-0 w-full h-full"
                                                    src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1&controls=1" 
                                                    title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                                        @endif
                                    @elseif($currentLesson->type == 'pdf')
                                        <iframe src="{{ Storage::url($currentLesson->file_path) }}" class="absolute top-0 left-0 w-full h-full" frameborder="0">
                                            <a href="{{ Storage::url($currentLesson->file_path) }}" class="text-white underline">Download PDF</a>
                                        </iframe>
                                    @endif
                                </div>
                            @else
                                {{-- HEADER ARTIKEL DENGAN GRID PATTERN (Tampilan Lama yang Bagus) --}}
                                <div class="relative w-full h-64 md:h-80 rounded-2xl overflow-hidden mb-8 shadow-sm border border-slate-200"
                                    style="background-color: #f8fafc; background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px), linear-gradient(to bottom, #e2e8f0 1px, transparent 1px); background-size: 24px 24px;">
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>

                                    <div class="absolute bottom-0 left-0 p-6 md:p-10 z-10 w-full">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-3 border border-indigo-100 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            Artikel
                                        </div>
                                        <h1 class="text-2xl md:text-4xl font-extrabold text-slate-900 leading-tight max-w-4xl">
                                            {{ $currentLesson->title }}
                                        </h1>
                                    </div>
                                </div>
                            @endif

                            {{-- Info Bar Tambahan & Deskripsi --}}
                            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-6">
                                
                                {{-- Tombol Eksternal (YouTube / Edit) --}}
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                        Tentang Materi Ini
                                    </h3>

                                    <div class="flex gap-2">
                                        @if($currentLesson->type == 'video' && $currentLesson->video_source == 'youtube')
                                            <a href="https://www.youtube.com/watch?v={{ $youtubeId }}" target="_blank" class="flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-full border border-red-100 transition-colors">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21.8 8.001c-.2-1.3-1.2-2.3-2.6-2.6C17 5 12 5 12 5s-5 0-7.2.4c-1.3.3-2.3 1.3-2.6 2.6C1.8 10.5 1.8 12 1.8 12s0 1.5.4 3.9c.2 1.3 1.2 2.3 2.6 2.6 2.2.4 7.2.4 7.2.4s5 0 7.2-.4c1.3-.3 2.3-1.3 2.6-2.6.4-2.4.4-3.9.4-3.9s0-1.5-.4-3.9zM9.5 15.5V8.5l6.5 3.5-6.5 3.5z"/></svg>
                                                <span>Buka di YouTube</span>
                                            </a>
                                        @endif
                                        
                                        @if(Auth::check() && Auth::user()->role == 'admin' && session('dev_mode'))
                                            <a href="{{ route('admin.chapters.lessons.edit', [$currentLesson->chapter_id, $currentLesson->id]) }}" class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-full hover:bg-yellow-200 transition font-bold flex items-center gap-1">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- Konten Teks --}}
                                @if($currentLesson->content)
                                    <div class="prose prose-indigo prose-sm sm:prose-base max-w-none text-gray-600 leading-relaxed">
                                        {!! $currentLesson->content !!}
                                    </div>
                                @else 
                                    <p class="text-sm text-gray-400 italic">Tidak ada deskripsi tambahan untuk materi ini.</p>
                                @endif

                            </div>

                            {{-- Tombol Selesai --}}
                            <div class="flex justify-end">
                                <form action="{{ route('front.lesson.complete', [$course->slug, $currentLesson->id]) }}" method="POST">
                                    @csrf
                                    @if(isset($isCompleted) && $isCompleted)
                                        <button type="submit" class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-bold flex items-center gap-2 transition">
                                            <span>Lanjut Materi Berikutnya</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </button>
                                    @else
                                        <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 transition transform hover:-translate-y-1 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Tandai Selesai</span>
                                        </button>
                                    @endif
                                </form>
                            </div>

                        @endif {{-- END IF TIPE MATERI --}}

                    @endif
                </div>

                {{-- KOLOM KANAN: SIDEBAR (Flush Style) --}}
                <div class="lg:col-span-4 order-2">
                    <div class="lg:sticky lg:top-24 space-y-6">
                        
                        {{-- 1. PROGRESS CARD --}}
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 relative overflow-hidden group">
                            <div class="flex justify-between items-end mb-2 relative z-10">
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Progress</span>
                                    <span class="text-3xl font-black text-gray-900 tracking-tighter">{{ $progress }}%</span>
                                </div>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $progress == 100 ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mb-4 overflow-hidden relative z-10">
                                <div class="h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden {{ $progress == 100 ? 'bg-green-500' : 'bg-indigo-600' }}" style="width: {{ $progress }}%"></div>
                            </div>

                            {{-- Sertifikat --}}
                            @if($progress == 100)
                                @php $myCert = \App\Models\Certificate::where('user_id', Auth::id())->where('course_id', $course->id)->first(); @endphp
                                <div class="mt-4 pt-4 border-t border-gray-100 relative z-10">
                                    <div class="mb-3">
                                        <h4 class="text-sm font-bold text-slate-800">Kompetensi Tercapai!</h4>
                                        <p class="text-[11px] text-slate-500">Selamat! Anda telah menyelesaikan seluruh materi.</p>
                                    </div>

                                    @if(!$myCert)
                                        <form action="{{ route('front.certificate.request', $course->id) }}" method="POST">
                                            @csrf
                                            <button class="w-full py-2.5 bg-slate-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition shadow-lg shadow-slate-200">Klaim Sertifikat</button>
                                        </form>
                                    @elseif($myCert->status == 'approved')
                                        <a href="{{ route('front.certificate.download', $myCert->id) }}" class="block w-full py-2.5 bg-green-600 text-white rounded-lg text-xs font-bold uppercase tracking-wider text-center hover:bg-green-700 transition shadow-lg shadow-green-200">Download Sertifikat</a>
                                    @else
                                        <button disabled class="w-full py-2.5 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold uppercase tracking-wider cursor-not-allowed">Menunggu Verifikasi</button>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- 2. DAFTAR MATERI (Flush Design) --}}
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" x-data="{ open: true }">
                            <button @click="open = !open" class="w-full text-left p-5 bg-gray-50/80 border-b border-gray-100 flex justify-between items-center hover:bg-gray-100 transition lg:cursor-default">
                                <h3 class="font-bold text-gray-900">Daftar Materi</h3>
                                <div class="lg:hidden text-gray-400" :class="{'rotate-180': open}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>

                            <div x-show="open" class="max-h-[60vh] lg:max-h-[calc(100vh-300px)] overflow-y-auto scrollbar-hide">
                                @foreach($course->chapters as $index => $chapter)
                                    <div class="border-b border-gray-100 last:border-0">
                                        <div class="px-5 py-3 bg-white sticky top-0 z-10 border-b border-gray-50">
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Bab {{ $index + 1 }}: {{ $chapter->title }}</h4>
                                        </div>
                                        <div class="p-2 space-y-1 bg-white">
                                            @foreach($chapter->lessons as $lesson)
                                                @php 
                                                    $isActive = isset($currentLesson) && $currentLesson->id == $lesson->id;
                                                    $isDone = \App\Models\LessonCompletion::where('user_id', Auth::id())->where('lesson_id', $lesson->id)->exists();
                                                @endphp
                                                <a href="{{ route('front.learning', [$course->slug, $lesson->id]) }}" 
                                                   class="flex items-center justify-between p-3 rounded-xl transition group {{ $isActive ? 'bg-indigo-50 ring-1 ring-indigo-200' : 'hover:bg-gray-50' }}">
                                                    <div class="flex items-center gap-3 overflow-hidden">
                                                        <div class="flex-shrink-0 {{ $isActive ? 'text-indigo-600' : ($isDone ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-400') }}">
                                                            @if($isDone)
                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                            @else
                                                                @if($lesson->type == 'video') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                @elseif($lesson->type == 'assignment') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                                @else <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> @endif
                                                            @endif
                                                        </div>
                                                        <span class="text-sm font-medium truncate {{ $isActive ? 'text-indigo-900' : ($isDone ? 'text-gray-500' : 'text-gray-700') }}">
                                                            {{ $lesson->title }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>