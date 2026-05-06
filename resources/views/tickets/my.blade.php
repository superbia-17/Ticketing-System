@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-amber-600 transition-colors mb-2 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> 
                Kembali
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Tiket Saya</h1>
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">Daftar permohonan aduan yang Anda ajukan.</p>
        </div>
        <a href="{{ route('tickets.create') }}" class="flex items-center gap-2 bg-[#FFC107] hover:bg-amber-500 text-black font-black py-2.5 px-5 rounded-xl transition-all shadow-md active:scale-95 text-[10px] uppercase tracking-widest">
            <i class="fas fa-plus-circle"></i> Buat Tiket Baru
        </a>
    </div>

    <!-- Container Utama -->
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-5 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b">Informasi Tiket</th>
                            <th class="px-6 py-5 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b text-center">Kategori</th>
                            <th class="px-6 py-5 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b text-center">Prioritas</th>
                            <th class="px-6 py-5 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b text-center">Status</th>
                            <th class="px-6 py-5 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-gray-50/30 transition group">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-amber-600 mb-1 uppercase tracking-tighter italic">{{ $ticket->ticket_number }}</span>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-sm font-bold text-gray-900 group-hover:text-amber-600 transition-colors leading-tight">
                                        {{ $ticket->title }}
                                    </a>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase mt-1">{{ $ticket->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-[10px] font-black text-gray-500 bg-gray-100 px-3 py-1 rounded-lg uppercase">
                                    {{ $ticket->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @php
                                    // Menyesuaikan kunci array dengan data di database (low, medium, high)
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
                                <span class="{{ $priorityColors[$ticket->priority] ?? 'bg-gray-50 text-gray-500 border-gray-100' }} border px-3 py-1 rounded-lg text-[9px] font-black uppercase italic shadow-sm">
                                    {{ $priorityLabel[$ticket->priority] ?? $ticket->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @php
                                    $statusClasses = [
                                        'open' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'in_progress' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'resolved' => 'bg-green-50 text-green-600 border-green-100',
                                        'closed' => 'bg-gray-100 text-gray-500 border-gray-200'
                                    ];
                                @endphp
                                <span class="{{ $statusClasses[$ticket->status] ?? 'bg-gray-50 text-gray-500 border-gray-100' }} border px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-black hover:text-[#FFC107] transition-all shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-20 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 text-gray-200 rounded-[30px] mb-6 border border-dashed border-gray-200">
                    <i class="fas fa-ticket-alt text-4xl"></i>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Belum Ada Tiket</h3>
                <p class="text-[10px] text-gray-400 max-w-xs mx-auto font-bold uppercase mt-2">Anda belum pernah mengajukan tiket aduan.</p>
            </div>
        @endif
    </div>
</div>
@endsection