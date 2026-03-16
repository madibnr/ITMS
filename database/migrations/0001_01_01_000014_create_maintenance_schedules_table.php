<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('asset_id')->nullable()->constrained();
            $table->enum('maintenance_type', ['Preventive', 'Corrective', 'Emergency'])->default('Preventive');
            $table->enum('frequency', ['Daily', 'Weekly', 'Monthly', 'Quarterly', 'Yearly', 'One-time'])->nullable();
            $table->dateTime('scheduled_date');
            $table->dateTime('completed_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->enum('status', ['Scheduled', 'In Progress', 'Completed', 'Cancelled'])->default('Scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'scheduled_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
