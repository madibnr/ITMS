<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('root_cause_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('root_cause');
            $table->text('contributing_factors')->nullable();
            $table->text('corrective_action');
            $table->text('preventive_action')->nullable();
            $table->foreignId('analyzed_by')->constrained('users');
            $table->enum('status', ['Draft', 'Under Review', 'Approved', 'Implemented'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('root_cause_analyses');
    }
};
