@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Riwayat Global</h1>
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-1">Memantau seluruh aktivitas aduan di sistem.</p>
        </div>
        <div class="bg-white px-5 py-3 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 text-sm">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Total Aduan</p>
                <p class="text-lg font-black text-black leading-none mt-1">{{ $tickets->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Global -->
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
        @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0 table-fixed">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400">
                            <th class="w-[20%] px-6 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b">Nomor Tiket</th>
                            <th class="w-[20%] px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b text-left">Pengirim</th>
                            <th class="w-[20%] px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b text-left">Judul & Kategori</th>
                            <th class="w-[20%] px-4 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b text-center">Prioritas</th>
                            <th class="w-[20%] px-6 py-4 text-[9px] font-black uppercase tracking-[0.2em] border-b text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-6 py-5 italic text-[10px] font-bold text-gray-500">
                                {{ $ticket->ticket_number }}
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex items-center justify-start gap-2">
                                    <div class="w-6 h-6 bg-amber-100 text-amber-700 rounded flex items-center justify-center text-[9px] font-black uppercase">
                                        {{ substr($ticket->submitter->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-[11px] font-bold text-gray-700 leading-none truncate">{{ $ticket->submitter->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-left">
                                <p class="text-[11px] font-black text-gray-900 uppercase tracking-tighter truncate">{{ $ticket->title }}</p>
                                <p class="text-[9px] text-gray-400 font-bold italic mt-0.5 leading-none">{{ $ticket->category->name ?? 'Umum' }}</p>
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
                                <span class="{{ $priorityColors[$ticket->priority] ?? 'bg-gray-50 text-gray-500' }} border px-3 py-1 rounded-lg text-[9px] font-black uppercase italic shadow-sm inline-block">
                                    {{ $priorityLabel[$ticket->priority] ?? $ticket->priority }}
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
                                <span class="{{ $statusClasses[$ticket->status] ?? 'bg-gray-50 text-gray-500' }} border px-2.5 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter inline-block shadow-sm">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-50 bg-gray-50/30">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <i class="fas fa-inbox text-2xl text-gray-200 mb-3 block"></i>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tidak Ada Riwayat Aduan</h3>
            </div>
        @endif
    </div>
</div>
@endsection