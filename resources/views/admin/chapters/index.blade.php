<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">{{ __('Atur Kurikulum') }}</h2>
            <a href="{{ route('admin.courses.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- KOLOM KIRI: FORM BUAT BAB --}}
                <div class="lg:col-span-8">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Buat Bab Baru</h3>
                                <p class="text-xs text-slate-500">Tambahkan bab baru ke dalam struktur kurikulum.</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.courses.chapters.store', $course) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <input type="text" name="title" class="w-full border-slate-300 rounded-lg" placeholder="Nama Bab..." required>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow hover:bg-indigo-700">Simpan Bab</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: SIDEBAR --}}
                <div class="lg:col-span-4 space-y-6">
                    @include('admin.chapters.sidebar') {{-- Kita pisahkan sidebar agar rapi --}}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>