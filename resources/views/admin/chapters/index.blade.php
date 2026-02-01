<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.index') }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali ke Kursus</span>
            </a>
        </div>
    </x-slot>

    <div class="pb-24 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            {{-- Header Title --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Atur Kurikulum</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola struktur bab untuk kursus <strong>{{ $course->name }}</strong>.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- KOLOM KIRI: FORM CREATE CHAPTER --}}
                <div class="lg:col-span-8">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
                        
                        {{-- Card Header --}}
                        <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Buat Bab Baru</h3>
                                <p class="text-xs text-slate-500">Bab digunakan untuk mengelompokkan materi pembelajaran.</p>
                            </div>
                        </div>

                        {{-- Form Content --}}
                        <form method="POST" action="{{ route('admin.courses.chapters.store', $course->id) }}" class="p-6">
                            @csrf

                            <div class="mb-6">
                                <x-input-label for="title" :value="__('Nama Bab')" class="mb-1" />
                                <x-text-input id="title" class="block w-full" type="text" name="title" :value="old('title')" placeholder="Contoh: Pendahuluan, Instalasi, dll..." required autofocus />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200">
                                    {{ __('Simpan Bab') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    {{-- List Bab yang Sudah Ada --}}
                    @if($course->chapters->count() > 0)
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                Daftar Bab Saat Ini
                            </h3>
                            <div class="space-y-3">
                                @foreach($course->chapters as $index => $chapter)
                                    <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow group">
                                        <div class="flex items-center gap-4">
                                            <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-500 rounded-full font-bold text-sm group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                                {{ $index + 1 }}
                                            </span>
                                            <h4 class="font-bold text-slate-700 text-sm md:text-base">{{ $chapter->title }}</h4>
                                        </div>
                                        
                                        {{-- ACTION BUTTONS --}}
                                        <div class="flex items-center gap-2">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.courses.chapters.edit', [$course->id, $chapter->id]) }}" 
                                               class="px-3 py-1.5 text-xs font-bold text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-300 transition-all flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('admin.courses.chapters.destroy', [$course->id, $chapter->id]) }}" method="POST" onsubmit="return confirm('Hapus bab ini beserta seluruh materinya?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Bab">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- KOLOM KANAN: SIDEBAR --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        {{-- Include Sidebar yang sama --}}
                        <div>
                             @include('admin.chapters.sidebar')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
@include('layouts.footer') 