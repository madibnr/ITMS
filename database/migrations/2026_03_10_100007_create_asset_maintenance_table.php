<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('maintenance_type', 100);
            $table->text('description')->nullable();
            $table->string('vendor', 150)->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['Scheduled', 'In Progress', 'Completed'])->default('Scheduled');
            $table->timestamps();

            $table->index(['asset_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance');
    }
};
