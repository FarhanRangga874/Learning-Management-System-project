@props(['href' => '#', 'active' => false, 'icon' => null])

<li class="inline-flex items-center">
    {{-- 1. Separator (Chevron Icon) --}}
    {{-- Otomatis hidden jika ini adalah item pertama dalam list --}}
    <svg class="w-3 h-3 text-gray-400 mx-2 first:hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
    </svg>

    {{-- 2. Konten --}}
    @if($active)
        {{-- State Aktif (Lokasi Sekarang) --}}
        <span class="inline-flex items-center text-sm font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md border border-indigo-100 truncate max-w-[150px] sm:max-w-xs" aria-current="page">
            @if($icon)
                <span class="mr-1.5 opacity-75">{!! $icon !!}</span>
            @endif
            {{ $slot }}
        </span>
    @else
        {{-- State Link (Navigasi) --}}
        <a href="{{ $href }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 hover:bg-gray-50 px-2 py-1 rounded-md transition-all duration-200 ease-in-out group">
            @if($icon)
                {{-- Icon akan berubah warna saat di-hover --}}
                <span class="mr-1.5 text-gray-400 group-hover:text-indigo-500 transition-colors">{!! $icon !!}</span>
            @endif
            {{ $slot }}
        </a>
    @endif
</li>