<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                {{-- Tombol Kembali --}}
                <a href="{{ route('admin.courses.index') }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                
                <h2 class="font-bold text-xl text-gray-800 leading-tight ml-2 border-l border-slate-300 pl-4">
                    {{ __('Pusat Bantuan & Kontak') }}
                </h2>
            </div>
            
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">Admin Panel</span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Flash Message Success --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm" role="alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- === KOLOM KIRI: PENGATURAN KONTAK (WHATSAPP) === --}}
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
                        {{-- Dekorasi Background --}}
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-50 rounded-full blur-xl opacity-60 pointer-events-none"></div>
                        
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0 shadow-sm border border-green-200">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.884.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.982zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.272-.099-.47-.148-.669.149-.198.296-.768.966-.941 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.535 0 1.518 1.115 2.989 1.264 3.187.149.198 2.193 3.348 5.312 4.695 3.119 1.347 3.119.897 3.687.842.57-.054 1.834-.755 2.092-1.485.258-.73.258-1.357.181-1.485z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-lg">Kontak WhatsApp</h3>
                                    <p class="text-xs text-slate-500 leading-tight">Nomor tujuan tombol "Hubungi Kami".</p>
                                </div>
                            </div>

                            {{-- Info Nomor Aktif --}}
                            <div class="mb-6 p-4 bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-xl flex items-center justify-between shadow-sm">
                                <div>
                                    <p class="text-[10px] text-green-600 font-bold uppercase tracking-wider mb-0.5">Nomor Aktif Saat Ini</p>
                                    <div class="text-slate-800 font-mono font-bold text-lg tracking-wide flex items-center gap-1">
                                        @if(isset($whatsapp->value))
                                            <span class="text-green-600 text-base mr-1"><i class="fa-brands fa-whatsapp"></i> +</span>{{ $whatsapp->value }}
                                        @else
                                            <span class="text-slate-400 italic text-sm font-normal">Belum diatur</span>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($whatsapp->value))
                                    <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 animate-pulse">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    </div>
                                @endif
                            </div>

                            <form action="{{ route('admin.faqs.update_contact') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="whatsapp_number" :value="__('Ubah Nomor Telepon')" class="mb-1.5" />
                                        
                                        {{-- [FIX] Input Group dengan +62 Mati --}}
                                        <div class="relative flex items-center group">
                                            {{-- Kode Negara Statis --}}
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none bg-slate-100 border-r border-slate-300 rounded-l-xl pr-3 h-full">
                                                <span class="text-slate-600 font-bold text-sm tracking-wide">🇮🇩 +62</span>
                                            </div>
                                            
                                            {{-- Input Field --}}
                                            <input type="text" inputmode="numeric" name="whatsapp_number" id="whatsapp_number" 
                                                class="pl-[5.5rem] block w-full rounded-xl border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-3 bg-white transition-all font-mono text-slate-700 placeholder-slate-400"
                                                placeholder="81234567890">
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-2 flex items-start gap-1.5 leading-relaxed bg-slate-50 p-2 rounded-lg border border-slate-100">
                                            <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>
                                                Cukup masukkan nomor lokal, contoh: <b>8123456789</b> atau <b>08123456789</b>. Sistem akan otomatis mengubahnya menjadi format internasional (+62).
                                            </span>
                                        </p>
                                    </div>

                                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-slate-900 text-white px-4 py-3 rounded-xl font-bold text-sm hover:bg-slate-800 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan Nomor
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- === KOLOM KANAN: TABEL FAQ === --}}
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Daftar FAQ</h3>
                                <p class="text-xs text-slate-500">Pertanyaan yang sering diajukan pengguna.</p>
                            </div>
                            <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Baru
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-slate-500 uppercase bg-slate-50/80 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-3 font-bold tracking-wider">Pertanyaan</th>
                                        <th class="px-6 py-3 font-bold tracking-wider">Jawaban</th>
                                        <th class="px-6 py-3 font-bold tracking-wider text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($faqs as $faq)
                                        <tr class="bg-white hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-6 py-4 align-top w-[35%]">
                                                <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                                    {{ $faq->question }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 align-top w-[50%]">
                                                <div class="text-slate-600 leading-relaxed text-xs">
                                                    {{ Str::limit($faq->answer, 120) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 align-top text-right whitespace-nowrap">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm" title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-200 transition-all shadow-sm" title="Hapus">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                    <p class="text-slate-500 font-medium">Belum ada data FAQ tersedia.</p>
                                                    <p class="text-slate-400 text-xs mt-1">Silakan tambahkan pertanyaan baru untuk membantu pengguna.</p>
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
        </div>
    </div>
</x-app-layout>