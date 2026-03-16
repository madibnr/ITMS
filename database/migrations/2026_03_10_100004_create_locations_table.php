<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('type', ['location', 'building', 'floor', 'room'])->default('location');
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('parent_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
