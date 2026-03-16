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
            $table->string('ticket_number', 20)->unique();
            $table->string('title');
            $table->text('description');
            $table->foreignId('category_id')->constrained();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed'])->default('Open');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->dateTime('sla_deadline')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->text('resolution_note')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index('sla_deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
