<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentation_tag_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documentation_id')->constrained('documentations')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('documentation_tags')->cascadeOnDelete();
            $table->unique(['documentation_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentation_tag_relations');
    }
};
