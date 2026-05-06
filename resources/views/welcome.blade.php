@extends('layouts.app')

@section('content')
    <div class="min-h-[85vh] flex flex-col items-center justify-center relative overflow-hidden">
        <!-- Dekorasi Background (Lingkaran Kuning Blur) -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-amber-100 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-50 rounded-full blur-3xl opacity-50"></div>

        <div class="relative z-10 text-center max-w-4xl px-4">
            <!-- Badge Info -->
            <div
                class="inline-flex items-center gap-2 bg-white border border-gray-100 px-4 py-2 rounded-full shadow-sm mb-8 animate-bounce">
                <span class="flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Support Online 24/7</span>
            </div>

            <!-- Judul Utama -->
            <h1 class="text-5xl md:text-7xl font-black text-black tracking-tighter leading-none mb-6">
                SISTEM LAYANAN <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-300">TIKET
                    TERPADU</span>
            </h1>

            <p class="text-gray-500 text-lg md:text-xl font-medium max-w-2xl mx-auto mb-12 leading-relaxed">
                Butuh bantuan atau ingin menyampaikan aduan akademik? Kami siap melayani mahasiswa dan publik di lingkungan
                <a href="https://upb.ac.id" target="_blank"
                    class="font-bold text-black border-b-2 border-amber-400 hover:text-amber-600 hover:border-black transition-all">
                    Universitas Panca Bhakti
                </a>.
            </p>

            <!-- Tombol Aksi Utama -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('login') }}"
                    class="w-full sm:w-auto bg-black hover:bg-gray-800 text-[#FFC107] px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-sm shadow-2xl shadow-gray-400 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                    Buat Tiket Sekarang
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>

                <a href="/admin/login"
                    class="w-full sm:w-auto bg-white border-2 border-gray-100 hover:border-amber-400 text-gray-500 hover:text-amber-600 px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-sm transition-all active:scale-95 flex items-center justify-center gap-3 shadow-sm">
                    <i class="fas fa-user-shield"></i> Portal Staf
                </a>
            </div>

            <!-- Fitur Singkat -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-24">
                <div class="p-6 bg-white rounded-3xl border border-gray-50 shadow-sm">
                    <div
                        class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-4">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="font-black text-sm uppercase tracking-tight mb-2">Respon Cepat</h3>
                    <p class="text-xs text-gray-400 font-medium">Aduan Anda akan langsung diteruskan ke unit kerja terkait
                        di UPB.</p>
                </div>

                <div class="p-6 bg-white rounded-3xl border border-gray-50 shadow-sm">
                    <div
                        class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-4">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="font-black text-sm uppercase tracking-tight mb-2">Pantau Status</h3>
                    <p class="text-xs text-gray-400 font-medium">Lacak proses penanganan tiket Anda secara transparan dari
                        mana saja.</p>
                </div>

                <div class="p-6 bg-white rounded-3xl border border-gray-50 shadow-sm">
                    <div
                        class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-4">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3 class="font-black text-sm uppercase tracking-tight mb-2">Akses Terpadu</h3>
                    <p class="text-xs text-gray-400 font-medium">Tersedia untuk seluruh civitas akademika dan masyarakat
                        umum.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Kecil -->
    <footer class="py-10 text-center border-t border-gray-50">
        <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.4em]">
            © 2026 Universitas Panca Bhakti • Pontianak, Indonesia
        </p>
    </footer>
@endsection