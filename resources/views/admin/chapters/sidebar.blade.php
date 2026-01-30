<div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
    
    {{-- Header Sidebar --}}
    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h3 class="font-bold text-slate-800 text-sm">Kurikulum Kursus</h3>
        <a href="{{ route('admin.courses.chapters.index', $course->id) }}" class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2.5 py-1.5 rounded-lg border border-indigo-100 hover:bg-indigo-100 transition flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Bab Baru
        </a>
    </div>

    {{-- List Bab --}}
    <div class="p-4 space-y-3 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
        @foreach($chapters as $index => $c)
            @php 
                $isActiveChapter = (isset($chapter) && $chapter->id == $c->id); 
            @endphp

            <div class="border border-slate-200 rounded-lg overflow-hidden transition-all duration-200 {{ $isActiveChapter ? 'ring-2 ring-indigo-500/10 shadow-md' : 'shadow-sm' }}" 
                 x-data="{ expanded: {{ $isActiveChapter ? 'true' : 'false' }} }">
                
                {{-- Header Item Bab --}}
                <div class="flex justify-between items-center p-3 cursor-pointer transition hover:bg-slate-50" 
                     :class="expanded ? 'bg-slate-50 border-b border-slate-100' : 'bg-white'"
                     @click="expanded = !expanded">
                    
                    <div class="flex items-center gap-3 overflow-hidden">
                        <span class="flex items-center justify-center w-6 h-6 rounded bg-slate-100 text-[10px] font-bold text-slate-500 border border-slate-200 shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <span class="font-bold text-xs text-slate-700 truncate select-none">{{ $c->title }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400 bg-slate-100 px-1.5 rounded">{{ $c->lessons->count() }} Materi</span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                </div>

                {{-- Body (Daftar Materi) --}}
                <div x-show="expanded" x-collapse>
                    <div class="bg-white p-2 space-y-1">
                        @forelse($c->lessons as $l)
                            @php
                                $isActiveLesson = (isset($lesson) && $lesson->id == $l->id);
                            @endphp

                            <div class="group relative flex flex-col p-2 rounded-lg transition-colors border border-transparent {{ $isActiveLesson ? 'bg-indigo-50 border-indigo-100' : 'hover:bg-slate-50 hover:border-slate-100' }}">
                                
                                {{-- Baris Utama: Ikon & Judul --}}
                                <div class="flex items-center justify-between gap-2">
                                    <a href="{{ route('admin.chapters.lessons.edit', [$c->id, $l->id]) }}" class="flex items-center gap-2.5 overflow-hidden flex-1 group-hover:text-indigo-600 transition-colors">
                                        
                                        {{-- Ikon Tipe Materi --}}
                                        <div class="shrink-0 {{ $isActiveLesson ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' }}">
                                            @if($l->type == 'assignment')
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                            @elseif($l->type == 'video')
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                            @elseif($l->type == 'pdf')
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                            @else
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg> 
                                            @endif
                                        </div>

                                        <span class="text-xs truncate font-medium {{ $isActiveLesson ? 'text-indigo-700' : 'text-slate-600' }}">
                                            {{ $l->title }}
                                        </span>
                                    </a>
                                    
                                    {{-- Tombol Hapus (Hover Only) --}}
                                    <form action="{{ route('admin.chapters.lessons.destroy', [$c->id, $l->id]) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')" class="lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-300 hover:text-red-500 p-1 rounded hover:bg-red-50 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                        </button>
                                    </form>
                                </div>

                                {{-- Baris Bawah: Tombol GRADING (Khusus Assignment) --}}
                                @if($l->type == 'assignment')
                                    <div class="mt-2 pl-6">
                                        <a href="{{ route('admin.lessons.users.index', $l->id) }}" class="flex items-center justify-center gap-1.5 text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1.5 rounded-md hover:bg-emerald-100 hover:text-emerald-700 transition w-full shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Penilaian Tugas
                                        </a>
                                    </div>
                                @endif

                            </div>
                        @empty
                            <div class="text-center py-3 border border-dashed border-slate-200 rounded-lg bg-slate-50">
                                <p class="text-[10px] text-slate-400 italic">Belum ada materi.</p>
                            </div>
                        @endforelse
                        
                        {{-- Tombol Tambah Materi --}}
                        <a href="{{ route('admin.chapters.lessons.create', $c->id) }}" class="flex items-center justify-center gap-1 w-full py-2 border border-dashed border-indigo-200 bg-indigo-50/50 rounded-lg text-center text-xs font-bold text-indigo-600 hover:bg-indigo-50 hover:border-indigo-400 hover:text-indigo-700 transition mt-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Materi
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>