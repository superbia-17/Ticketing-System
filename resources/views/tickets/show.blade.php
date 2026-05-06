@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header & Navigasi -->
    <div class="flex items-center justify-between">
        <a href="{{ route('tickets.my') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-amber-600 transition-colors group">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> 
            Kembali ke Daftar Tiket
        </a>
        <div class="flex gap-2">
            @php
                $statusClasses = [
                    'open' => 'bg-blue-50 text-blue-600 border-blue-100',
                    'in_progress' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'resolved' => 'bg-green-50 text-green-600 border-green-100',
                    'closed' => 'bg-gray-100 text-gray-500 border-gray-200'
                ];
            @endphp
            <span class="{{ $statusClasses[$ticket->status] ?? 'bg-gray-50 text-gray-500' }} border px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                Status: {{ str_replace('_', ' ', $ticket->status) }}
            </span>
        </div>
    </div>

    <!-- Detail Utama Tiket -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-black p-6 md:p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-[10px] font-black text-amber-400 uppercase tracking-[0.3em]">ID Tiket: {{ $ticket->ticket_number }}</span>
                    <h1 class="text-2xl font-black tracking-tight mt-1">{{ $ticket->title }}</h1>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Dibuat Pada</p>
                    <p class="text-sm font-bold text-amber-500">{{ $ticket->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            <!-- Informasi Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pb-6 border-b border-gray-50">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kategori</p>
                    <p class="text-xs font-bold text-gray-800">{{ $ticket->category->name ?? 'Umum' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Prioritas</p>
                    <span class="text-[10px] font-black uppercase {{ $ticket->priority === 'urgent' ? 'text-red-600' : 'text-amber-600' }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pengirim</p>
                    <p class="text-xs font-bold text-gray-800">{{ $ticket->submitter->name ?? 'User' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">NIM/ID</p>
                    <p class="text-xs font-bold text-gray-800">{{ $ticket->submitter->nim ?? '-' }}</p>
                </div>
            </div>

            <!-- Deskripsi Masalah -->
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi Masalah</p>
                <div class="bg-gray-50 rounded-2xl p-6 text-sm text-gray-700 leading-relaxed font-medium italic border-l-4 border-amber-400">
                    "{{ $ticket->description }}"
                </div>
            </div>
        </div>
    </div>

    <!-- Area Percakapan (Chat Style) -->
    <div class="space-y-4">
        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-2 mt-8">Ruang Percakapan</h3>
        
        <div class="space-y-6">
            @forelse($ticket->responses as $response)
                @php
                    $isAdmin = $response->user->isStaff(); // Menggunakan helper isStaff dari model User
                @endphp
                
                <div class="flex {{ $isAdmin ? 'justify-start' : 'justify-end' }}">
                    <div class="max-w-[85%] md:max-w-[70%] space-y-1">
                        <div class="flex items-center gap-2 {{ $isAdmin ? 'flex-row' : 'flex-row-reverse' }} px-2">
                            <span class="text-[10px] font-black text-gray-400 uppercase">{{ $response->user->name }}</span>
                            <span class="text-[9px] font-bold {{ $isAdmin ? 'text-amber-600' : 'text-blue-600' }} uppercase">
                                {{ $isAdmin ? '• Staf UPB' : '• Anda' }}
                            </span>
                        </div>
                        
                        <div class="p-4 rounded-2xl shadow-sm border {{ $isAdmin ? 'bg-white border-gray-100 rounded-tl-none text-gray-800' : 'bg-black border-black rounded-tr-none text-white' }}">
                            <p class="text-sm leading-relaxed font-medium">{{ $response->message }}</p>
                        </div>
                        
                        <p class="text-[9px] font-bold text-gray-400 px-2 {{ $isAdmin ? 'text-left' : 'text-right' }}">
                            {{ $response->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100">
                    <i class="fas fa-comments text-3xl text-gray-200 mb-3"></i>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Belum ada balasan dari tim admin</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Form Balasan -->
    @if($ticket->allow_user_reply && $ticket->status !== 'closed')
        <div class="mt-10 bg-white rounded-3xl border border-gray-100 shadow-lg p-6">
            <form method="POST" action="{{ route('tickets.reply', $ticket->id) }}" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label for="response" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Tulis Balasan</label>
                    <textarea name="response" rows="3" required
                              placeholder="Ketik pesan balasan Anda di sini..."
                              class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium text-gray-900 focus:bg-white focus:border-amber-400 focus:ring-0 transition-all outline-none resize-none">{{ old('response') }}</textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-[#FFC107] hover:bg-amber-500 text-black font-black uppercase tracking-widest text-[10px] py-4 px-8 rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="mt-8 bg-gray-100 rounded-2xl p-6 text-center border-2 border-dashed border-gray-200">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                <i class="fas fa-lock mr-2"></i> Percakapan telah dikunci atau menunggu respon admin.
            </p>
        </div>
    @endif
</div>
@endsection