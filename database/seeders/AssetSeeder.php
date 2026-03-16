<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $laptopCat = Category::where('type', 'asset')->where('name', 'Laptop')->first();
        $pcCat = Category::where('type', 'asset')->where('name', 'PC')->first();
        $printerCat = Category::where('type', 'asset')->where('name', 'Printer')->first();
        $switchCat = Category::where('type', 'asset')->where('name', 'Switch')->first();
        $serverCat = Category::where('type', 'asset')->where('name', 'Server')->first();
        $userIds = User::pluck('id')->toArray();

        $assets = [
            ['name' => 'ThinkPad X1 Carbon', 'category_id' => $laptopCat->id, 'brand' => 'Lenovo', 'model' => 'X1 Carbon Gen 11', 'serial_number' => 'LNV-X1C-001', 'purchase_cost' => 25000000, 'location' => 'Jakarta Office - Lt.3', 'status' => 'Active'],
            ['name' => 'ThinkPad T14', 'category_id' => $laptopCat->id, 'brand' => 'Lenovo', 'model' => 'T14 Gen 4', 'serial_number' => 'LNV-T14-001', 'purchase_cost' => 18000000, 'location' => 'Jakarta Office - Lt.2', 'status' => 'Active'],
            ['name' => 'MacBook Pro 14"', 'category_id' => $laptopCat->id, 'brand' => 'Apple', 'model' => 'M3 Pro', 'serial_number' => 'APL-MBP-001', 'purchase_cost' => 35000000, 'location' => 'Jakarta Office - Lt.5', 'status' => 'Active'],
            ['name' => 'Dell OptiPlex 7090', 'category_id' => $pcCat->id, 'brand' => 'Dell', 'model' => 'OptiPlex 7090', 'serial_number' => 'DEL-OPT-001', 'purchase_cost' => 15000000, 'location' => 'Server Room', 'status' => 'Active'],
            ['name' => 'HP LaserJet Pro', 'category_id' => $printerCat->id, 'brand' => 'HP', 'model' => 'M404dn', 'serial_number' => 'HP-LJP-001', 'purchase_cost' => 5000000, 'location' => 'Jakarta Office - Lt.2', 'status' => 'Active'],
            ['name' => 'Cisco Catalyst 2960', 'category_id' => $switchCat->id, 'brand' => 'Cisco', 'model' => 'WS-C2960X-24TS', 'serial_number' => 'CSC-CAT-001', 'purchase_cost' => 12000000, 'location' => 'Server Room', 'status' => 'Active'],
            ['name' => 'Dell PowerEdge R740', 'category_id' => $serverCat->id, 'brand' => 'Dell', 'model' => 'R740', 'serial_number' => 'DEL-SRV-001', 'purchase_cost' => 95000000, 'location' => 'Data Center', 'status' => 'Active'],
            ['name' => 'ThinkPad E14 (Retired)', 'category_id' => $laptopCat->id, 'brand' => 'Lenovo', 'model' => 'E14 Gen 2', 'serial_number' => 'LNV-E14-001', 'purchase_cost' => 12000000, 'location' => 'Warehouse', 'status' => 'Retired'],
        ];

        foreach ($assets as $i => $data) {
            $prefix = match ($data['category_id']) {
                    $laptopCat->id => 'LPT',
                    $pcCat->id => 'PC',
                    $printerCat->id => 'PRT',
                    $switchCat->id => 'SW',
                    $serverCat->id => 'SVR',
                    default => 'GEN',
                };

            Asset::create([
                ...$data,
                'asset_code' => Asset::generateAssetCode($prefix),
                'purchase_date' => now()->subMonths(rand(1, 24)),
                'warranty_expired' => now()->addMonths(rand(1, 36)),
                'assigned_user_id' => $i < 5 ? $userIds[array_rand($userIds)] : null,
            ]);
        }
    }
}
