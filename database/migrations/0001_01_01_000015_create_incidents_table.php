<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number', 20)->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('severity', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->enum('status', ['Open', 'Investigating', 'Resolved', 'Closed'])->default('Open');
            $table->foreignId('reported_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('related_asset_id')->nullable()->constrained('assets');
            $table->foreignId('related_ticket_id')->nullable()->constrained('tickets');
            $table->text('impact_description')->nullable();
            $table->text('resolution')->nullable();
            $table->dateTime('occurred_at');
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
