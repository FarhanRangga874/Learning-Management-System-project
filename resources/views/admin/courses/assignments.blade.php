<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.index') }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </x-slot>

    <div class="pb-24 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            {{-- Header Title --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Daftar Tugas & Kuis</h1>
                    <p class="text-slate-500 text-sm mt-1">Kelola penilaian tugas siswa untuk kursus <strong>{{ $course->title }}</strong>.</p>
                </div>
                
                {{-- Stat Card Kecil --}}
                <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Total Tugas</p>
                        <p class="text-lg font-bold text-slate-900 leading-none">{{ $assignments->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            @if($assignments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($assignments as $assignment)
                        <div class="group bg-white border border-slate-200 rounded-xl p-5 hover:shadow-lg hover:border-indigo-200 transition duration-300 relative overflow-hidden flex flex-col h-full">
                            
                            {{-- Decorative Blob --}}
                            <div class="absolute top-0 right-0 -mt-6 -mr-6 w-24 h-24 bg-gradient-to-br from-indigo-50 to-white rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>

                            <div class="relative z-10 flex flex-col h-full">
                                
                                {{-- Badge Bab --}}
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ Str::limit($assignment->chapter->title, 20) }}
                                    </span>
                                    
                                    {{-- Ikon Tipe Tugas --}}
                                    <div class="text-slate-300 group-hover:text-indigo-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </div>
                                </div>

                                {{-- Judul Tugas --}}
                                <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-indigo-700 transition-colors line-clamp-2">
                                    {{ $assignment->title }}
                                </h3>

                                {{-- Deskripsi Singkat (Opsional jika ada) --}}
                                <p class="text-sm text-slate-500 line-clamp-2 mb-4 flex-1">
                                    {{ Str::limit(strip_tags($assignment->content), 80) }}
                                </p>

                                {{-- Footer Card --}}
                                <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span>Peserta</span>
                                    </div>

                                    <a href="{{ route('admin.lessons.users.index', $assignment->id) }}" class="inline-flex items-center gap-1.5 bg-indigo-600 text-white text-xs font-bold px-3 py-2 rounded-lg hover:bg-indigo-700 hover:shadow-md transition shadow-sm group-hover:translate-x-1">
                                        <span>Periksa Nilai</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-12 text-center max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Belum Ada Tugas</h3>
                    <p class="text-slate-500 text-sm mt-2 max-w-md mx-auto leading-relaxed">
                        Kursus ini belum memiliki materi dengan tipe tugas atau kuis. Silakan tambahkan materi tugas melalui menu <strong>Kelola Materi</strong>.
                    </p>
                    <a href="{{ route('admin.courses.chapters.index', $course->id) }}" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-white border border-indigo-200 text-indigo-700 font-bold rounded-lg hover:bg-indigo-50 hover:border-indigo-300 transition text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Kelola Materi Kursus
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>