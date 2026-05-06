<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Tiket UPB</title>
    
    <!-- Framework & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- TOP NAVIGATION BAR (Header Kuning UPB) -->
    <header class="bg-[#FFC107] h-[80px] flex items-center justify-between px-6 sticky top-0 z-50 shadow-sm border-b border-black/5">
        <div class="flex items-center gap-4">
            <!-- UPB LOGO & BRANDING -->
            <div class="flex items-center gap-4">
                <!-- Logo UPB dari Aset Lokal -->
                <div class="flex items-center justify-center">
                    <img src="{{ asset('images/logo-upb.png') }}" 
                         alt="Logo UPB" 
                         class="h-14 w-auto object-contain drop-shadow-sm">
                </div>
                
                <!-- Separator -->
                <div class="h-10 w-[2px] bg-black/20 hidden md:block"></div>

                <!-- Text Branding -->
                <div class="hidden sm:block">
                    <h1 class="font-extrabold text-black leading-tight text-xl tracking-tighter uppercase">UPB TICKETING</h1>
                    <span class="text-[10px] text-black/70 font-black uppercase tracking-widest block -mt-1">Sistem Layanan Aduan</span>
                </div>
            </div>
        </div>

        <!-- User Menu & Actions -->
        <div class="flex items-center gap-4">
            @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center gap-3 bg-white/20 p-1.5 px-3 rounded-2xl hover:bg-white/30 transition outline-none border border-black/10">
                    <div class="text-right hidden sm:block">
                        <p class="text-[9px] font-black text-black/60 uppercase leading-none mb-1">Status: {{ ucfirst(Auth::user()->role) }}</p>
                        <p class="text-xs font-bold text-black truncate max-w-[120px]">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-black flex items-center justify-center border-2 border-amber-400 overflow-hidden shadow-sm">
                        <i class="fas fa-user text-white text-xs"></i>
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-3 w-60 bg-white rounded-3xl shadow-2xl border border-gray-100 z-[60] overflow-hidden">
                    <div class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1 italic text-center">Informasi Akun</p>
                        <p class="text-xs font-bold text-gray-800 truncate text-center">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="p-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-4 text-xs text-red-600 hover:bg-red-50 rounded-2xl transition font-black text-left uppercase tracking-tighter">
                                <i class="fas fa-power-off w-4"></i> Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm font-black text-black hover:underline transition uppercase tracking-tighter">Masuk</a>
                <a href="{{ route('register') }}" class="text-sm font-black bg-black text-white px-6 py-3 rounded-2xl hover:bg-gray-800 transition uppercase tracking-tighter">Daftar</a>
            </div>
            @endauth
        </div>
    </header>

    <div class="flex flex-1">
        <!-- SIDEBAR NAVIGATION -->
        @auth
        <aside class="w-[280px] bg-white border-r h-[calc(100vh-80px)] sticky top-[80px] p-6 flex flex-col hidden lg:flex">
            <nav class="space-y-2 flex-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] ml-4 mb-4 text-center italic">Navigasi</p>
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-4 {{ Request::is('dashboard') ? 'bg-amber-50 text-amber-700 font-bold border-r-4 border-amber-500 shadow-sm shadow-amber-100' : 'text-gray-500 hover:bg-gray-50' }} px-5 py-4 rounded-2xl text-sm transition-all group">
                    <i class="fas fa-th-large w-5 text-center {{ Request::is('dashboard') ? 'text-amber-600' : 'group-hover:text-amber-500' }}"></i> 
                    Dashboard
                </a>

                <a href="{{ route('tickets.all') }}" 
                   class="flex items-center gap-4 {{ Request::is('tickets/all') ? 'bg-amber-50 text-amber-700 font-bold border-r-4 border-amber-500 shadow-sm shadow-amber-100' : 'text-gray-500 hover:bg-gray-50' }} px-5 py-4 rounded-2xl text-sm transition-all group">
                    <i class="fas fa-history w-5 text-center {{ Request::is('tickets/all') ? 'text-amber-600' : 'group-hover:text-amber-500' }}"></i> 
                    Riwayat Global
                </a>

                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] ml-4 mb-4 mt-8 text-center italic">Layanan Aduan</p>

                <a href="{{ route('tickets.my') }}" 
                   class="flex items-center gap-4 {{ Request::is('tickets/my') ? 'bg-amber-50 text-amber-700 font-bold border-r-4 border-amber-500 shadow-sm shadow-amber-100' : 'text-gray-500 hover:bg-gray-50' }} px-5 py-4 rounded-2xl text-sm transition-all group">
                    <i class="fas fa-folder-open w-5 text-center {{ Request::is('tickets/my') ? 'text-amber-600' : 'group-hover:text-amber-500' }}"></i> 
                    Tiket Saya
                </a>

                <a href="{{ route('tickets.create') }}" 
                   class="flex items-center gap-4 {{ Request::is('tickets/create') ? 'bg-black text-[#FFC107] font-bold shadow-xl shadow-gray-200' : 'text-gray-500 hover:bg-gray-50' }} px-5 py-4 rounded-2xl text-sm transition-all group mt-4">
                    <i class="fas fa-plus-circle w-5 text-center {{ Request::is('tickets/create') ? 'text-[#FFC107]' : 'group-hover:text-amber-500' }}"></i> 
                    Buat Tiket Baru
                </a>
            </nav>

            <div class="mt-auto pt-6">
                <div class="bg-gray-50 p-5 rounded-3xl border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 italic text-center">UPB Support</p>
                    <p class="text-[10px] font-bold text-gray-500 leading-relaxed text-center">Silakan sampaikan kendala teknis Anda melalui sistem ini.</p>
                </div>
            </div>
        </aside>
        @endauth

        <!-- CONTENT AREA -->
        <main class="flex-1 p-6 lg:p-12 overflow-y-auto">
            @if(session('success'))
                <div class="mb-8 p-5 bg-green-50 border-l-4 border-green-500 text-green-700 text-xs font-black uppercase tracking-tight rounded-r-3xl shadow-sm flex items-center gap-4 animate-fade-in">
                    <div class="bg-green-500 text-white w-6 h-6 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-check"></i>
                    </div>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-black uppercase tracking-tight rounded-r-3xl shadow-sm">
                    <div class="flex items-center gap-4 mb-3 text-red-800">
                        <div class="bg-red-500 text-white w-6 h-6 rounded-lg flex items-center justify-center shadow-md">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        Peringatan Sistem:
                    </div>
                    <ul class="list-disc ml-14 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>