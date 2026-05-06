@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo & Judul -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-black rounded-3xl shadow-xl mb-6 transform -rotate-6 group hover:rotate-0 transition-transform duration-300">
                <i class="fas fa-shield-alt text-[#FFC107] text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Masuk Akun</h2>
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] mt-2">Sistem Layanan Tiket UPB</p>
        </div>

        <!-- Card Login -->
        <div class="bg-white rounded-[40px] border border-gray-100 shadow-2xl shadow-gray-200/50 overflow-hidden">
            <div class="p-8 md:p-10">
                <form class="space-y-6" method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Input Email -->
                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Alamat Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-500 transition-colors">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl pl-12 pr-5 py-4 text-sm font-bold text-gray-900 focus:bg-white focus:border-amber-400 focus:ring-0 transition-all outline-none"
                                   placeholder="nama@email.com" value="{{ old('email') }}">
                        </div>
                    </div>

                    <!-- Input Password dengan See/Hide -->
                    <div class="space-y-2">
                        <label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Kata Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-500 transition-colors">
                                <i class="fas fa-lock text-sm"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl pl-12 pr-12 py-4 text-sm font-bold text-gray-900 focus:bg-white focus:border-amber-400 focus:ring-0 transition-all outline-none"
                                   placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password', 'eye-icon-login')" 
                                    class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-amber-500 transition-colors">
                                <i id="eye-icon-login" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
                            <ul class="text-[11px] text-red-700 font-bold uppercase tracking-tight">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full bg-black hover:bg-gray-800 text-[#FFC107] font-black uppercase tracking-[0.2em] py-5 rounded-2xl transition-all shadow-xl active:scale-[0.98] flex items-center justify-center gap-3 group">
                            Masuk Sekarang 
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 p-8 text-center border-t border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 ml-2 transition-colors">Daftar Akun Baru</a>
                </p>
            </div>
        </div>

        <!-- Tombol Kembali ke Beranda -->
        <div class="text-center mt-8">
            <a href="/" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] hover:text-black transition-all group">
                <i class="fas fa-home mr-2 group-hover:-translate-y-0.5 transition-transform"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection