@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full">
        <!-- Logo & Judul -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-black rounded-3xl shadow-xl mb-6 transform rotate-6 group hover:rotate-0 transition-transform duration-300">
                <i class="fas fa-user-plus text-[#FFC107] text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Daftar Akun Baru</h2>
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] mt-2">Sistem Layanan Tiket UPB</p>
        </div>

        <div class="bg-white rounded-[40px] border border-gray-100 shadow-2xl shadow-gray-200/50 overflow-hidden">
            <div class="p-8 md:p-12">
                <form class="space-y-6" method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-900 focus:bg-white focus:border-amber-400 outline-none"
                                   placeholder="Nama Lengkap" value="{{ old('name') }}">
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Alamat Email</label>
                            <input id="email" name="email" type="email" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-900 focus:bg-white focus:border-amber-400 outline-none"
                                   placeholder="nama@email.com" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="role" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Mendaftar Sebagai</label>
                            <div class="relative">
                                <select id="role" name="role"
                                        class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-900 focus:bg-white focus:border-amber-400 outline-none appearance-none">
                                    <option value="public" {{ old('role') == 'public' ? 'selected' : '' }}>Publik / Umum</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Mahasiswa UPB</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div id="nimField" class="space-y-2" style="display: {{ old('role') == 'student' ? 'block' : 'none' }};">
                            <label for="nim" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">NIM</label>
                            <input id="nim" name="nim" type="text"
                                   class="w-full bg-gray-50 border-2 border-gray-100 border-dashed rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-900 focus:bg-white focus:border-amber-400 outline-none"
                                   placeholder="12345678" value="{{ old('nim') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password See/Hide -->
                        <div class="space-y-2">
                            <label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Kata Sandi</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required
                                       class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl pl-5 pr-12 py-3.5 text-sm font-bold text-gray-900 focus:bg-white focus:border-amber-400 outline-none"
                                       placeholder="••••••••">
                                <button type="button" onclick="togglePassword('password', 'eye-reg-1')" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                                    <i id="eye-reg-1" class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password See/Hide -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Konfirmasi Sandi</label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                       class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl pl-5 pr-12 py-3.5 text-sm font-bold text-gray-900 focus:bg-white focus:border-amber-400 outline-none"
                                       placeholder="••••••••">
                                <button type="button" onclick="togglePassword('password_confirmation', 'eye-reg-2')" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                                    <i id="eye-reg-2" class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full bg-black hover:bg-gray-800 text-[#FFC107] font-black uppercase tracking-[0.2em] py-5 rounded-2xl transition-all shadow-xl active:scale-[0.98] flex items-center justify-center gap-3">
                            Buat Akun Sekarang <i class="fas fa-check-circle text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 p-8 text-center border-t border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 ml-2 transition-colors font-black">Masuk Disini</a>
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

    document.getElementById('role').addEventListener('change', function() {
        const nimField = document.getElementById('nimField');
        const nimInput = document.getElementById('nim');
        if (this.value === 'student') {
            nimField.style.display = 'block';
            nimInput.required = true;
        } else {
            nimField.style.display = 'none';
            nimInput.required = false;
        }
    });
</script>
@endsection