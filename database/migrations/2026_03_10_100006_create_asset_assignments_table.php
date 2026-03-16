<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to_user_id')->constrained('users');
            $table->foreignId('assigned_by')->constrained('users');
            $table->date('assigned_date');
            $table->date('expected_return_date')->nullable();
            $table->date('returned_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['asset_id', 'returned_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_assignments');
    }
};
