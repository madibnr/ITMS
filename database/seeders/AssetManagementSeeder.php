<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Location;
use App\Models\Software;
use App\Models\License;
use Illuminate\Database\Seeder;

class AssetManagementSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Manufacturers ──────────────────────────────
        $manufacturers = [
            ['name' => 'Dell', 'website' => 'https://www.dell.com', 'support_email' => 'support@dell.com'],
            ['name' => 'HP', 'website' => 'https://www.hp.com', 'support_email' => 'support@hp.com'],
            ['name' => 'Lenovo', 'website' => 'https://www.lenovo.com', 'support_email' => 'support@lenovo.com'],
            ['name' => 'Cisco', 'website' => 'https://www.cisco.com', 'support_email' => 'support@cisco.com'],
            ['name' => 'Apple', 'website' => 'https://www.apple.com', 'support_email' => 'support@apple.com'],
            ['name' => 'Samsung', 'website' => 'https://www.samsung.com'],
            ['name' => 'APC', 'website' => 'https://www.apc.com'],
            ['name' => 'Brother', 'website' => 'https://www.brother.com'],
            ['name' => 'Hikvision', 'website' => 'https://www.hikvision.com'],
        ];
        foreach ($manufacturers as $m) {
            Manufacturer::firstOrCreate(['name' => $m['name']], $m);
        }

        // ─── Locations ──────────────────────────────────
        $hq = Location::firstOrCreate(['name' => 'Head Office'], ['type' => 'location']);
        $building1 = Location::firstOrCreate(['name' => 'Building A', 'parent_id' => $hq->id], ['type' => 'building', 'parent_id' => $hq->id]);
        Location::firstOrCreate(['name' => 'Floor 1', 'parent_id' => $building1->id], ['type' => 'floor', 'parent_id' => $building1->id]);
        Location::firstOrCreate(['name' => 'Floor 2', 'parent_id' => $building1->id], ['type' => 'floor', 'parent_id' => $building1->id]);
        Location::firstOrCreate(['name' => 'Server Room', 'parent_id' => $building1->id], ['type' => 'room', 'parent_id' => $building1->id]);
        $building2 = Location::firstOrCreate(['name' => 'Building B', 'parent_id' => $hq->id], ['type' => 'building', 'parent_id' => $hq->id]);
        Location::firstOrCreate(['name' => 'Data Center', 'parent_id' => $building2->id], ['type' => 'room', 'parent_id' => $building2->id]);
        $branch = Location::firstOrCreate(['name' => 'Branch Office'], ['type' => 'location']);
        Location::firstOrCreate(['name' => 'IT Room', 'parent_id' => $branch->id], ['type' => 'room', 'parent_id' => $branch->id]);

        // ─── Software & Licenses ────────────────────────
        $softwareList = [
            ['software_name' => 'Microsoft 365', 'vendor' => 'Microsoft', 'version' => 'E3', 'category' => 'Productivity'],
            ['software_name' => 'Adobe Creative Cloud', 'vendor' => 'Adobe', 'version' => '2025', 'category' => 'Design'],
            ['software_name' => 'Slack', 'vendor' => 'Salesforce', 'version' => 'Business+', 'category' => 'Communication'],
            ['software_name' => 'Kaspersky Endpoint Security', 'vendor' => 'Kaspersky', 'version' => '12', 'category' => 'Security'],
            ['software_name' => 'VMware vSphere', 'vendor' => 'VMware', 'version' => '8', 'category' => 'Virtualization'],
        ];

        foreach ($softwareList as $sw) {
            $software = Software::firstOrCreate(['software_name' => $sw['software_name']], $sw);

            if ($software->licenses()->count() === 0) {
                License::create([
                    'software_id' => $software->id,
                    'license_key' => strtoupper(bin2hex(random_bytes(12))),
                    'seats' => rand(10, 100),
                    'expiration_date' => now()->addMonths(rand(6, 24)),
                ]);
            }
        }

        // ─── Update Categories with Icons ───────────────
        $categoryIcons = [
            'Laptop' => 'bi-laptop',
            'Desktop' => 'bi-pc-display',
            'Server' => 'bi-hdd-rack',
            'Network Device' => 'bi-router',
            'Printer' => 'bi-printer',
            'CCTV' => 'bi-camera-video',
            'Access Control' => 'bi-shield-lock',
            'Software License' => 'bi-key',
        ];

        foreach ($categoryIcons as $name => $icon) {
            \App\Models\Category::where('name', $name)->where('type', 'asset')->update(['icon' => $icon]);
        }
    }
}
