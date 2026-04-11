<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique()->comment('e.g. TKT-2026-00042');
            $table->string('title');
            $table->text('description');

            // Reporter info — always filled, even for guests
            $table->string('reporter_name');
            $table->string('reporter_email');
            $table->string('reporter_phone', 20)->nullable();

            // Only filled when submitted by a logged-in student
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])
                ->default('open');

            $table->enum('priority', ['low', 'medium', 'high'])
                ->default('medium');

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Staff or admin handling this ticket');

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // Indexes for frequent queries
            $table->index('status');
            $table->index('priority');
            $table->index('reporter_email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};