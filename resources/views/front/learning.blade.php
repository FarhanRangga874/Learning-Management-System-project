<x-app-layout>
    {{-- Hapus <style> manual disini agar tidak bentrok dengan app.css --}}
    @push('styles')
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush

    @php
        $youtubeId = '';
        if($currentLesson->type == 'video' && $currentLesson->video_source == 'youtube') {
            $url = $currentLesson->file_path; // Menggunakan file_path sesuai database
            $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
            if (preg_match($pattern, $url, $match)) {
                $youtubeId = $match[1];
            } else {
                $youtubeId = $url; 
            }
        }
    @endphp

    <div class="bg-white min-h-screen">
        
        {{-- Header / Navigation (Sticky) --}}
        <div class="border-b border-gray-100 bg-white sticky top-0 z-30 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    <div class="flex items-center gap-4">
                        {{-- TOMBOL KEMBALI --}}
                        <a href="{{ route('front.details', $course->slug) }}" 
                           class="p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 transition shadow-sm" 
                           title="Kembali ke Detail Kursus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>

                        {{-- Judul Kursus di Header (Hidden on mobile) --}}
                        <div class="hidden md:block">
                            <div class="text-xs text-gray-400 font-bold uppercase tracking-wider">Sedang Mempelajari</div>
                            <div class="text-sm font-bold text-gray-900 truncate max-w-xs leading-tight">
                                {{ $course->title }}
                            </div>
                        </div>
                    </div>

                    <div class="md:hidden text-sm font-bold text-gray-900 truncate max-w-[150px]">
                        {{ $course->title }}
                    </div>

                </div>
            </div>
        </div>

        {{-- Main Content Wrapper --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            
            {{-- Header Section --}}
            <div class="mb-8">
                <nav class="flex text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('front.index') }}" class="hover:text-indigo-600">Home</a></li>
                        <li><span class="text-gray-300">/</span></li>
                        <li><span class="text-gray-500">{{ $course->category->name }}</span></li>
                        <li><span class="text-gray-300">/</span></li>
                        <li class="text-gray-900 font-medium truncate" aria-current="page">{{ $course->title }}</li>
                    </ol>
                </nav>
                
                <div class="flex items-center gap-4">
                    {{-- Ikon Tipe Materi --}}
                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm border border-indigo-100">
                        @if($currentLesson->type == 'video')
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        @elseif($currentLesson->type == 'pdf')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        @endif
                    </div>

                    {{-- Judul H1 --}}
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        {{ $currentLesson->title }}
                    </h1>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                
                {{-- LEFT COLUMN: CONTENT --}}
                <div class="lg:col-span-8 order-2 lg:order-1">
                    
                    {{-- CONTAINER UTAMA (Video / PDF) --}}
                    <div class="relative w-full {{ $currentLesson->type == 'pdf' ? 'aspect-[3/4] sm:aspect-[4/3] lg:aspect-[3/4] h-[80vh]' : 'aspect-video' }} bg-black rounded-2xl overflow-hidden shadow-lg border border-gray-100 z-10 mb-8">
                        
                        @if($currentLesson->type == 'video')
                            @if($currentLesson->video_source == 'upload')
                                <video class="w-full h-full object-contain" controls controlsList="nodownload">
                                    <source src="{{ Storage::url($currentLesson->file_path) }}" type="video/mp4" />
                                    Browser Anda tidak mendukung tag video.
                                </video>
                            @elseif($currentLesson->video_source == 'youtube')
                                <iframe class="absolute top-0 left-0 w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1&controls=1&showinfo=0" 
                                    title="YouTube video player" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            @endif

                        @elseif($currentLesson->type == 'pdf')
                            <iframe src="{{ Storage::url($currentLesson->file_path) }}" 
                                    class="absolute top-0 left-0 w-full h-full" 
                                    frameborder="0">
                                Browser Anda tidak mendukung iframe PDF. 
                                <a href="{{ Storage::url($currentLesson->file_path) }}" class="text-white underline">Download PDF</a>
                            </iframe>

                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 text-gray-400">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="font-bold tracking-widest text-sm uppercase">Materi Bacaan</span>
                            </div>
                        @endif
                    </div>

                    {{-- INFO BAR (CLEAN LAYOUT + TOMBOL YOUTUBE) --}}
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6 mb-6">
                        <div class="text-sm text-gray-500">
                            @if($currentLesson->type == 'video')
                                <div class="flex items-center gap-3 flex-wrap">
                                    
                                    {{-- Badge Video --}}
                                    <span class="font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                                        Video Pembelajaran
                                    </span>
                                    
                                    {{-- TOMBOL BUKA DI YOUTUBE --}}
                                    @if($currentLesson->video_source == 'youtube')
                                        <a href="https://www.youtube.com/watch?v={{ $youtubeId }}" target="_blank" rel="noopener noreferrer" 
                                           class="flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-full border border-red-100 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                            Buka di YouTube 
                                            <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    @endif

                                </div>
                            @elseif($currentLesson->type == 'pdf')
                                <span class="font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                                    Dokumen PDF
                                </span>
                            @else
                                <span class="font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                                    Bacaan Teks
                                </span>
                            @endif
                        </div>
                        
                        {{-- Tombol Edit untuk Admin --}}
                        @if(Auth::check() && Auth::user()->role == 'admin' && session('dev_mode'))
                        <div>
                            <a href="{{ route('admin.chapters.lessons.edit', [$currentLesson->chapter_id, $currentLesson->id]) }}" class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-md hover:bg-yellow-200 transition font-bold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Materi
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- Lesson Content with Drop Cap --}}
                    @if($currentLesson->content)
                    <div class="ck-content prose prose-lg prose-slate max-w-none prose-headings:font-bold prose-a:text-indigo-600 hover:prose-a:text-indigo-500 prose-img:rounded-xl prose-drop-cap">
                        {!! $currentLesson->content !!}
                    </div>
                    @endif
                </div>

                {{-- RIGHT COLUMN: SIDEBAR --}}
                <div class="lg:col-span-4 order-1 lg:order-2">
                    <div class="sticky top-24">
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                <h3 class="text-lg font-bold text-gray-900">Daftar Materi</h3>
                                <span class="text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200 px-2 py-0.5 rounded-full">
                                    {{ $course->chapters->sum(function($c){ return $c->lessons->count(); }) }} Materi
                                </span>
                            </div>
                            <div class="max-h-[70vh] overflow-y-auto scrollbar-hide bg-white p-5">
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
                                                            @else
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            @endif
                                                        </div>
                                                        <span class="text-sm truncate w-full">{{ $lesson->title }}</span>
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