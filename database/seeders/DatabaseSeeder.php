<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Administrator (Akses Filament)
        User::create([
            'name' => 'Administrator UPB',
            'email' => 'admin@upb.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'nim' => null,
            'phone' => '08123456789',
        ]);

        // 2. Membuat Kategori Tiket Awal
        // Penting agar dropdown kategori di halaman 'Create Ticket' tidak kosong
        $categories = [
            ['name' => 'Akademik & Perkuliahan', 'slug' => 'akademik'],
            ['name' => 'Fasilitas Kampus', 'slug' => 'fasilitas'],
            ['name' => 'Sistem Informasi (SIAKAD/Portal)', 'slug' => 'it-support'],
            ['name' => 'Administrasi Keuangan', 'slug' => 'keuangan'],
            ['name' => 'Layanan Perpustakaan', 'slug' => 'perpustakaan'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}