<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('closed_by')->nullable()->constrained('users')->after('resolution_note');
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['closed_by']);
            $table->dropColumn('closed_by');
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
