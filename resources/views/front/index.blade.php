<x-app-layout>
    
    {{-- HERO SECTION (TEMA: RAMAH & BERSIH) --}}
    <div class="relative bg-gradient-to-b from-indigo-50 via-white to-white pt-20 pb-24 lg:pt-32 lg:pb-36 overflow-hidden">
        
        {{-- Background Soft Blobs (Hiasan Pastel) --}}
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div class="absolute top-10 left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-10 right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            
            {{-- Badge Ramah --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-indigo-100 text-indigo-600 text-sm font-semibold mb-8 shadow-sm">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                </span>
                <span>Untuk Indonesia Makin Cakap Digital</span>
            </div>

            {{-- Headline Bersih & Tegas --}}
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">
                Teknologi Jadi Mudah, <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">
                    Hidup Jadi Lebih Maju.
                </span>
            </h1>

            {{-- Subheadline (Bahasa Sederhana) --}}
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600 mb-10 leading-relaxed">
                Belajar skill digital dari dasar secara gratis. Mulai dari memakai internet dengan aman, mengelola dokumen, hingga membangun bisnis online.
            </p>

            {{-- Search Bar (High Contrast - Mudah Dilihat) --}}
            <div class="max-w-xl mx-auto">
                <form action="{{ route('front.index') }}" method="GET" class="relative group">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-slate-400 group-focus-within:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    
                    {{-- Input Putih dengan Shadow Halus --}}
                    <input type="text" name="search" placeholder="Apa yang ingin Anda pelajari hari ini?" 
                        class="block w-full pl-14 pr-32 py-4 bg-white border-2 border-indigo-50 rounded-full text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-xl shadow-indigo-100/50 text-base"
                        value="{{ request('search') }}">
                        
                    {{-- Tombol Cari --}}
                    <div class="absolute inset-y-1.5 right-1.5">
                        <button type="submit" class="h-full px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-bold text-sm transition-colors duration-200">
                            Cari Kelas
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- CATALOG SECTION --}}
    <div class="py-10 md:py-16 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 md:mb-10 gap-4">
                <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                    @if(request('search'))
                        Hasil Pencarian: "{{ request('search') }}"
                    @elseif(request('category'))
                        Kategori: {{ $categories->find(request('category'))->name ?? 'Terpilih' }}
                    @else
                        Katalog Kelas Terbaru
                    @endif
                </h2>
                
                {{-- KATEGORI DINAMIS --}}
                <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                    {{-- Tombol Semua --}}
                    <a href="{{ route('front.index') }}" 
                        class="px-4 py-2 text-xs md:text-sm font-medium rounded-full whitespace-nowrap transition-all duration-200
                        {{ !request('category') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                        Semua
                    </a>

                    {{-- Loop Kategori --}}
                    @foreach($categories as $category)
                        <a href="{{ route('front.index', ['category' => $category->id, 'search' => request('search')]) }}" 
                            class="px-4 py-2 text-xs md:text-sm font-medium rounded-full whitespace-nowrap transition-all duration-200
                            {{ request('category') == $category->id ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- GRID UTAMA --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                
                @forelse($courses as $course)
                <div class="group bg-white rounded-xl md:rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden hover:-translate-y-1">
                    
                    {{-- Thumbnail --}}
                    <div class="relative h-32 md:h-52 overflow-hidden bg-slate-200">
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>
                        
                        {{-- Badge Kategori --}}
                        <span class="absolute top-2 left-2 md:top-4 md:left-4 bg-white/90 backdrop-blur-sm text-slate-800 text-[10px] md:text-xs font-bold px-2 py-1 md:px-3 md:py-1.5 rounded-full shadow-sm">
                            {{ $course->category->name }}
                        </span>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-3 md:p-6 flex-1 flex flex-col">
                        <div class="mb-2 md:mb-4">
                            <h3 class="text-sm md:text-xl font-bold text-slate-900 leading-snug mb-1 md:mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                <a href="{{ route('front.details', $course->slug) }}">
                                    {{ $course->title }}
                                </a>
                            </h3>
                            
                            <p class="hidden md:block text-slate-500 text-sm">
                                {{ Str::limit($course->description, 80) }}
                            </p>
                        </div>

                        <div class="mt-auto flex items-center justify-between pt-3 md:pt-4 border-t border-slate-50">
                            <div>
                                @if($course->access_type == 'open')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] md:text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Gratis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] md:text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Privat
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('front.details', $course->slug) }}" class="inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 rounded-full bg-slate-50 text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                
                {{-- Empty State --}}
                <div class="col-span-full py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">Tidak ada kelas ditemukan</h3>
                    <p class="text-slate-500 max-w-sm mx-auto mt-2">
                        @if(request('search'))
                            Tidak ada hasil untuk pencarian "{{ request('search') }}".
                        @else
                            Belum ada kelas yang tersedia untuk kategori ini.
                        @endif
                    </p>
                    @if(request('search') || request('category'))
                        <a href="{{ route('front.index') }}" class="inline-block mt-4 text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                            Reset Filter
                        </a>
                    @endif
                </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>