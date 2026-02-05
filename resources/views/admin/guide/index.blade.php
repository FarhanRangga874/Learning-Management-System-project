<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panduan Administrator') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-bold mb-4">Manual Penggunaan Sistem</h3>
                    
                    <div class="w-full h-screen">
                        <iframe 
                            src="{{ asset('manual/panduan_admin.pdf') }}" 
                            class="w-full h-full border rounded-lg"
                            type="application/pdf">
                        </iframe>
                        
                        <div class="mt-4 text-sm text-gray-600">
                            <p>Jika dokumen tidak muncul, <a href="{{ asset('manual/panduan_admin.pdf') }}" target="_blank" class="text-indigo-600 hover:underline">klik di sini untuk mengunduh PDF</a>.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>