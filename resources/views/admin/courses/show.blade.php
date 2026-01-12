<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.courses.index') }}" class="p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            
            {{-- Judul Header --}}
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail & Peserta Kursus') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. KARTU DETAIL KURSUS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    
                    {{-- Thumbnail --}}
                    <div class="w-full md:w-1/3 lg:w-1/4 relative">
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover min-h-[200px]">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent md:hidden"></div>
                    </div>

                    {{-- Info Konten --}}
                    <div class="w-full md:w-2/3 lg:w-3/4 p-6 md:p-8 flex flex-col justify-center">
                        
                        {{-- Badges Kategori & Akses --}}
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $course->category->name }}
                            </span>
                            
                            @if($course->access_type == 'code')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Kode: {{ $course->access_code }}
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                    Open Access
                                </span>
                            @endif
                        </div>

                        {{-- Judul & Deskripsi --}}
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ $course->title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-6">{{ $course->description }}</p>
                        
                        {{-- Statistik --}}
                        <div class="flex items-center gap-8 pt-6 border-t border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Siswa</span>
                                    <span class="text-lg font-bold text-gray-900">{{ $students->count() }} Orang</span>
                                </div>
                            </div>

                            <div class="hidden md:block w-px h-10 bg-gray-200"></div>

                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Dibuat Pada</span>
                                    <span class="text-lg font-bold text-gray-900">{{ $course->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 2. TABEL DAFTAR SISWA --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Daftar Peserta</h3>
                        <p class="text-sm text-gray-500">Siswa yang telah mendaftar pada kursus ini.</p>
                    </div>
                    <span class="text-xs font-medium bg-white border border-gray-200 text-gray-500 px-3 py-1 rounded-full shadow-sm">
                        Total: {{ $students->count() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">#</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Nama Siswa</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Email</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Tanggal Bergabung</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $index => $student)
                            <tr class="bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 text-center text-gray-400 font-medium">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($student->avatar)
                                            <img class="h-9 w-9 rounded-full object-cover mr-3 ring-2 ring-white border border-gray-200" src="{{ Storage::url($student->avatar) }}" alt="{{ $student->name }}">
                                        @else
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mr-3 text-indigo-600 font-bold text-xs ring-2 ring-white border border-indigo-50">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="font-semibold text-gray-900">{{ $student->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $student->email }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $student->pivot->joined_at 
                                            ? \Carbon\Carbon::parse($student->pivot->joined_at)->format('d M Y') 
                                            : '-' 
                                        }}
                                        <span class="text-xs text-gray-400 ml-1">
                                            ({{ $student->pivot->joined_at ? \Carbon\Carbon::parse($student->pivot->joined_at)->format('H:i') : '' }})
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        Aktif
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <h3 class="text-gray-900 font-medium text-lg">Belum ada peserta</h3>
                                        <p class="text-gray-500 text-sm mt-1 max-w-sm">Kursus ini belum memiliki siswa yang mendaftar. Bagikan kode akses atau link kursus untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>