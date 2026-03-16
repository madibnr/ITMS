<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('software', function (Blueprint $table) {
            $table->id();
            $table->string('software_name', 200);
            $table->string('vendor', 150)->nullable();
            $table->string('version', 50)->nullable();
            $table->string('category', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('software');
    }
};
