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
    </style>
    @endpush

    {{-- Logic PHP Helper --}}
    @php
        // 1. Helper YouTube
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
        
        // 2. Helper Waktu Baca Artikel
        $readingTime = 0;
        $wordCount = 0;
        if($currentLesson && $currentLesson->type == 'text') {
            $textClean = strip_tags($currentLesson->content);
            $wordCount = str_word_count($textClean);
            $readingTime = ceil($wordCount / 200); // Estimasi 200 kata per menit
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
            
            {{-- LAYOUT GRID UTAMA --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                
                {{-- === KOLOM KIRI: KONTEN BELAJAR === --}}
                <div class="lg:col-span-8 order-1 lg:order-1">
                    
                    {{-- 1. TAMPILAN KHUSUS ARTIKEL (PREMIUM READER MODE) --}}
                    @if($currentLesson->type == 'text')
                        
                        <div class="bg-white px-2 md:px-6">
                            {{-- Header Artikel --}}
                            <div class="mb-8 border-b border-gray-100 pb-8">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider mb-4 border border-blue-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                    Artikel
                                </div>
                                <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-4">
                                    {{ $currentLesson->title }}
                                </h1>
                                <div class="flex items-center gap-4 text-sm text-gray-500 font-medium">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $readingTime }} min baca
                                    </span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span>{{ $wordCount }} Kata</span>
                                </div>
                            </div>

                            {{-- Isi Artikel --}}
                            <article class="ck-content prose prose-lg prose-slate prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-700 prose-p:leading-loose prose-a:text-indigo-600 hover:prose-a:text-indigo-500 prose-img:rounded-2xl prose-img:shadow-md max-w-none">
                                {!! $currentLesson->content !!}
                            </article>

                            {{-- Footer Artikel --}}
                            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col items-center">
                                <p class="text-slate-400 text-sm italic mb-6">Anda telah mencapai akhir artikel.</p>
                                
                                <form action="{{ route('front.lesson.complete', [$course->slug, $currentLesson->id]) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-full font-bold shadow-xl transition transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span>{{ isset($isCompleted) && $isCompleted ? 'Lanjut ke Materi Berikutnya' : 'Saya Selesai Membaca' }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    {{-- 2. JIKA TIPE TUGAS (ASSIGNMENT) --}}
                    @elseif($currentLesson->type == 'assignment')
                        
                        <div class="bg-white border border-gray-100 rounded-3xl shadow-lg overflow-hidden">
                            
                            {{-- Header dengan Gradient & Pattern --}}
                            <div class="relative bg-gradient-to-br from-indigo-600 to-violet-700 p-8 text-white text-center overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-full opacity-10" 
                                     style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;">
                                </div>
                                
                                <div class="relative z-10">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl mb-4 border border-white/30 shadow-inner">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                    <h2 class="text-3xl font-black tracking-tight mb-2">{{ $currentLesson->title }}</h2>
                                    <p class="text-indigo-100 text-sm font-medium">Tugas / Kuis Evaluasi</p>
                                </div>
                            </div>

                            <div class="p-8">
                                
                                {{-- Instruksi / Konten --}}
                                <div class="prose prose-indigo prose-sm max-w-none text-gray-600 mb-8 text-center bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                    @if($currentLesson->content)
                                        {!! $currentLesson->content !!}
                                    @else
                                        <p class="italic text-gray-400">Tidak ada instruksi khusus. Kerjakan soal dengan teliti.</p>
                                    @endif
                                </div>

                                {{-- LOGIKA STATUS PENGERJAAN --}}
                                @if(isset($hasSubmitted) && $hasSubmitted)
                                    
                                    {{-- A. SUDAH MENGERJAKAN (RESULT CARD) --}}
                                    <div class="bg-gradient-to-b from-green-50 to-white border border-green-100 rounded-2xl p-8 text-center max-w-md mx-auto shadow-sm">
                                        
                                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-4 ring-4 ring-green-50">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        
                                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Tugas Selesai!</h3>
                                        <p class="text-gray-500 text-sm mb-6">Terima kasih telah mengerjakan.</p>
                                        
                                        <div class="bg-white border border-green-100 rounded-xl p-4 mb-6 shadow-sm">
                                            <p class="text-green-600 text-xs font-bold uppercase tracking-wider mb-1">Skor Pilihan Ganda</p>
                                            <p class="text-5xl font-black text-slate-800 tracking-tighter">{{ $totalScore ?? 0 }}</p>
                                        </div>

                                        <div class="space-y-3">
                                            @if($currentLesson->show_results)
                                                <a href="{{ route('front.quiz.results', [$course->slug, $currentLesson->id]) }}" 
                                                class="block w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                    Lihat Detail & Pembahasan
                                                </a>
                                                <p class="text-xs text-gray-400 mt-2">Klik tombol di atas untuk evaluasi mandiri.</p>
                                            @else
                                                <div class="bg-gray-100 p-3 rounded-lg text-xs text-gray-500 font-medium">
                                                    Detail pembahasan disembunyikan oleh instruktur.
                                                </div>
                                            @endif
                                            
                                            <button disabled class="w-full py-3 bg-gray-50 text-gray-400 rounded-xl font-bold border border-gray-200 cursor-not-allowed text-sm">
                                                Anda sudah mengerjakan tugas ini
                                            </button>
                                        </div>
                                    </div>

                                @else
                                    
                                    {{-- B. BELUM MENGERJAKAN (START CARD) --}}
                                    <div class="max-w-md mx-auto">
                                        
                                        <div class="flex items-start gap-4 p-4 bg-yellow-50 border border-yellow-100 rounded-xl mb-8">
                                            <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            <div>
                                                <h4 class="font-bold text-yellow-800 text-sm mb-1">Perhatian Sebelum Mulai</h4>
                                                <ul class="text-xs text-yellow-700 list-disc list-inside space-y-1">
                                                    <li>Pastikan koneksi internet Anda stabil.</li>
                                                    <li>Jangan memuat ulang (refresh) halaman saat mengerjakan.</li>
                                                    <li>Pengerjaan tidak dapat dibatalkan setelah dimulai.</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <a href="{{ route('front.quiz', [$course->slug, $currentLesson->id]) }}" 
                                            class="group relative block w-full">
                                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-200"></div>
                                            <div class="relative flex items-center justify-center gap-3 px-8 py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-lg transition-all transform group-hover:-translate-y-0.5">
                                                <span>Mulai Kerjakan Tugas</span>
                                                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                            </div>
                                        </a>
                                        
                                    </div>

                                @endif

                            </div>
                        </div>

                    {{-- 3. JIKA TIPE VIDEO / PDF (STANDARD) --}}
                    @else
                        
                        {{-- Player Container --}}
                        <div class="relative w-full {{ $currentLesson->type == 'pdf' ? 'h-[50vh] md:h-[80vh]' : 'aspect-video' }} bg-black rounded-2xl overflow-hidden shadow-lg border border-gray-100 z-10 mb-6">
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

                        {{-- Info Bar Video/PDF --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-6 mb-6 gap-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $currentLesson->title }}</h1>
                                <div class="flex items-center gap-3 text-sm text-gray-500">
                                    <span class="flex items-center gap-1 bg-indigo-50 text-indigo-700 px-2.5 py-0.5 rounded font-bold text-xs uppercase tracking-wide">
                                        {{ $currentLesson->type == 'video' ? 'Video' : 'Dokumen' }}
                                    </span>
                                    @if($currentLesson->type == 'video' && $currentLesson->video_source == 'youtube')
                                        <a href="https://www.youtube.com/watch?v={{ $youtubeId }}" target="_blank" class="hover:text-red-600 flex items-center gap-1 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                            Tonton di YouTube
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            @if(Auth::check() && Auth::user()->role == 'admin' && session('dev_mode'))
                                <a href="{{ route('admin.chapters.lessons.edit', [$currentLesson->chapter_id, $currentLesson->id]) }}" class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-md hover:bg-yellow-200 transition font-bold flex items-center gap-1 self-start sm:self-center">
                                    Edit Materi
                                </a>
                            @endif
                        </div>

                        {{-- DESKRIPSI VIDEO: HANYA MUNCUL JIKA ADA ISI --}}
                        @if(!empty($currentLesson->content))
                            <div class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3">Tentang Materi Ini</h3>
                                <div class="prose prose-sm prose-slate max-w-none text-slate-600">
                                    {!! $currentLesson->content !!}
                                </div>
                            </div>
                        @endif

                        {{-- Tombol Aksi Video/PDF --}}
                        <div class="mt-8 flex justify-end">
                            <form action="{{ route('front.lesson.complete', [$course->slug, $currentLesson->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ isset($isCompleted) && $isCompleted ? 'Lanjut Materi Berikutnya' : 'Tandai Selesai & Lanjut' }}
                                </button>
                            </form>
                        </div>

                    @endif

                </div>

                {{-- === KOLOM KANAN: SIDEBAR & PROGRESS === --}}
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

                            {{-- === LOGIC TOMBOL SERTIFIKAT (REDESIGN BARU) === --}}
{{-- === CARD SERTIFIKAT SIMPEL (REPLACED) === --}}
                            @if($progress == 100)
                                @php
                                    $myCert = \App\Models\Certificate::where('user_id', Auth::id())
                                                    ->where('course_id', $course->id)->first();
                                @endphp

                                <div class="mt-4 pt-4 border-t border-gray-100 relative z-10">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm">Selamat!</h4>
                                            <p class="text-[11px] text-gray-500 leading-tight">Kursus selesai. Silakan unduh sertifikat Anda.</p>
                                        </div>
                                    </div>

                                    @if(!$myCert)
                                        <form action="{{ route('front.certificate.request', $course->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                                Klaim Sertifikat
                                            </button>
                                        </form>
                                    @elseif($myCert->status == 'pending')
                                        <div class="w-full py-2 bg-yellow-50 text-yellow-700 text-xs font-bold rounded-lg border border-yellow-200 text-center cursor-wait">
                                            Verifikasi Admin...
                                        </div>
                                    @elseif($myCert->status == 'approved')
                                        <a href="{{ route('front.certificate.download', $myCert->id) }}" target="_blank" class="block w-full py-2 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition shadow-sm text-center">
                                            Download PDF
                                        </a>
                                    @elseif($myCert->status == 'rejected')
                                        <div class="w-full py-2 bg-red-50 text-red-700 text-xs font-bold rounded-lg border border-red-200 text-center">
                                            Ditolak
                                        </div>
                                    @endif
                                </div>
                            @endif
                            {{-- === END CARD SERTIFIKAT === --}}

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