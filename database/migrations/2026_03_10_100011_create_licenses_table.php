<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('software_id')->constrained('software')->cascadeOnDelete();
            $table->string('license_key', 255);
            $table->unsignedInteger('seats')->default(1);
            $table->date('expiration_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('software_id');
            $table->index('expiration_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
