<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-500 bg-white border border-gray-200 px-3 py-1.5 rounded-md shadow-sm">
                {{ \Carbon\Carbon::now()->isoFormat('d MMMM Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 1. OVERVIEW SECTION --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                
                {{-- Welcome Card --}}
                <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-center">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Selamat Datang, {{ Auth::user()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-2xl text-sm leading-relaxed">
                        Lanjutkan pembelajaran Anda. Akses materi terbaru dan selesaikan kursus untuk meningkatkan kompetensi profesional Anda.
                    </p>
                    <div>
                        <a href="{{ route('front.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Telusuri Katalog Kelas
                        </a>
                    </div>
                </div>

                {{-- Stats Summary --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Kelas Diikuti</span>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $myCourses->count() }}</span>
                            <span class="text-sm text-gray-500">Kursus</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Status Akun</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. MY COURSES SECTION --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Kelas Saya
                </h3>
                
                <div class="w-full md:w-64">
                    <form action="{{ route('dashboard') }}" method="GET" class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-10 pr-3 py-2 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow" 
                            placeholder="Cari kelas...">
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($myCourses as $course)
                    @php
                        // Logic Progress
                        $totalLessons = $course->chapters->flatMap->lessons->count();
                        $completedLessons = \App\Models\LessonCompletion::where('user_id', Auth::id())
                            ->where('course_id', $course->id)
                            ->count();
                        $progress = ($totalLessons > 0) ? round(($completedLessons / $totalLessons) * 100) : 0;
                    @endphp

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col h-full group">
                        
                        {{-- Header / Thumbnail --}}
                        <div class="relative h-48 overflow-hidden rounded-t-xl bg-gray-100 border-b border-gray-100">
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur border border-gray-200 text-gray-700 text-[10px] font-bold px-2.5 py-1 rounded shadow-sm uppercase tracking-wide">
                                {{ $course->category->name }}
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="p-5 flex-1 flex flex-col">
                            <h4 class="font-bold text-base text-gray-900 dark:text-white mb-2 line-clamp-2 leading-snug group-hover:text-indigo-600 transition-colors">
                                {{ $course->title }}
                            </h4>
                            
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-4">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Terdaftar: {{ $course->pivot->joined_at ? \Carbon\Carbon::parse($course->pivot->joined_at)->format('d M Y') : '-' }}</span>
                            </div>

                            <div class="mt-auto">
                                {{-- Progress --}}
                                <div class="flex justify-between items-end mb-1.5">
                                    <span class="text-xs font-medium text-gray-500">Progres Belajar</span>
                                    <span class="text-xs font-bold {{ $progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-4">
                                    <div class="h-1.5 rounded-full transition-all duration-500 {{ $progress == 100 ? 'bg-green-500' : 'bg-indigo-600' }}" style="width: {{ $progress }}%"></div>
                                </div>
                                
                                {{-- Button --}}
                                <a href="{{ route('front.learning', $course->slug) }}" class="block w-full text-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 hover:text-gray-900 transition focus:ring-2 focus:ring-offset-1 focus:ring-gray-200">
                                    {{ $progress == 100 ? 'Lihat Kembali' : ($progress > 0 ? 'Lanjutkan' : 'Mulai Belajar') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-xl border border-dashed border-gray-300">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada kelas yang diikuti.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>

@include('layouts.footer')