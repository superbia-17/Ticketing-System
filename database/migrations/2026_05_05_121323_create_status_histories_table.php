<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menambahkan pengecekan agar tidak error jika tabel sudah ada
        if (!Schema::hasTable('status_histories')) {
            Schema::create('status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
                $table->string('status');
                $table->foreignId('changed_by')->constrained('users');
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('status_histories');
    }
};