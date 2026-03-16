<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type', 50)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();

            $table->index('asset_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_files');
    }
};
