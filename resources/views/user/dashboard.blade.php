@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- GREETING SECTION -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight uppercase">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 text-[10px] mt-1 font-bold uppercase tracking-widest">
                Panel Kontrol Sistem Tiket — <span class="text-amber-600">{{ Auth::user()->role }}</span>
                @if(Auth::user()->nim) | NIM: {{ Auth::user()->nim }} @endif
            </p>
        </div>
        <a href="{{ route('tickets.create') }}" class="flex items-center gap-2 bg-[#FFC107] hover:bg-amber-500 text-black font-black py-2.5 px-5 rounded-xl transition-all shadow-md active:scale-95 text-[10px] uppercase tracking-widest">
            <i class="fas fa-plus-circle"></i> Buat Tiket Baru
        </a>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg shadow-inner">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div>
                <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest">Total Aduan Global</p>
                <p class="text-xl font-black text-gray-900">{{ $allTickets->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-lg shadow-inner">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest">Aduan Menunggu</p>
                <p class="text-xl font-black text-gray-900">
                    {{ $allTickets->whereIn('status', ['open', 'in_progress'])->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-lg shadow-inner">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest">Aduan Selesai</p>
                <p class="text-xl font-black text-gray-900">
                    {{ $allTickets->whereIn('status', ['resolved', 'closed'])->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- GLOBAL HISTORY TABLE (Limit 5 Tiket) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <div>
                <h3 class="font-black text-gray-900 uppercase text-sm tracking-tight">Riwayat Tiket Global</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Aduan terbaru di sistem.</p>
            </div>
            <a href="{{ route('tickets.all') }}" class="text-[9px] font-black text-amber-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0 table-fixed">
                <thead>
                    <tr class="bg-white text-gray-400">
                        <th class="w-[20%] px-6 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b">Nomor Tiket</th>
                        <th class="w-[20%] px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b text-left">Pengirim</th>
                        <th class="w-[20%] px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b text-left">Judul & Kategori</th>
                        <th class="w-[20%] px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b text-center">Prioritas</th>
                        <th class="w-[20%] px-6 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($allTickets as $t)
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-6 py-5 italic text-[10px] font-bold text-gray-500">
                            {{ $t->ticket_number }}
                        </td>
                        <td class="px-4 py-5">
                            <div class="flex items-center justify-start gap-2">
                                <div class="w-6 h-6 bg-amber-100 text-amber-700 rounded flex items-center justify-center text-[9px] font-black uppercase">
                                    {{ substr($t->submitter->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-[11px] font-bold text-gray-700 leading-none truncate">{{ $t->submitter->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-5 text-left">
                            <p class="text-[11px] font-black text-gray-900 uppercase tracking-tighter truncate">{{ $t->title }}</p>
                            <p class="text-[9px] text-gray-400 font-bold italic mt-0.5 leading-none">{{ $t->category->name ?? 'Umum' }}</p>
                        </td>
                        <td class="px-4 py-5 text-center">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'medium' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'high' => 'bg-red-50 text-red-600 border-red-100'
                                ];
                                $priorityLabel = [
                                    'low' => 'Rendah',
                                    'medium' => 'Sedang',
                                    'high' => 'Tinggi'
                                ];
                            @endphp
                            <span class="{{ $priorityColors[$t->priority] ?? 'bg-gray-50 text-gray-500' }} border px-3 py-1 rounded-lg text-[9px] font-black uppercase italic shadow-sm inline-block">
                                {{ $priorityLabel[$t->priority] ?? $t->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            @php
                                $statusClasses = [
                                    'open' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'in_progress' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'resolved' => 'bg-green-50 text-green-600 border-green-100',
                                    'closed' => 'bg-gray-100 text-gray-500 border-gray-200'
                                ];
                            @endphp
                            <span class="{{ $statusClasses[$t->status] ?? 'bg-gray-50 text-gray-500' }} border px-2.5 py-0.5 rounded text-[8px] font-black uppercase shadow-sm">
                                {{ str_replace('_', ' ', $t->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-[10px] text-gray-400 font-black uppercase">
                            Belum ada data tiket.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection