<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        // 1. Create ticket_reporters table
        Schema::create('ticket_reporters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('full_name');
            $table->string('nik', 50)->index();
            $table->string('whatsapp', 20);
            $table->string('email')->index();
            $table->timestamps();
        });

        // 2. Update tickets table
        Schema::table('tickets', function (Blueprint $table) {
            // Make user_id nullable (public tickets have no authenticated user)
            $table->foreignId('user_id')->nullable()->change();

            // Add reporter reference
            $table->foreignId('reporter_id')->nullable()->after('user_id')
                ->constrained('ticket_reporters')->nullOnDelete();

            // Add metadata for public submissions
            $table->string('ip_address', 45)->nullable()->after('resolution_note');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->enum('source', ['internal', 'public'])->default('internal')->after('user_agent');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['reporter_id']);
            $table->dropColumn(['reporter_id', 'ip_address', 'user_agent', 'source']);
        });

        Schema::dropIfExists('ticket_reporters');
    }
};
