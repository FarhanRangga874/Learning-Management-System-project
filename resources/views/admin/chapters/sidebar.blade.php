<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-slate-900 text-sm">Kurikulum</h3>
        <a href="{{ route('admin.courses.chapters.index', $course->id) }}" class="text-xs bg-slate-100 px-2 py-1 rounded hover:bg-slate-200 text-slate-600 transition">+ Bab Baru</a>
    </div>

    <div class="space-y-3">
        @foreach($chapters as $index => $c)
            @php $isActiveChapter = (isset($chapter) && $chapter->id == $c->id); @endphp

            <div class="border border-slate-100 rounded-lg overflow-hidden bg-white shadow-sm" x-data="{ expanded: {{ $isActiveChapter ? 'true' : 'false' }} }">
                <div class="flex justify-between p-3 bg-slate-50 cursor-pointer hover:bg-slate-100 transition" @click="expanded = !expanded">
                    <span class="font-bold text-xs text-slate-700 truncate w-3/4">{{ $index + 1 }}. {{ $c->title }}</span>
                    <svg class="w-4 h-4 text-slate-400" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </div>

                <div x-show="expanded" class="bg-white border-t border-slate-100 p-2 space-y-1">
                    @foreach($c->lessons as $l)
                        <div class="flex items-center justify-between p-2 rounded hover:bg-slate-50 group">
                            <a href="{{ route('admin.chapters.lessons.edit', [$c->id, $l->id]) }}" class="flex items-center gap-2 overflow-hidden flex-1">
                                <div class="text-slate-400">
                                    @if($l->type == 'assignment')
                                        <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                    @endif
                                </div>
                                <span class="text-xs truncate text-slate-600">{{ $l->title }}</span>
                            </a>
                            <form action="{{ route('admin.chapters.lessons.destroy', [$c->id, $l->id]) }}" method="POST" onsubmit="return confirm('Hapus?')" class="opacity-0 group-hover:opacity-100 transition">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-red-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></button>
                            </form>
                        </div>
                    @endforeach
                    
                    {{-- SATU TOMBOL TAMBAH --}}
                    <a href="{{ route('admin.chapters.lessons.create', $c->id) }}" class="block w-full py-2 border border-dashed border-indigo-200 bg-indigo-50/30 rounded text-center text-xs font-bold text-indigo-600 hover:bg-indigo-50 hover:border-indigo-400 transition mt-2">
                        + Tambah Materi
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>