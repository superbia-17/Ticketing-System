<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();
            // Nullable: staff can respond, but so can the original guest reporter
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            // Guest reporter name (when user_id is null)
            $table->string('responder_name')->nullable();
            $table->text('message');
            $table->boolean('is_internal')->default(false)
                ->comment('True = admin-only note, hidden from public');
            $table->timestamps();
        });

        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size')->comment('Size in bytes');
            $table->timestamps();
        });

        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();
            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->enum('old_status', ['open', 'in_progress', 'resolved', 'closed'])
                ->nullable();
            $table->enum('new_status', ['open', 'in_progress', 'resolved', 'closed']);
            $table->string('note')->nullable()->comment('Optional reason for change');
            $table->timestamps();

            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_histories');
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_responses');
    }
};