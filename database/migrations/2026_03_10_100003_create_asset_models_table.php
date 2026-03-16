<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturer_id')->constrained()->cascadeOnDelete();
            $table->string('model_name', 150);
            $table->foreignId('category_id')->constrained();
            $table->string('image')->nullable();
            $table->json('specs')->nullable();
            $table->unsignedInteger('default_warranty_months')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['manufacturer_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_models');
    }
};
