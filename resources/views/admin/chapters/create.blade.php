<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Bab Kursus') }}
        </h2>
    </x-slot>

    <div class="pb-24 bg-slate-50 min-h-screen">
        
        <form method="POST" action="{{ route('admin.courses.chapters.store', $course) }}">
            @csrf

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                
                {{-- Header Judul Halaman --}}
                <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Tambah Bab Baru</h1>
                        <p class="text-slate-500 text-sm mt-1">Buat bab baru dan tambahkan materi awal sekaligus.</p>
                    </div>
                    <a href="{{ route('admin.courses.chapters.index', $course) }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Daftar Bab
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- ==================== KOLOM KIRI (8/12): FORM UTAMA ==================== --}}
                    <div class="lg:col-span-8 space-y-8">
                        
                        {{-- CARD 1: INPUT JUDUL BAB --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 md:p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">Detail Bab</h3>
                                    <p class="text-xs text-slate-500">Masukkan nama bab yang deskriptif.</p>
                                </div>
                            </div>
                            
                            <div>
                                <x-input-label for="title" :value="__('Judul Bab')" class="mb-2" />
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                                    class="block w-full px-4 py-3 text-base text-slate-900 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400 transition shadow-sm" 
                                    placeholder="Contoh: Bab 1 - Pengenalan Dasar">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                        </div>

                        {{-- CARD 2: INPUT MATERI (DYNAMIC LIST) --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 md:p-8" x-data="{ lessons: [] }">
                            <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-800">Materi Pembelajaran</h3>
                                        <p class="text-xs text-slate-500">Tambahkan materi awal untuk bab ini (Opsional).</p>
                                    </div>
                                </div>
                                
                                {{-- Tombol Tambah Materi --}}
                                <button type="button" @click="lessons.push({ title: '' })" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg hover:bg-emerald-100 transition border border-emerald-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Materi
                                </button>
                            </div>

                            <div class="space-y-3">
                                {{-- Loop Input Materi --}}
                                <template x-for="(lesson, index) in lessons" :key="index">
                                    <div class="flex gap-3 items-center group animate-fade-in-down">
                                        <span class="text-slate-400 font-bold text-sm w-6 text-center" x-text="index + 1 + '.'"></span>
                                        
                                        <input type="text" name="lessons[]" x-model="lesson.title" required
                                            class="block w-full px-4 py-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-400 transition" 
                                            placeholder="Judul Materi...">
                                        
                                        <button type="button" @click="lessons.splice(index, 1)" 
                                            class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>

                                {{-- Empty State (Jika belum ada materi ditambah) --}}
                                <div x-show="lessons.length === 0" class="text-center py-8 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl">
                                    <p class="text-slate-400 text-sm mb-2">Belum ada materi yang ditambahkan.</p>
                                    <p class="text-xs text-slate-400">Klik tombol <span class="font-bold text-emerald-600">"Tambah Materi"</span> di pojok kanan atas.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ==================== KOLOM KANAN (4/12): SIDEBAR PREVIEW ==================== --}}
                    <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                        
                        {{-- 1. KARTU KURSUS --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                            <div class="h-32 bg-slate-100 relative group overflow-hidden">
                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition transform group-hover:scale-105 duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-3 left-3 right-3">
                                    <span class="inline-block px-2 py-1 bg-indigo-600 text-white text-[10px] font-bold rounded uppercase tracking-wider mb-1">
                                        {{ $course->category->name }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">Menambahkan ke:</p>
                                <h4 class="text-base font-bold text-slate-900 leading-snug">{{ $course->title }}</h4>
                            </div>
                        </div>

                        {{-- 2. STRUKTUR BAB & KONTEN SAAT INI --}}
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
                            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center justify-between">
                                <span>Preview Struktur</span>
                                <span class="text-xs font-normal text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">{{ $course->chapters->count() }} Bab</span>
                            </h3>
                            
                            <div class="space-y-4 relative">
                                {{-- Garis Vertikal --}}
                                <div class="absolute left-[11px] top-2 bottom-6 w-0.5 bg-slate-100 -z-0"></div>

                                @foreach($course->chapters as $index => $chapter)
                                    <div class="relative z-10" x-data="{ expanded: false }">
                                        
                                        {{-- Judul Bab (Accordion Header) --}}
                                        <div @click="expanded = !expanded" class="flex items-center gap-3 mb-2 cursor-pointer hover:bg-slate-50 p-1.5 rounded-lg -ml-1.5 transition">
                                            <span class="flex flex-shrink-0 items-center justify-center w-6 h-6 rounded-full bg-white border-2 border-slate-200 text-[10px] font-bold text-slate-500">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="text-sm font-bold text-slate-800 truncate flex-1">{{ $chapter->title }}</span>
                                            
                                            {{-- Chevron Icon --}}
                                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>

                                        {{-- List Materi (Accordion Body) --}}
                                        <div x-show="expanded" x-collapse class="ml-3 pl-4 border-l-2 border-slate-100 space-y-2 pb-2">
                                            @forelse($chapter->lessons as $lesson)
                                                <div class="flex items-center gap-2 text-xs text-slate-500 group hover:text-indigo-600 transition-colors">
                                                    @if($lesson->type == 'video')
                                                        <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @elseif($lesson->type == 'text')
                                                        <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                    @endif
                                                    <span class="truncate">{{ $lesson->title }}</span>
                                                </div>
                                            @empty
                                                <div class="text-[10px] text-slate-400 italic pl-1">Belum ada materi</div>
                                            @endforelse
                                            
                                            {{-- Tombol Tambah Materi di Sidebar --}}
                                            <a href="{{ route('admin.chapters.lessons.create', $chapter->id) }}" class="flex items-center gap-1 text-[10px] font-bold text-indigo-600 hover:text-indigo-800 mt-2 pl-1">
                                                <span>+ Tambah ke Bab ini</span>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Ghost Item (Preview Bab Baru) --}}
                                <div class="relative z-10 flex items-center gap-3 mt-4 opacity-100 animate-pulse bg-indigo-50/50 p-2 rounded-lg border border-indigo-100 border-dashed">
                                    <span class="flex flex-shrink-0 items-center justify-center w-6 h-6 rounded-full bg-indigo-100 border-2 border-indigo-200 text-[10px] font-bold text-indigo-600">
                                        +
                                    </span>
                                    <span class="text-xs text-indigo-600 font-bold italic">Bab Baru (Sedang dibuat...)</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ==================== FLOATING FOOTER ACTION BAR ==================== --}}
            <div class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 p-4 md:px-8 shadow-[0_-4px_20px_-5px_rgba(0,0,0,0.1)]">
                <div class="max-w-7xl mx-auto flex flex-col-reverse sm:flex-row justify-end items-center gap-3">
                    
                    <a href="{{ route('admin.courses.chapters.index', $course) }}" class="w-full sm:w-auto text-center px-6 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-50 hover:text-slate-900 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200">
                        Batal
                    </a>

                    <button type="submit" class="w-full sm:w-auto flex justify-center items-center gap-2 px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold rounded-lg text-sm transition shadow-lg shadow-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Bab & Materi
                    </button>

                </div>
            </div>

        </form>
    </div>
</x-app-layout>
@include('layouts.footer') 