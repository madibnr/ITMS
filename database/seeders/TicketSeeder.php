<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $ticketCategories = Category::where('type', 'ticket')->pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        $itStaff = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->pluck('id')->toArray();

        $tickets = [
            ['title' => 'Laptop tidak bisa booting', 'description' => 'Laptop menampilkan blue screen saat startup.', 'priority' => 'High', 'status' => 'Open'],
            ['title' => 'Tidak bisa akses email Outlook', 'description' => 'Outlook error saat login.', 'priority' => 'Medium', 'status' => 'In Progress'],
            ['title' => 'Request install Adobe Acrobat', 'description' => 'Butuh Adobe Acrobat Pro DC.', 'priority' => 'Low', 'status' => 'Open'],
            ['title' => 'Printer Lt.2 paper jam', 'description' => 'Printer HP sering paper jam.', 'priority' => 'Medium', 'status' => 'Resolved'],
            ['title' => 'VPN tidak bisa connect', 'description' => 'VPN GlobalProtect gagal connect.', 'priority' => 'High', 'status' => 'In Progress'],
            ['title' => 'Monitor berkedip-kedip', 'description' => 'Monitor Dell berkedip tidak stabil.', 'priority' => 'Low', 'status' => 'Closed'],
            ['title' => 'Network down lantai 5', 'description' => 'Koneksi network lantai 5 terputus.', 'priority' => 'Critical', 'status' => 'Open'],
            ['title' => 'Request akses shared folder', 'description' => 'Butuh akses folder Finance.', 'priority' => 'Low', 'status' => 'Resolved'],
            ['title' => 'Aplikasi ERP lambat', 'description' => 'ERP lambat saat generate report.', 'priority' => 'High', 'status' => 'In Progress'],
            ['title' => 'Password expired', 'description' => 'Akun AD expired butuh reset.', 'priority' => 'Medium', 'status' => 'Resolved'],
        ];

        foreach ($tickets as $data) {
            $slaHours = match ($data['priority']) {
                    'Critical' => 4,
                    'High' => 8,
                    'Medium' => 24,
                    'Low' => 48,
                };

            $ticket = Ticket::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'],
                'status' => $data['status'],
                'ticket_number' => Ticket::generateTicketNumber(),
                'category_id' => $ticketCategories[array_rand($ticketCategories)],
                'user_id' => $users[array_rand($users)],
                'assigned_to' => $data['status'] !== 'Open' ? $itStaff[array_rand($itStaff)] : null,
                'sla_deadline' => now()->addHours($slaHours),
                'resolved_at' => in_array($data['status'], ['Resolved', 'Closed']) ? now()->subHours(rand(1, 10)) : null,
            ]);

            if (in_array($data['status'], ['In Progress', 'Resolved', 'Closed'])) {
                $ticket->comments()->create([
                    'user_id' => $itStaff[array_rand($itStaff)],
                    'body' => 'Sedang dicek dan ditindaklanjuti.',
                    'is_internal' => (bool)rand(0, 1),
                ]);
            }
        }
    }
}
