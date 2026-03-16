<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code', 30)->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained();
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable()->unique();
            $table->json('specifications')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 15, 2)->nullable();
            $table->date('warranty_expired')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users');
            $table->enum('status', ['Active', 'Maintenance', 'Retired'])->default('Active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('warranty_expired');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
