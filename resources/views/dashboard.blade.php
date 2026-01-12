<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Belajar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                
                <div class="lg:col-span-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-indigo-100 mb-6">Siap melanjutkan perjalanan belajarmu hari ini?</p>
                        
                        <a href="{{ route('front.index') }}" class="inline-block bg-white text-indigo-600 px-5 py-2 rounded-lg font-bold text-sm hover:bg-indigo-50 transition">
                            Cari Kelas Baru
                        </a>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-10 translate-y-10">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.75l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <span class="block text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $myCourses->count() }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Kelas Diikuti</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $myCourses->count() > 0 ? '100%' : '0%' }}"></div>
                    </div>
                </div>

            </div>

            <div class="mb-6 flex justify-between items-end">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Kursus Saya</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($myCourses as $course)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-full hover:shadow-md transition duration-300">
                    
                    <div class="relative h-40 bg-gray-200">
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-2.5 py-1 rounded shadow-sm">
                            {{ $course->category->name }}
                        </span>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-2 leading-tight line-clamp-2">
                            {{ $course->title }}
                        </h4>
                        
                        <p class="text-xs text-gray-500 mb-4">
                            Bergabung: {{ $course->pivot->joined_at ? \Carbon\Carbon::parse($course->pivot->joined_at)->format('d M Y') : '-' }}
                        </p>

                        <div class="mt-auto">
                            <div class="flex justify-between text-xs font-semibold mb-1 text-gray-500">
                                <span>Progress</span>
                                <span>0%</span> </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: 5%"></div>
                            </div>
                            
                            <a href="{{ route('front.learning', $course->slug) }}" class="block w-full text-center bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold py-2.5 rounded-xl hover:bg-gray-800 transition">
                                Lanjutkan Belajar
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl p-10 text-center border border-dashed border-gray-300 dark:border-gray-700">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Belum ada kelas yang diikuti</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">Mulailah perjalanan belajar Anda dengan memilih kelas dari katalog kami.</p>
                    <a href="{{ route('front.index') }}" class="inline-flex bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                        Lihat Katalog Kelas
                    </a>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>