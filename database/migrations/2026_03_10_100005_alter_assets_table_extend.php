<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Change status from enum to string to support more values
        // MySQL requires special handling for enum → string change
        DB::statement("ALTER TABLE assets MODIFY COLUMN status VARCHAR(30) NOT NULL DEFAULT 'Active'");

        Schema::table('assets', function (Blueprint $table) {
            $table->string('asset_tag', 50)->nullable()->unique()->after('id');
            $table->foreignId('model_id')->nullable()->after('category_id')
                ->constrained('asset_models')->nullOnDelete();
            $table->string('supplier', 150)->nullable()->after('purchase_cost');
            $table->date('warranty_expiration')->nullable()->after('warranty_expired');
            $table->foreignId('location_id')->nullable()->after('location')
                ->constrained('locations')->nullOnDelete();

            $table->index('asset_tag');
            $table->index('model_id');
            $table->index('location_id');
        });

        // Step 2: Copy warranty_expired to warranty_expiration for existing records
        DB::statement('UPDATE assets SET warranty_expiration = warranty_expired WHERE warranty_expired IS NOT NULL');

        // Step 3: Generate asset_tag from asset_code for existing records
        DB::statement('UPDATE assets SET asset_tag = asset_code WHERE asset_code IS NOT NULL');
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['model_id']);
            $table->dropForeign(['location_id']);
            $table->dropIndex(['asset_tag']);
            $table->dropIndex(['model_id']);
            $table->dropIndex(['location_id']);
            $table->dropColumn(['asset_tag', 'model_id', 'supplier', 'warranty_expiration', 'location_id']);
        });

        DB::statement("ALTER TABLE assets MODIFY COLUMN status ENUM('Active','Maintenance','Retired') NOT NULL DEFAULT 'Active'");
    }
};
