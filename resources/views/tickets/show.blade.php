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
                    <span class="text-[10px] font-black uppercase {{ match($ticket->priority) { 'low' => 'text-blue-600', 'high' => 'text-red-600', default => 'text-amber-600' } }}">
                        {{ match($ticket->priority) {
                            'low' => 'Rendah',
                            'medium' => 'Sedang',
                            'high' => 'Tinggi',
                            default => ucfirst($ticket->priority)
                        } }}
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

            <!-- Lampiran Gambar Tiket -->
            @if($ticket->image)
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                    <i class="fas fa-paperclip mr-1"></i> Lampiran Gambar
                </p>
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <a href="{{ asset('storage/' . $ticket->image) }}" 
                       target="_blank"
                       class="block group relative overflow-hidden rounded-xl inline-block">
                        <img src="{{ asset('storage/' . $ticket->image) }}" 
                             alt="Lampiran tiket"
                             class="max-w-full max-h-[350px] rounded-xl object-cover border-2 border-gray-200 transition-transform group-hover:scale-[1.02]"
                             loading="lazy">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all rounded-xl flex items-center justify-center">
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg">
                                <i class="fas fa-expand mr-1"></i> Lihat Penuh
                            </span>
                        </div>
                    </a>
                </div>
            </div>
            @endif
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
                            @if($response->message)
                                <p class="text-sm leading-relaxed font-medium">{{ $response->message }}</p>
                            @endif

                            {{-- Display image attachment if present --}}
                            @if($response->image)
                                <div class="{{ $response->message ? 'mt-3' : '' }}">
                                    <a href="{{ asset('storage/' . $response->image) }}" 
                                       target="_blank"
                                       class="block group relative overflow-hidden rounded-xl">
                                        <img src="{{ asset('storage/' . $response->image) }}" 
                                             alt="Lampiran gambar"
                                             class="max-w-full max-h-[300px] rounded-xl object-cover border-2 {{ $isAdmin ? 'border-gray-200' : 'border-gray-600' }} transition-transform group-hover:scale-[1.02]"
                                             loading="lazy">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all rounded-xl flex items-center justify-center">
                                            <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg">
                                                <i class="fas fa-expand mr-1"></i> Lihat Penuh
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @endif
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
    @if(auth()->id() === $ticket->user_id && $ticket->allow_user_reply && $ticket->status !== 'closed')
        <div class="mt-10 bg-white rounded-3xl border border-gray-100 shadow-lg p-6" x-data="imageUpload()">
            <form method="POST" action="{{ route('tickets.reply', $ticket->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label for="response" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Tulis Balasan</label>
                    <textarea name="response" rows="3"
                              placeholder="Ketik pesan balasan Anda di sini..."
                              class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium text-gray-900 focus:bg-white focus:border-amber-400 focus:ring-0 transition-all outline-none resize-none">{{ old('response') }}</textarea>
                </div>

                {{-- Image Upload Area --}}
                <div class="space-y-3">
                    {{-- Image Preview --}}
                    <template x-if="preview">
                        <div class="relative inline-block">
                            <img :src="preview" 
                                 class="max-h-[200px] rounded-2xl border-2 border-amber-200 shadow-sm object-cover">
                            <button type="button" 
                                    @click="removeImage()"
                                    class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all hover:scale-110">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                            <div class="absolute bottom-2 left-2 bg-black/60 text-white text-[9px] font-bold px-2 py-1 rounded-lg uppercase" x-text="fileName"></div>
                        </div>
                    </template>

                    {{-- Upload Button --}}
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 bg-gray-50 hover:bg-gray-100 border-2 border-dashed border-gray-200 hover:border-amber-300 text-gray-500 hover:text-amber-600 font-bold text-[10px] uppercase tracking-widest py-3 px-5 rounded-xl cursor-pointer transition-all group">
                            <i class="fas fa-image text-sm group-hover:scale-110 transition-transform"></i>
                            <span x-text="preview ? 'Ganti Gambar' : 'Lampirkan Gambar'"></span>
                            <input type="file" 
                                   name="image" 
                                   accept="image/jpeg,image/jpg,image/png"
                                   @change="handleFile($event)"
                                   class="hidden"
                                   x-ref="fileInput">
                        </label>
                        <span class="text-[9px] text-gray-300 font-bold uppercase">Maks. 5MB • JPG, PNG</span>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-[#FFC107] hover:bg-amber-500 text-black font-black uppercase tracking-widest text-[10px] py-4 px-8 rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Balasan
                    </button>
                </div>
            </form>
        </div>

        <script>
            function imageUpload() {
                return {
                    preview: null,
                    fileName: '',

                    handleFile(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        // Validate file size (5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Ukuran gambar maksimal 5MB.');
                            event.target.value = '';
                            return;
                        }

                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Format gambar tidak didukung. Gunakan JPG atau PNG.');
                            event.target.value = '';
                            return;
                        }

                        this.fileName = file.name;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.preview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    },

                    removeImage() {
                        this.preview = null;
                        this.fileName = '';
                        this.$refs.fileInput.value = '';
                    }
                }
            }
        </script>
    @elseif(auth()->id() !== $ticket->user_id)
        <div class="mt-8 bg-gray-100 rounded-2xl p-6 text-center border-2 border-dashed border-gray-200">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                <i class="fas fa-eye mr-2"></i> Anda dapat melihat riwayat percakapan, tetapi tidak bisa mengirim balasan karena bukan pemilik tiket.
            </p>
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