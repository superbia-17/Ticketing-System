<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('reporter_name')->nullable()->after('title');
            $table->string('reporter_email')->nullable()->after('reporter_name');
            $table->string('reporter_nim')->nullable()->after('reporter_email');
            $table->string('reporter_phone')->nullable()->after('reporter_nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['reporter_name', 'reporter_email', 'reporter_nim', 'reporter_phone']);
        });
    }
};
