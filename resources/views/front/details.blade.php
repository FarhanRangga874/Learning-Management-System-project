<x-app-layout>
    <div class="bg-white min-h-screen">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-12">
            
            {{-- 1. BREADCRUMBS NAVIGATION --}}
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('front.index') }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                            Home
                        </a>
                    </li>
                    <li><span class="text-gray-300 text-xs">/</span></li>
                    <li>
                        <a href="{{ route('front.index', ['category' => $course->category->id]) }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                            {{ $course->category->name }}
                        </a>
                    </li>
                    <li><span class="text-gray-300 text-xs">/</span></li>
                    <li aria-current="page">
                        <span class="text-xs font-semibold text-gray-900 truncate max-w-[200px] sm:max-w-xs block">
                            {{ $course->title }}
                        </span>
                    </li>
                </ol>
            </nav>

            {{-- 2. JUDUL UTAMA --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                    {{ $course->title }}
                </h1>
            </div>

            {{-- 3. GRID LAYOUT KONTEN --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                
                {{-- KOLOM KIRI --}}
                <div class="lg:col-span-8 order-1">
                    
                    {{-- Thumbnail --}}
                    <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-lg border border-gray-100 mb-8 group">
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                        
                        {{-- Badge Kategori --}}
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-1.5 rounded-full bg-white/95 backdrop-blur text-indigo-700 text-xs font-bold uppercase tracking-wider shadow-sm border border-indigo-50">
                                {{ $course->category->name }}
                            </span>
                        </div>
                    </div>

                    {{-- Deskripsi [FIX WYSIWYG] --}}
                    <div class="prose prose-lg prose-indigo max-w-none text-gray-600 leading-relaxed mb-10">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Tentang Kursus</h3>
                        {{-- Menggunakan {!! !!} agar HTML dirender --}}
                        {!! $course->description ?? 'Deskripsi belum tersedia.' !!}
                    </div>

                    {{-- Keypoints --}}
                    <div class="bg-gray-50 rounded-2xl p-6 md:p-8 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Yang Akan Anda Pelajari
                        </h3>
                        
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                            @forelse($course->keypoints as $keypoint)
                                <li class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700 leading-relaxed font-medium">{{ $keypoint->name }}</span>
                                </li>
                            @empty
                                <li class="text-gray-500 italic col-span-2">Belum ada poin kunci.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- KOLOM KANAN: SIDEBAR --}}
                <div class="lg:col-span-4 order-2">
                    <div class="lg:sticky lg:top-24 space-y-8">
                        
                        {{-- Enroll Box --}}
                        <div class="bg-white border border-indigo-100 rounded-2xl p-6 shadow-xl shadow-indigo-100/50 relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>

                            @if($enrolled)
                                <div class="text-center relative z-10">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4 ring-4 ring-green-50">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">Anda Sudah Terdaftar!</h3>
                                    <p class="text-sm text-gray-500 mb-6">Lanjutkan progres belajar Anda sekarang.</p>
                                    
                                    <a href="{{ route('front.learning', $course->slug) }}" class="flex items-center justify-center gap-2 w-full bg-indigo-600 text-white py-4 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 transform active:scale-[0.98]">
                                        <span>Lanjut Belajar</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            @else
                                <div class="relative z-10">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Bergabung Kelas Ini</h3>
                                    
                                    <form action="{{ route('front.join', $course->slug) }}" method="POST">
                                        @csrf
                                        @if($course->access_type == 'code')
                                            <div class="mb-5">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Akses</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                                    </div>
                                                    <input type="text" name="access_code" placeholder="Masukkan kode..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-white text-sm font-medium" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold text-base py-4 rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform active:scale-[0.98] flex justify-center items-center gap-2">
                                                Validasi & Gabung
                                            </button>
                                        @else
                                            <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-5 text-center">
                                                <span class="text-green-700 font-bold text-sm uppercase tracking-wide flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Akses Gratis
                                                </span>
                                            </div>
                                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold text-base py-4 rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform active:scale-[0.98] flex justify-center items-center gap-2">
                                                Daftar Sekarang
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            @endif
                        </div>

                        {{-- Info Box --}}
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                            <h4 class="font-bold text-gray-900 mb-5 text-lg">Informasi Kursus</h4>
                            <ul class="space-y-5">
                                <li class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <span class="text-gray-600 font-medium text-sm">Peserta</span>
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $course->students->count() }} Orang</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <span class="text-gray-600 font-medium text-sm">Total Materi</span>
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $course->chapters->sum(function($c){ return $c->lessons->count(); }) }} Materi</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-gray-600 font-medium text-sm">Terbit</span>
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $course->created_at->format('d M Y') }}</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@include('layouts.footer') 