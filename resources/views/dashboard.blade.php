<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Belajar') }}
        </h2>
    </x-slot>

    <div class="py-8 md:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 1. HERO & STATS SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mb-8 md:mb-10">
                
                {{-- Welcome Card --}}
                <div class="lg:col-span-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-indigo-200 dark:shadow-none relative overflow-hidden flex flex-col justify-center">
                    <div class="relative z-10">
                        <h3 class="text-2xl md:text-3xl font-extrabold mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-indigo-100 text-sm md:text-base mb-6 md:mb-8 max-w-lg">Siap melanjutkan perjalanan belajarmu hari ini? Konsistensi adalah kunci kesuksesan.</p>
                        
                        <a href="{{ route('front.index') }}" class="inline-flex items-center gap-2 bg-white text-indigo-600 px-5 py-2.5 md:px-6 md:py-3 rounded-full font-bold text-xs md:text-sm hover:bg-indigo-50 transition shadow-sm">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Cari Kelas Baru
                        </a>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-8 translate-y-8 pointer-events-none">
                        <svg class="w-32 h-32 md:w-48 md:h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.75l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
                    </div>
                </div>

                {{-- Stats Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <span class="block text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-gray-100">{{ $myCourses->count() }}</span>
                            <span class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400">Kelas Diikuti</span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-semibold text-gray-500 dark:text-gray-400">
                            <span>Status Akun</span>
                            <span class="text-green-600">Active</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2 md:h-2.5">
                            <div class="bg-blue-600 h-2 md:h-2.5 rounded-full" style="width: {{ $myCourses->count() > 0 ? '100%' : '0%' }}"></div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 2. COURSE LIST HEADER & SEARCH BAR --}}
            <div class="mb-6 md:mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-100">Kursus Saya</h3>
                    <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 mt-1">Lanjutkan di mana Anda tinggalkan.</p>
                </div>

                {{-- SEARCH BAR --}}
                <div class="w-full md:w-72">
                    <form action="{{ route('dashboard') }}" method="GET" class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full leading-5 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm transition duration-200 ease-in-out shadow-sm" 
                            placeholder="Cari kelas saya...">
                    </form>
                </div>
            </div>

            {{-- 3. COURSE GRID --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
                @forelse($myCourses as $course)
                
                {{-- LOGIC HITUNG PROGRESS DI DALAM LOOP --}}
                @php
                    // 1. Hitung total materi dalam kursus ini
                    // Pastikan di controller sudah diload 'chapters.lessons' agar tidak N+1 Query
                    $totalLessons = $course->chapters->flatMap->lessons->count();

                    // 2. Hitung jumlah materi yang sudah diselesaikan user ini di kursus ini
                    $completedLessons = \App\Models\LessonCompletion::where('user_id', Auth::id())
                        ->where('course_id', $course->id)
                        ->count();

                    // 3. Hitung persentase
                    $progress = ($totalLessons > 0) ? round(($completedLessons / $totalLessons) * 100) : 0;
                @endphp

                <div class="group bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full overflow-hidden">
                    
                    {{-- Thumbnail --}}
                    <div class="relative h-32 md:h-48 overflow-hidden">
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="absolute top-2 left-2 md:top-4 md:left-4 bg-white/90 backdrop-blur text-indigo-700 text-[10px] md:text-xs font-bold px-2 py-1 md:px-3 md:py-1.5 rounded-full shadow-sm">
                            {{ $course->category->name }}
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="p-4 md:p-6 flex-1 flex flex-col">
                        <h4 class="font-bold text-sm md:text-xl text-gray-900 dark:text-gray-100 mb-2 leading-snug line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            {{ $course->title }}
                        </h4>
                        
                        <div class="hidden md:flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Bergabung: {{ $course->pivot->joined_at ? \Carbon\Carbon::parse($course->pivot->joined_at)->format('d M Y') : '-' }}</span>
                        </div>

                        <div class="mt-auto pt-4 md:pt-6 border-t border-gray-100 dark:border-gray-700">
                            
                            {{-- Progress Bar Dinamis --}}
                            <div class="flex justify-between text-[10px] md:text-xs font-semibold mb-2 text-gray-600 dark:text-gray-400">
                                <span>Progres</span>
                                <span class="{{ $progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">
                                    {{ $progress }}%
                                </span> 
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 md:h-2 mb-4 md:mb-6">
                                <div class="h-1.5 md:h-2 rounded-full transition-all duration-1000 ease-out {{ $progress == 100 ? 'bg-green-500' : 'bg-indigo-500' }}" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                            
                            {{-- Tombol Aksi --}}
                            <a href="{{ route('front.learning', $course->slug) }}" class="flex items-center justify-center gap-2 w-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold py-2 md:py-3 rounded-xl transition shadow-lg shadow-indigo-200 dark:shadow-none text-xs md:text-sm hover:bg-indigo-700 hover:text-white">
                                <span class="hidden md:inline">
                                    {{ $progress == 100 ? 'Lihat Kembali' : ($progress > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar') }}
                                </span>
                                <span class="md:hidden">
                                    {{ $progress == 100 ? 'Review' : 'Lanjut' }}
                                </span>
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                
                {{-- 4. EMPTY STATE --}}
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-3xl p-10 md:p-16 text-center border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6 text-gray-400">
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Belum ada kelas yang diikuti</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto text-sm md:text-base">Mulailah perjalanan belajar Anda dengan memilih kelas berkualitas dari katalog kami.</p>
                    <a href="{{ route('front.index') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3.5 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform">
                        Lihat Katalog Kelas
                    </a>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
@include('layouts.footer') 