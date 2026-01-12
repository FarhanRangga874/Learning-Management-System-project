<x-app-layout>
    
    <div class="relative bg-slate-900 pt-16 pb-20 lg:pt-24 lg:pb-28 overflow-hidden">
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-3xl mix-blend-screen"></div>
            <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-3xl mix-blend-screen"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight mb-6">
                Upgrade Skill, <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-300">
                    Raih Masa Depan.
                </span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-400 mb-10">
                Akses ratusan materi pembelajaran berkualitas tinggi yang dikurasi oleh para ahli industri.
            </p>

            <div class="max-w-xl mx-auto">
                <form action="{{ route('front.index') }}" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" placeholder="Cari kelas programming, desain, marketing..." 
                           class="block w-full pl-12 pr-4 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white placeholder-slate-400 focus:outline-none focus:bg-white/20 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-xl"
                           value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </div>

    <div class="py-16 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
                <h2 class="text-2xl font-bold text-gray-900">Katalog Kelas Terbaru</h2>
                
                <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                    <button class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-full shadow-md shadow-indigo-200">Semua</button>
                    <button class="px-4 py-2 bg-white text-gray-600 border border-gray-200 text-sm font-medium rounded-full hover:bg-gray-50 whitespace-nowrap">Technology</button>
                    <button class="px-4 py-2 bg-white text-gray-600 border border-gray-200 text-sm font-medium rounded-full hover:bg-gray-50 whitespace-nowrap">Design</button>
                    <button class="px-4 py-2 bg-white text-gray-600 border border-gray-200 text-sm font-medium rounded-full hover:bg-gray-50 whitespace-nowrap">Business</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @forelse($courses as $course)
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden hover:-translate-y-1">
                    
                    <div class="relative h-52 overflow-hidden bg-gray-200">
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>
                        
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                            {{ $course->category->name }}
                        </span>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-900 leading-snug mb-2 group-hover:text-indigo-600 transition-colors">
                                <a href="{{ route('front.details', $course->slug) }}">
                                    {{ $course->title }}
                                </a>
                            </h3>
                            <p class="text-gray-500 text-sm line-clamp-2">
                                {{ $course->description }}
                            </p>
                        </div>

                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">
                            <div>
                                @if($course->access_type == 'open')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Free Access
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Private
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('front.details', $course->slug) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada kelas ditemukan</h3>
                    <p class="text-gray-500 max-w-sm mx-auto mt-2">Coba kata kunci lain atau kembali lagi nanti untuk materi terbaru.</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>