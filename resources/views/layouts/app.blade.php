<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Tiket UPB</title>
    
    <!-- Framework & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        [x-cloak] { display: none !important; }

        /* Notification bell animation */
        @keyframes bellRing {
            0%, 100% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-14deg); }
            30% { transform: rotate(10deg); }
            40% { transform: rotate(-8deg); }
            50% { transform: rotate(4deg); }
            60% { transform: rotate(-2deg); }
            70% { transform: rotate(0deg); }
        }
        .bell-ring { animation: bellRing 0.8s ease-in-out; }

        @keyframes notifSlideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .notif-slide-in { animation: notifSlideIn 0.2s ease-out forwards; }

        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .badge-pulse { animation: badgePulse 2s ease-in-out infinite; }

        /* Notification scrollbar */
        .notif-scroll::-webkit-scrollbar { width: 4px; }
        .notif-scroll::-webkit-scrollbar-track { background: transparent; }
        .notif-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 999px; }
        .notif-scroll::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
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
        <div class="flex items-center gap-3">
            @auth
            <!-- NOTIFICATION BELL -->
            <div class="relative" x-data="notificationBell()" x-init="init()">
                <button @click="toggleDropdown()" 
                        class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 transition-all border border-black/10 outline-none group"
                        :class="{ 'bg-white/40': open }">
                    <i class="fas fa-bell text-black text-sm transition-transform group-hover:scale-110"
                       :class="{ 'bell-ring': hasNew }"></i>
                    
                    <!-- Unread Badge -->
                    <template x-if="unreadCount > 0">
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[9px] font-black min-w-[18px] h-[18px] flex items-center justify-center rounded-full shadow-lg border-2 border-[#FFC107] badge-pulse"
                              x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                    </template>
                </button>

                <!-- Notification Dropdown -->
                <div x-show="open" x-cloak
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-3 w-[360px] bg-white rounded-3xl shadow-2xl border border-gray-100 z-[60] overflow-hidden">
                    
                    <!-- Dropdown Header -->
                    <div class="px-5 py-4 bg-gradient-to-r from-gray-900 to-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-amber-500 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-bell text-black text-[10px]"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-white uppercase tracking-wider">Notifikasi</p>
                                <p class="text-[9px] text-gray-400 font-bold" x-text="unreadCount > 0 ? unreadCount + ' belum dibaca' : 'Semua sudah dibaca'"></p>
                            </div>
                        </div>
                        <button @click.stop="markAllRead()" 
                                x-show="unreadCount > 0"
                                class="text-[9px] font-black text-amber-400 hover:text-amber-300 uppercase tracking-widest transition-colors">
                            Baca Semua
                        </button>
                    </div>

                    <!-- Notification List -->
                    <div class="max-h-[340px] overflow-y-auto notif-scroll">
                        <template x-if="notifications.length === 0">
                            <div class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-50 text-gray-200 rounded-2xl mb-4 border-2 border-dashed border-gray-200">
                                    <i class="fas fa-bell-slash text-xl"></i>
                                </div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum Ada Notifikasi</p>
                                <p class="text-[9px] text-gray-300 font-bold mt-1">Anda akan mendapat notifikasi saat admin membalas tiket.</p>
                            </div>
                        </template>

                        <template x-for="(notif, index) in notifications" :key="notif.id">
                            <a :href="'{{ url('/notifications') }}/' + notif.id + '/read'"
                               @click.prevent="goToNotif(notif)"
                               class="block px-5 py-4 hover:bg-amber-50/50 transition-all border-b border-gray-50 last:border-0 group notif-slide-in"
                               :class="{ 'bg-amber-50/30': !notif.read_at }"
                               :style="'animation-delay: ' + (index * 50) + 'ms'">
                                <div class="flex gap-3">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shadow-sm"
                                             :class="notif.read_at ? 'bg-gray-100 text-gray-400' : 'bg-gradient-to-br from-amber-400 to-amber-500 text-black'">
                                            <i class="fas fa-reply text-[10px]"></i>
                                        </div>
                                    </div>
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[9px] font-black uppercase tracking-wider"
                                                  :class="notif.read_at ? 'text-gray-400' : 'text-amber-600'"
                                                  x-text="notif.ticket_number"></span>
                                            <template x-if="!notif.read_at">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            </template>
                                        </div>
                                        <p class="text-[11px] font-bold text-gray-800 leading-snug truncate group-hover:text-amber-700 transition-colors"
                                           x-text="notif.responder_name + ' membalas: ' + notif.ticket_title"></p>
                                        <p class="text-[10px] text-gray-400 mt-1 truncate italic" x-text="'\"' + notif.message_preview + '\"'"></p>
                                        <p class="text-[9px] text-gray-300 font-bold mt-1.5 uppercase" x-text="notif.created_at"></p>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

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

    @auth
    <script>
        function notificationBell() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                hasNew: false,
                pollInterval: null,

                init() {
                    this.fetchUnreadCount();
                    // Poll every 30 seconds
                    this.pollInterval = setInterval(() => {
                        this.fetchUnreadCount();
                    }, 30000);
                },

                async fetchUnreadCount() {
                    try {
                        const res = await fetch('{{ route("notifications.unread-count") }}');
                        const data = await res.json();
                        const oldCount = this.unreadCount;
                        this.unreadCount = data.count;
                        
                        // Trigger bell animation if new notifications arrived
                        if (data.count > oldCount && oldCount !== 0) {
                            this.hasNew = true;
                            setTimeout(() => this.hasNew = false, 1000);
                        }
                    } catch (e) {
                        console.error('Failed to fetch notification count:', e);
                    }
                },

                async fetchNotifications() {
                    try {
                        const res = await fetch('{{ route("notifications.latest") }}');
                        const data = await res.json();
                        this.notifications = data.notifications;
                    } catch (e) {
                        console.error('Failed to fetch notifications:', e);
                    }
                },

                toggleDropdown() {
                    this.open = !this.open;
                    if (this.open) {
                        this.fetchNotifications();
                    }
                },

                async markAllRead() {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        await fetch('{{ route("notifications.mark-all-read") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        });
                        this.unreadCount = 0;
                        this.notifications = this.notifications.map(n => ({
                            ...n,
                            read_at: new Date().toISOString()
                        }));
                    } catch (e) {
                        console.error('Failed to mark all as read:', e);
                    }
                },

                async goToNotif(notif) {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        await fetch('/notifications/' + notif.id + '/read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        });
                    } catch (e) {
                        // Proceed with redirect even if mark-as-read fails
                    }
                    window.location.href = '/tickets/' + notif.ticket_id;
                },

                destroy() {
                    if (this.pollInterval) {
                        clearInterval(this.pollInterval);
                    }
                }
            }
        }
    </script>
    @endauth

</body>
</html>