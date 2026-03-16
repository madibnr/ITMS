<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('documentation_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content');
            $table->string('attachment')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};
