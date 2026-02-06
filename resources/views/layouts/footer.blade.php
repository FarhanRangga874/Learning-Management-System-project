{{-- Container Wrapper agar footer bisa rounded --}}
<div class="bg-gray-50 pt-16">
    <footer class="bg-slate-900 text-slate-300 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.3)] relative overflow-hidden">
        
        {{-- Dekorasi Background (Glow Halus) --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-12 lg:py-16">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-20">
                
                {{-- KOLOM KIRI: Brand & Social --}}
                <div class="flex flex-col space-y-6">
                    {{-- Logo Area --}}
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-white/5 rounded-2xl backdrop-blur-sm border border-white/10">
                            <img src="{{ asset('images/logo_kabupaten_blitar.png') }}" 
                                alt="Logo Pemkab Blitar" 
                                class="h-12 w-auto object-contain drop-shadow-md">
                        </div>
                        <div class="flex flex-col justify-center h-16">
                            <h3 class="text-white font-bold text-lg leading-tight tracking-tight">Dinas Kominfo<br>Kabupaten Blitar</h3>
                        </div>
                    </div>

                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        Membangun masyarakat cerdas digital. Platform pembelajaran resmi untuk aparatur dan warga Kabupaten Blitar.
                    </p>
                    
                    {{-- Social Media (Tombol Bulat Modern) --}}
                    <div class="flex gap-3">
                        <a href="https://www.instagram.com/kominfo.blitarkab/" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-pink-600 hover:text-white transition-all duration-300 transform hover:-translate-y-1" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069-3.204 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- KOLOM KANAN: Kontak dengan Style Card Minimalis --}}
                <div class="flex flex-col justify-center md:items-end">
                    <div class="bg-slate-800/50 rounded-2xl p-6 border border-slate-700/50 backdrop-blur-sm max-w-md w-full">
                        <h4 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            Pusat Bantuan
                        </h4>
                        
                        <ul class="space-y-4 text-sm">
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-indigo-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Jl. S. Supriadi No.17, Kec. Sananwetan,<br>Kota Blitar, Jawa Timur 66133</span>
                            </li>
                            <li class="flex items-center gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span class="font-medium tracking-wide">(0342) 555955</span>
                            </li>
                            <li class="flex items-center gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <a href="mailto:kominfo@blitarkab.go.id" class="hover:text-white hover:underline transition-colors">kominfo@blitarkab.go.id</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            {{-- COPYRIGHT SIMPLE --}}
            <div class="mt-12 pt-6 border-t border-slate-800 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Dinas Komunikasi, Informatika, Statistik dan Persandian Kab. Blitar.</p>
            </div>
        </div>
    </footer>
</div>