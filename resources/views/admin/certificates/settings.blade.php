<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.certificates.index') }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali</span>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight border-l border-slate-300 pl-4 ml-2">
                {{ __('Desain Sertifikat') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.certificates.update_settings') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- 1. BACKGROUND IMAGE --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="font-bold text-slate-800 mb-4">Background Sertifikat</h3>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Preview Saat Ini</label>
                            <div class="aspect-video w-full rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 overflow-hidden relative flex items-center justify-center">
                                @if($template->background_image)
                                    <img src="{{ Storage::url($template->background_image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-slate-400 text-xs">Belum ada background</span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="background_image" :value="__('Upload Background Baru (Landscape)')" />
                            <input type="file" name="background_image" id="background_image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition"/>
                            <p class="text-[10px] text-slate-400">Format: JPG/PNG. Ukuran A4 Landscape disarankan.</p>
                        </div>
                    </div>

                    {{-- 2. TANDA TANGAN & PEJABAT --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="font-bold text-slate-800 mb-4">Tanda Tangan & Pejabat</h3>

                        <div class="mb-4">
                            <x-input-label for="signature_name" :value="__('Nama Penanda Tangan')" />
                            <x-text-input id="signature_name" name="signature_name" type="text" class="mt-1 block w-full" :value="old('signature_name', $template->signature_name)" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="signature_position" :value="__('Jabatan')" />
                            <x-text-input id="signature_position" name="signature_position" type="text" class="mt-1 block w-full" :value="old('signature_position', $template->signature_position)" required />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Scan Tanda Tangan</label>
                            <div class="h-24 w-48 rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 overflow-hidden relative flex items-center justify-center mb-2">
                                @if($template->signature_image)
                                    <img src="{{ Storage::url($template->signature_image) }}" class="h-full object-contain">
                                @else
                                    <span class="text-slate-400 text-xs">Belum ada TTD</span>
                                @endif
                            </div>
                            <input type="file" name="signature_image" id="signature_image" accept="image/png" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition"/>
                            <p class="text-[10px] text-slate-400 mt-1">Format: PNG Transparan.</p>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition transform hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>