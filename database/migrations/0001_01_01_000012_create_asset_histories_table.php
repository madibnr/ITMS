<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('action', 100);
            $table->text('description')->nullable();
            $table->foreignId('performed_by')->constrained('users');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};
