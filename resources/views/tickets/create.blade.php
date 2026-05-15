@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Tombol Kembali (Ramping) -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" 
           class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-amber-600 transition-colors group">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> 
            Kembali
        </a>
    </div>

    <!-- Card Utama Form -->
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <!-- Header Form (Lebih Ramping) -->
        <div class="bg-black p-6 text-white flex items-center gap-4">
            <div class="w-10 h-10 bg-[#FFC107] rounded-xl flex items-center justify-center text-black shadow-md">
                <i class="fas fa-pen-nib text-sm"></i>
            </div>
            <div>
                <h1 class="text-lg font-black tracking-tight uppercase leading-none">Buat Tiket Baru</h1>
                <p class="text-gray-400 text-[9px] font-bold uppercase tracking-widest mt-1">Sampaikan aduan atau kendala Anda</p>
            </div>
        </div>

        <!-- Body Form -->
        <div class="p-6 md:p-8" x-data="ticketImageUpload()">
            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Input: Judul & Kategori -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Judul Tiket -->
                    <div class="space-y-1.5">
                        <label for="title" class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Subjek Aduan</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               placeholder="Contoh: Kendala Portal"
                               class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs font-bold text-gray-900 focus:bg-white focus:border-amber-400 transition-all outline-none">
                        @error('title')
                            <p class="text-[9px] text-red-500 font-bold mt-1 ml-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-1.5">
                        <label for="category_id" class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori Layanan</label>
                        <div class="relative">
                            <select name="category_id" id="category_id" required
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs font-bold text-gray-900 focus:bg-white focus:border-amber-400 transition-all outline-none appearance-none">
                                <option value="" disabled selected>Pilih Layanan...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prioritas (3 Tingkat: Rendah, Sedang, Tinggi) -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Tingkat Prioritas</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi'] as $value => $label)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="priority" value="{{ $value }}" {{ old('priority', 'medium') == $value ? 'checked' : '' }} class="peer sr-only">
                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-2.5 text-center transition-all group-hover:bg-gray-100 peer-checked:bg-amber-50 peer-checked:border-amber-500">
                                <p class="text-[10px] font-black uppercase tracking-tighter text-gray-600 peer-checked:text-amber-700">
                                    {{ $label }}
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Deskripsi Detail -->
                <div class="space-y-1.5">
                    <label for="description" class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Deskripsi Lengkap</label>
                    <textarea name="description" id="description" rows="4" required
                              placeholder="Ceritakan kendala Anda secara detail..."
                              class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-xs font-medium text-gray-900 focus:bg-white focus:border-amber-400 transition-all outline-none resize-none">{{ old('description') }}</textarea>
                </div>

                <!-- Lampiran Gambar -->
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Lampiran Gambar (Opsional)</label>
                    
                    <!-- Image Preview -->
                    <template x-if="preview">
                        <div class="relative inline-block animate-fade-in">
                            <img :src="preview" 
                                 class="max-h-[220px] rounded-2xl border-2 border-amber-200 shadow-sm object-cover">
                            <button type="button" 
                                    @click="removeImage()"
                                    class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all hover:scale-110">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                            <div class="absolute bottom-2 left-2 bg-black/60 text-white text-[9px] font-bold px-2 py-1 rounded-lg uppercase" x-text="fileName"></div>
                        </div>
                    </template>

                    <!-- Upload Button -->
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 bg-gray-50 hover:bg-gray-100 border-2 border-dashed border-gray-200 hover:border-amber-300 text-gray-500 hover:text-amber-600 font-bold text-[10px] uppercase tracking-widest py-3 px-5 rounded-xl cursor-pointer transition-all group">
                            <i class="fas fa-image text-sm group-hover:scale-110 transition-transform"></i>
                            <span x-text="preview ? 'Ganti Gambar' : 'Pilih Gambar'"></span>
                            <input type="file" 
                                   name="image" 
                                   accept="image/jpeg,image/jpg,image/png"
                                   @change="handleFile($event)"
                                   class="hidden"
                                   x-ref="fileInput">
                        </label>
                        <span class="text-[9px] text-gray-300 font-bold uppercase">Maks. 5MB • JPG, PNG</span>
                    </div>
                    @error('image')
                        <p class="text-[9px] text-red-500 font-bold mt-1 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Submit (Ramping) -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-[#FFC107] hover:bg-amber-500 text-black font-black uppercase tracking-widest py-4 rounded-2xl transition-all shadow-md active:scale-[0.98] flex items-center justify-center gap-2 text-[10px]">
                        <i class="fas fa-paper-plane text-[9px]"></i> Kirim Aduan
                    </button>
                    <p class="text-center text-[8px] text-gray-400 font-black mt-4 uppercase tracking-[0.2em]">
                        Tiket Anda akan segera diproses oleh tim administrasi
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function ticketImageUpload() {
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
@endsection