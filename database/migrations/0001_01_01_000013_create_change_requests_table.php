<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->string('change_number', 20)->unique();
            $table->string('title');
            $table->text('description');
            $table->text('reason');
            $table->enum('impact', ['Low', 'Medium', 'High'])->default('Low');
            $table->enum('risk_level', ['Low', 'Medium', 'High'])->default('Low');
            $table->enum('status', ['Draft', 'Submitted', 'Approved', 'Rejected', 'Implemented', 'Closed'])->default('Draft');
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->dateTime('scheduled_date')->nullable();
            $table->dateTime('implemented_at')->nullable();
            $table->text('rollback_plan')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
