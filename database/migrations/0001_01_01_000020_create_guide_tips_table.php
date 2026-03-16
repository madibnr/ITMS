<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('guide_tips', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('fas fa-lightbulb'); // FontAwesome class
            $table->string('icon_bg')->default('#dbeafe'); // CSS colour
            $table->string('icon_color')->default('#2563eb'); // CSS colour
            $table->string('title');
            $table->text('body');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_tips');
    }
};
