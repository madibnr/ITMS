<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('license_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('assigned_at')->useCurrent();

            $table->unique(['license_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_user');
    }
};
