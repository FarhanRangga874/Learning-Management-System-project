<div class="bg-white rounded-xl shadow-sm border border-slate-200 h-full flex flex-col overflow-hidden">
    
    {{-- Header Sidebar --}}
    <div class="px-5 py-4 border-b border-slate-100 bg-white flex justify-between items-center sticky top-0 z-20">
        <div>
            <h3 class="font-bold text-slate-800 text-base">Kurikulum Kursus</h3>
            <p class="text-[11px] text-slate-400 mt-0.5 font-medium">
                {{ $course->chapters->count() }} Bab • {{ $course->chapters->flatMap->lessons->count() }} Materi
            </p>
        </div>
    </div>

    {{-- List Content --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar bg-slate-50/30 p-4 space-y-3" style="max-height: calc(100vh - 200px);">
        @foreach($course->chapters as $chap)
            @php
                // Cek apakah ini Bab target saat Create?
                $isTargetChapter = isset($chapter) && $chapter->id == $chap->id;
                
                // Cek apakah sedang di halaman create
                $isCreating = request()->routeIs('admin.chapters.lessons.create');

                // Cek apakah bab ini harus terbuka (ada materi aktif atau sedang create di sini)
                // Jika sedang create di bab ini, otomatis buka
                // Jika sedang edit materi di bab ini, otomatis buka
                $isActiveChapter = ($isTargetChapter && $isCreating) || $chap->lessons->contains('id', $lesson->id ?? null);
            @endphp

            {{-- AlpineJS Accordion State --}}
            <div x-data="{ open: {{ $isActiveChapter ? 'true' : 'false' }} }" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm transition-all duration-200 hover:shadow-md">
                
                {{-- Header Bab (Clickable) --}}
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 bg-slate-50 hover:bg-slate-100 transition-colors text-left focus:outline-none group">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-md bg-white border border-slate-200 text-slate-500 text-xs font-bold shadow-sm group-hover:border-indigo-200 group-hover:text-indigo-600 transition-colors">
                            {{ $loop->iteration }}
                        </span>
                        <h4 class="font-bold text-slate-700 text-sm truncate pr-2 group-hover:text-slate-900 transition-colors" title="{{ $chap->title }}">
                            {{ $chap->title }}
                        </h4>
                    </div>
                    {{-- Icon Chevron --}}
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 group-hover:text-slate-600" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                {{-- List Materi (Dropdown Content) --}}
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-2 space-y-1 bg-white border-t border-slate-100">
                     
                    @foreach($chap->lessons as $less)
                        @php
                            $isActive = isset($lesson) && $lesson->id == $less->id;
                            $isNew = session('created_lesson_id') == $less->id;
                        @endphp

                        <a href="{{ route('admin.chapters.lessons.edit', [$chap->id, $less->id]) }}" 
                           id="lesson-{{ $less->id }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 group relative overflow-hidden
                           {{ $isActive 
                                ? 'bg-indigo-50 text-indigo-700 font-medium' 
                                : ($isNew ? 'bg-green-50 text-green-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900') 
                           }}">
                            
                            {{-- Active Indicator Border Left --}}
                            @if($isActive)
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-l-lg"></div>
                            @endif

                            {{-- Icon --}}
                            <div class="shrink-0 {{ $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-500' }}">
                                @if($less->type == 'video')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($less->type == 'text')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                @elseif($less->type == 'pdf')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                @elseif($less->type == 'assignment')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                @endif
                            </div>

                            <span class="truncate w-full">{{ $less->title }}</span>
                            
                            @if($isNew)
                                <span class="ml-auto text-[9px] font-bold bg-green-200 text-green-800 px-1.5 py-0.5 rounded shadow-sm animate-pulse">NEW</span>
                            @endif
                        </a>
                    @endforeach

                    {{-- Placeholder Sedang Menambah (Hanya muncul di Bab Target) --}}
                    @if($isTargetChapter && $isCreating)
                        <div id="target-placeholder" class="px-3 py-2.5 rounded-lg border border-dashed border-indigo-300 bg-indigo-50 flex items-center gap-3 animate-pulse m-1">
                            <div class="w-4 h-4 rounded-full border-2 border-indigo-400 border-t-transparent animate-spin shrink-0"></div>
                            <span class="text-xs font-bold text-indigo-500 italic truncate">Materi Baru (Sedang Dibuat)...</span>
                        </div>
                    @endif

                    {{-- Tombol Tambah --}}
                    <div class="pt-2 px-1 pb-1">
                        <a href="{{ route('admin.chapters.lessons.create', $chap->id) }}" 
                           class="flex items-center justify-center gap-2 w-full py-2 rounded-lg border border-dashed border-slate-300 text-xs font-bold text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all group/add">
                           <div class="bg-slate-100 rounded-full p-0.5 group-hover/add:bg-indigo-100 group-hover/add:text-indigo-600 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                           </div>
                           Tambah Materi
                        </a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- SCRIPT: Auto Scroll Logic --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Scroll ke item baru dibuat
        @if(session('created_lesson_id'))
            const newLesson = document.getElementById('lesson-{{ session("created_lesson_id") }}');
            if (newLesson) {
                setTimeout(() => {
                    newLesson.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            }
        @endif

        // 2. Scroll ke placeholder saat create
        @if(request()->routeIs('admin.chapters.lessons.create'))
            const placeholder = document.getElementById('target-placeholder');
            if (placeholder) {
                setTimeout(() => {
                    placeholder.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
        @endif
    });
</script>