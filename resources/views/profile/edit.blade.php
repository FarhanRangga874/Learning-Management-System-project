<x-app-layout>
    <x-slot name="header">
        {{-- Container Header Flex --}}
        <div class="flex items-center gap-4">
            
            {{-- Tombol Kembali --}}
            {{-- Arahkan ke dashboard atau halaman sebelumnya --}}
            <a href="{{ route('dashboard') }}" class="group flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="hidden sm:inline">Kembali</span>
            </a>

            {{-- Separator (Garis tipis vertikal) --}}
            <div class="hidden sm:block h-6 w-px bg-slate-300"></div>

            {{-- Judul Halaman --}}
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Profil Saya') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Form Edit Profil (Yang sudah didesain ulang sebelumnya) --}}
            @include('profile.partials.update-profile-information-form')

            {{-- Form Ganti Password --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Form Hapus Akun --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>