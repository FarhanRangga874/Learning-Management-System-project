<x-app-layout>
    
    {{-- CSS Tambahan --}}
    @push('styles')
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush

    {{-- 1. HERO SECTION --}}
    <div class="relative bg-gradient-to-b from-indigo-50 via-white to-white pt-24 pb-20 lg:pt-32 lg:pb-32 overflow-hidden">
        {{-- Background Blobs --}}
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div class="absolute top-10 left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-10 right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-indigo-100 text-indigo-600 text-xs md:text-sm font-semibold mb-6 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                </span>
                <span>Untuk Indonesia Makin Cakap Digital</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight mb-4 md:mb-6 leading-tight">
                Teknologi Jadi Mudah, <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">
                    Hidup Jadi Lebih Maju.
                </span>
            </h1>

            <p class="mt-4 max-w-2xl mx-auto text-base md:text-lg text-slate-600 mb-8 md:mb-10 leading-relaxed px-4">
                Belajar skill digital dari dasar secara gratis. Mulai dari memakai internet dengan aman hingga membangun bisnis online.
            </p>

            {{-- Search Bar --}}
            <div class="max-w-xl mx-auto px-4">
                <form action="{{ route('front.index') }}" method="GET" class="relative group">
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('access_type')) <input type="hidden" name="access_type" value="{{ request('access_type') }}"> @endif

                    <div class="absolute inset-y-0 left-0 pl-4 md:pl-5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-slate-400 group-focus-within:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    
                    <input type="text" name="search" placeholder="Apa yang ingin dipelajari?" 
                        class="block w-full pl-12 pr-28 md:pl-14 md:pr-32 py-3.5 md:py-4 bg-white border-2 border-indigo-50 rounded-full text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-xl shadow-indigo-100/50 text-sm md:text-base truncate"
                        value="{{ request('search') }}">
                        
                    <div class="absolute inset-y-1.5 right-1.5">
                        <button type="submit" class="h-full px-4 md:px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-bold text-xs md:text-sm transition-colors duration-200">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. CATALOG SECTION --}}
    <div class="py-12 md:py-16 bg-slate-50" id="catalog">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Katalog & Filter --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-4 relative z-20"> {{-- Tambahkan relative z-20 agar di atas card --}}
                <h2 class="text-xl md:text-2xl font-bold text-slate-900 hidden lg:block">
                    @if(request('search')) Hasil: "{{ request('search') }}"
                    @elseif(request('category')) Kategori: {{ $categories->find(request('category'))->name ?? 'Terpilih' }}
                    @else Katalog Kelas Terbaru @endif
                </h2>
                
                {{-- FILTER CONTROLS --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    {{-- Filter Akses --}}
                    <div class="flex bg-white p-1 rounded-xl border border-slate-200 shadow-sm">
                        <a href="{{ route('front.index', array_merge(request()->except(['page', 'access_type']), ['access_type' => null])) }}" 
                           class="flex-1 px-4 py-1.5 text-xs md:text-sm font-semibold rounded-lg text-center transition-all 
                           {{ !request('access_type') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                           Semua
                        </a>
                        <a href="{{ route('front.index', array_merge(request()->except(['page']), ['access_type' => 'open'])) }}" 
                           class="flex-1 px-4 py-1.5 text-xs md:text-sm font-semibold rounded-lg text-center transition-all 
                           {{ request('access_type') == 'open' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50' }}">
                           Gratis
                        </a>
                        <a href="{{ route('front.index', array_merge(request()->except(['page']), ['access_type' => 'code'])) }}" 
                           class="flex-1 px-4 py-1.5 text-xs md:text-sm font-semibold rounded-lg text-center transition-all 
                           {{ request('access_type') == 'code' ? 'bg-amber-50 text-amber-700' : 'text-slate-500 hover:bg-slate-50' }}">
                           Private
                        </a>
                    </div>

                    {{-- Filter Kategori (Dropdown Fix) --}}
                    <div class="relative w-full sm:w-64" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" 
                            class="flex items-center justify-between w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <span class="text-sm font-medium text-slate-700 truncate">
                                @if(request('category') && request('category') !== 'all')
                                    {{ $categories->find(request('category'))->name ?? 'Semua Kategori' }}
                                @else
                                    Semua Kategori
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Dropdown Menu (Z-INDEX 50 agar paling atas) --}}
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-full bg-white rounded-xl shadow-2xl border border-slate-100 py-1 overflow-hidden origin-top-right z-50 max-h-60 overflow-y-auto custom-scrollbar" 
                             style="display: none;">
                            
                            <a href="{{ route('front.index', array_merge(request()->except(['page', 'category']), ['category' => null])) }}" 
                               class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border-b border-slate-50 {{ !request('category') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                                Semua Kategori
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('front.index', array_merge(request()->except(['page']), ['category' => $category->id])) }}" 
                                   class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ request('category') == $category->id ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

{{-- COURSE GRID (Redesigned) --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @forelse($courses as $course)
                
                <div class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    
                    {{-- 1. Image & Overlay --}}
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity duration-300"></div>

                        {{-- Category Badge (Top Left) --}}
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-indigo-700 text-[10px] font-bold px-2 py-1 rounded-md shadow-sm border border-indigo-50 uppercase tracking-wider z-10">
                            {{ $course->category->name }}
                        </span>

                        {{-- Access Badge (Top Right) --}}
                        @if($course->access_type == 'open')
                            <span class="absolute top-3 right-3 bg-emerald-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-sm z-10">
                                GRATIS
                            </span>
                        @else
                            <span class="absolute top-3 right-3 bg-slate-800/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-sm flex items-center gap-1 z-10">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                PRIVATE
                            </span>
                        @endif
                    </div>

                    {{-- 2. Content --}}
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="font-bold text-slate-900 text-sm md:text-base leading-snug line-clamp-2 mb-2 group-hover:text-indigo-600 transition-colors">
                            <a href="{{ route('front.details', $course->slug) }}" class="focus:outline-none">
                                <span class="absolute inset-0 z-0"></span>
                                {{ $course->title }}
                            </a>
                        </h3>
                        
                        <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                            {{ Str::limit(strip_tags($course->description), 70) }}
                        </p>

                        {{-- Footer Card (Author/Stats - Opsional, bisa dihapus jika tidak ada data) --}}
                        <div class="mt-auto pt-3 border-t border-slate-50 flex items-center justify-between text-[10px] text-slate-400 font-medium">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Materi Lengkap
                            </span>
                            <span class="group-hover:text-indigo-500 transition-colors flex items-center gap-1">
                                Lihat Detail &rarr;
                            </span>
                        </div>
                    </div>
                </div>
                
                @empty
                {{-- Empty State (Tidak Berubah) --}}
                <div class="col-span-full py-16 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-50 mb-6">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Belum ada kelas tersedia</h3>
                    <p class="text-slate-500 mt-2 max-w-md mx-auto text-sm">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                    <a href="{{ route('front.index') }}" class="inline-block mt-6 px-6 py-2.5 bg-indigo-600 text-white rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 text-sm">
                        Reset Filter
                    </a>
                </div>
                @endforelse
            </div>

            {{-- PAGINATION (Redesigned) --}}
            <div class="mt-12 flex justify-center">
                {{ $courses->onEachSide(1)->links() }}
            </div>
            {{-- Jika belum publish vendor pagination: php artisan vendor:publish --tag=laravel-pagination --}}

        </div>
    </div>

    {{-- 3. FAQ SECTION --}}
    <div class="py-24 bg-white relative overflow-hidden border-t border-slate-100">
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Sering Ditanyakan</h2>
                <p class="text-slate-500 text-lg">Cari tahu jawaban dari pertanyaan yang sering diajukan.</p>
            </div>
            
            <div class="space-y-4">
                @forelse($faqs ?? [] as $faq) 
                    <div x-data="{ open: false }" class="group border border-slate-200 rounded-2xl bg-white transition-all duration-300 hover:border-indigo-300 hover:shadow-md">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors pr-4">{{ $faq->question }}</span>
                            <span class="flex-shrink-0">
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 group-hover:bg-indigo-50 transition-colors">
                                    <svg class="w-5 h-5 text-slate-500 group-hover:text-indigo-600 transition-transform duration-300" :class="open ? 'rotate-180' : 'rotate-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path>
                                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 12H6"></path>
                                    </svg>
                                </div>
                            </span>
                        </button>
                        <div x-show="open" x-transition class="px-6 pb-6" style="display: none;">
                            <div class="pt-4 border-t border-slate-100">
                                <p class="text-slate-600 leading-relaxed italic">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                        <p class="text-slate-400 font-medium">Belum ada pertanyaan.</p>
                    </div>
                @endforelse
            </div>

            {{-- Logic Ambil Nomor WA --}}
            @php
                $waNumber = \App\Models\SiteSetting::where('key', 'whatsapp_number')->value('value') ?? '628123456789';
                $waMessage = "Halo Admin, saya butuh bantuan seputar kursus.";
                $waLink = "https://wa.me/{$waNumber}?text=" . urlencode($waMessage);
            @endphp

            {{-- Tampilan Baru --}}
            <div class="mt-20 text-center pb-10">
                <div class="inline-flex flex-col md:flex-row items-center gap-6 px-8 py-6 bg-white border border-indigo-50 rounded-3xl shadow-xl shadow-indigo-100/50 hover:shadow-indigo-200/50 transition-all duration-300 transform hover:-translate-y-1">
                    
                    {{-- Teks Copywriting --}}
                    <div class="flex flex-col md:items-start items-center text-center md:text-left">
                        <p class="text-slate-900 font-extrabold text-lg">Masih butuh bantuan?</p>
                        <p class="text-slate-500 text-sm">kami siap menjawab pertanyaan Anda.</p>
                    </div>

                    {{-- Divider Vertical (Desktop) --}}
                    <div class="h-10 w-px bg-slate-100 hidden md:block"></div>

                    {{-- Tombol WhatsApp --}}
                    <a href="{{ $waLink }}" target="_blank" class="group relative px-6 py-3 bg-indigo-600 text-white rounded-full font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-3">
                        
                        {{-- Ikon WhatsApp SVG --}}
                        <svg class="w-5 h-5 fill-current opacity-90 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.884.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.982zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.272-.099-.47-.148-.669.149-.198.296-.768.966-.941 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.535 0 1.518 1.115 2.989 1.264 3.187.149.198 2.193 3.348 5.312 4.695 3.119 1.347 3.119.897 3.687.842.57-.054 1.834-.755 2.092-1.485.258-.73.258-1.357.181-1.485z"/>
                        </svg>
                        
                        <span>Hubungi Kami</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-app-layout>