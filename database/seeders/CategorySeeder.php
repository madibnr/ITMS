<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Ticket categories
        foreach ([
        ['name' => 'Hardware', 'type' => 'ticket', 'description' => 'Hardware issues (PC, laptop, peripherals)'],
        ['name' => 'Software', 'type' => 'ticket', 'description' => 'Software installation, bugs, licensing'],
        ['name' => 'Network', 'type' => 'ticket', 'description' => 'Network connectivity, VPN, Wi-Fi'],
        ['name' => 'Email', 'type' => 'ticket', 'description' => 'Email and communication issues'],
        ['name' => 'Access', 'type' => 'ticket', 'description' => 'Account access, password reset, permissions'],
        ['name' => 'Printer', 'type' => 'ticket', 'description' => 'Printer and scanner related issues'],
        ['name' => 'Other', 'type' => 'ticket', 'description' => 'General IT support requests'],
        ] as $cat) {
            Category::create($cat);
        }

        // Asset categories
        foreach ([
        ['name' => 'Laptop', 'type' => 'asset', 'description' => 'Laptops and notebooks'],
        ['name' => 'PC', 'type' => 'asset', 'description' => 'Desktop computers'],
        ['name' => 'Printer', 'type' => 'asset', 'description' => 'Printers and scanners'],
        ['name' => 'Switch', 'type' => 'asset', 'description' => 'Network switches'],
        ['name' => 'AP', 'type' => 'asset', 'description' => 'Wireless access points'],
        ['name' => 'Router', 'type' => 'asset', 'description' => 'Routers and firewalls'],
        ['name' => 'Server', 'type' => 'asset', 'description' => 'Server hardware'],
        ['name' => 'Monitor', 'type' => 'asset', 'description' => 'Monitors and displays'],
        ['name' => 'UPS', 'type' => 'asset', 'description' => 'Uninterruptible power supply'],
        ] as $cat) {
            Category::create($cat);
        }
    }
}
