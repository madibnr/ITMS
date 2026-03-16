<?php

namespace Database\Seeders;

use App\Models\DocumentationCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentationSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Network Documentation',
                'slug'        => 'network-documentation',
                'description' => 'Documentation for network devices, IP addresses, VLANs, and topology.',
                'icon'        => '🌐',
            ],
            [
                'name'        => 'SOP (Standard Operating Procedure)',
                'slug'        => 'sop-standard-operating-procedure',
                'description' => 'Standard operating procedures for IT team workflows and processes.',
                'icon'        => '📋',
            ],
            [
                'name'        => 'Server Rack Documentation',
                'slug'        => 'server-rack-documentation',
                'description' => 'Rack layout, U position mapping, and installed device tracking.',
                'icon'        => '🖥️',
            ],
            [
                'name'        => 'Account Documentation',
                'slug'        => 'account-documentation',
                'description' => 'System accounts documentation (Email, Windows, Bitlocker, etc.). No passwords stored.',
                'icon'        => '🔐',
            ],
            [
                'name'        => 'General IT Documentation',
                'slug'        => 'general-it-documentation',
                'description' => 'General IT documentation, guides, and reference material.',
                'icon'        => '📄',
            ],
        ];

        foreach ($categories as $category) {
            DocumentationCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Documentation categories seeded successfully.');
    }
}
