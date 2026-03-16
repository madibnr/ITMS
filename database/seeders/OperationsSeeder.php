<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\ChangeRequest;
use App\Models\Incident;
use App\Models\MaintenanceSchedule;
use App\Models\RootCauseAnalysis;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class OperationsSeeder extends Seeder
{
    public function run(): void
    {
        $itStaff = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->pluck('id')->toArray();
        $managers = User::whereHas('roles', fn($q) => $q->where('slug', 'manager'))->pluck('id')->toArray();
        $assets = Asset::pluck('id')->toArray();
        $tickets = Ticket::pluck('id')->toArray();

        // ─── Change Requests ────────────────────────────────
        $changeRequests = [
            [
                'title' => 'Upgrade firewall rules untuk blokir IP suspicious',
                'description' => 'Update ACL pada firewall untuk meningkatkan keamanan.',
                'reason' => 'Banyak attempt brute force dari IP tertentu.',
                'impact' => 'Medium',
                'risk_level' => 'Low',
                'status' => 'Approved',
                'scheduled_date' => now()->addDays(3),
            ],
            [
                'title' => 'Migrasi database server ke SSD storage',
                'description' => 'Memindahkan database production ke SSD NVMe untuk performa lebih baik.',
                'reason' => 'Query lambat pada jam sibuk.',
                'impact' => 'High',
                'risk_level' => 'High',
                'status' => 'Submitted',
                'scheduled_date' => now()->addWeek(),
                'rollback_plan' => 'Restore dari backup HDD jika terjadi masalah.',
            ],
            [
                'title' => 'Update OS server production ke Ubuntu 24.04',
                'description' => 'Upgrade operating system di 3 server prod.',
                'reason' => 'End of life Ubuntu 22.04.',
                'impact' => 'High',
                'risk_level' => 'Medium',
                'status' => 'Draft',
                'scheduled_date' => now()->addWeeks(2),
            ],
            [
                'title' => 'Implementasi MFA untuk akses VPN',
                'description' => 'Menambahkan multi-factor authentication pada VPN.',
                'reason' => 'Kebutuhan compliance ISO 27001.',
                'impact' => 'Medium',
                'risk_level' => 'Low',
                'status' => 'Implemented',
                'scheduled_date' => now()->subDays(5),
                'implemented_at' => now()->subDays(3),
            ],
            [
                'title' => 'Penambahan VLAN baru untuk IoT devices',
                'description' => 'Membuat VLAN terpisah untuk perangkat IoT.',
                'reason' => 'Segmentasi jaringan untuk keamanan.',
                'impact' => 'Low',
                'risk_level' => 'Low',
                'status' => 'Approved',
                'scheduled_date' => now()->addDays(10),
            ],
        ];

        foreach ($changeRequests as $data) {
            $data['change_number'] = ChangeRequest::generateChangeNumber();
            $data['requested_by'] = $itStaff[array_rand($itStaff)];
            if (in_array($data['status'], ['Approved', 'Implemented'])) {
                $data['approved_by'] = !empty($managers) ? $managers[array_rand($managers)] : $itStaff[0];
            }
            ChangeRequest::create($data);
        }

        // ─── Maintenance Schedules ──────────────────────────
        $schedules = [
            [
                'title' => 'Backup server harian',
                'description' => 'Full backup database dan file server.',
                'maintenance_type' => 'Preventive',
                'frequency' => 'Daily',
                'scheduled_date' => now()->addDay(),
                'status' => 'Scheduled',
            ],
            [
                'title' => 'Pembersihan dust server room',
                'description' => 'Membersihkan debu pada rak server dan perangkat jaringan.',
                'maintenance_type' => 'Preventive',
                'frequency' => 'Monthly',
                'scheduled_date' => now()->addWeeks(2),
                'status' => 'Scheduled',
            ],
            [
                'title' => 'Penggantian UPS battery',
                'description' => 'Ganti baterai UPS yang sudah 3 tahun.',
                'maintenance_type' => 'Corrective',
                'frequency' => 'One-time',
                'scheduled_date' => now()->subDays(2),
                'completed_date' => now()->subDay(),
                'status' => 'Completed',
            ],
            [
                'title' => 'Patching security server',
                'description' => 'Update security patches pada semua server.',
                'maintenance_type' => 'Preventive',
                'frequency' => 'Monthly',
                'scheduled_date' => now()->addDays(5),
                'status' => 'Scheduled',
            ],
            [
                'title' => 'Emergency repair switch lantai 3',
                'description' => 'Switch mengalami port failure, perlu penggantian.',
                'maintenance_type' => 'Emergency',
                'frequency' => 'One-time',
                'scheduled_date' => now(),
                'status' => 'In Progress',
            ],
        ];

        foreach ($schedules as $data) {
            $data['asset_id'] = !empty($assets) ? $assets[array_rand($assets)] : null;
            $data['assigned_to'] = $itStaff[array_rand($itStaff)];
            MaintenanceSchedule::create($data);
        }

        // ─── Incidents ──────────────────────────────────────
        $incidents = [
            [
                'title' => 'Server production down selama 30 menit',
                'description' => 'Server web production tidak merespons request.',
                'severity' => 'Critical',
                'status' => 'Resolved',
                'impact_description' => 'Semua user tidak bisa mengakses aplikasi.',
                'resolution' => 'Restart service Apache dan clear cache.',
                'occurred_at' => now()->subDays(3),
                'resolved_at' => now()->subDays(3)->addMinutes(45),
            ],
            [
                'title' => 'Database connection pool exhausted',
                'description' => 'Aplikasi ERP gagal connect ke database.',
                'severity' => 'High',
                'status' => 'Investigating',
                'impact_description' => 'Tim Finance tidak bisa akses ERP.',
                'occurred_at' => now()->subHours(5),
            ],
            [
                'title' => 'Email spam massal dari internal',
                'description' => 'Satu akun email terkompromi dan mengirim spam.',
                'severity' => 'Medium',
                'status' => 'Resolved',
                'impact_description' => 'Beberapa client menerima email spam.',
                'resolution' => 'Reset password akun, enable MFA, blacklist IP.',
                'occurred_at' => now()->subWeek(),
                'resolved_at' => now()->subWeek()->addHours(2),
            ],
            [
                'title' => 'Wi-Fi lantai 5 tidak stabil',
                'description' => 'Access point mengalami intermittent disconnect.',
                'severity' => 'Low',
                'status' => 'Open',
                'impact_description' => 'User lantai 5 sesekali terputus.',
                'occurred_at' => now()->subDays(2),
            ],
            [
                'title' => 'Data loss pada file server backup',
                'description' => 'Backup terakhir tidak berjalan, data 2 hari hilang.',
                'severity' => 'Critical',
                'status' => 'Closed',
                'impact_description' => 'File shared 2 hari terakhir tidak ter-backup.',
                'resolution' => 'Recovery dari shadow copy dan fix backup script.',
                'occurred_at' => now()->subWeeks(2),
                'resolved_at' => now()->subWeeks(2)->addHours(6),
            ],
        ];

        foreach ($incidents as $data) {
            $data['incident_number'] = Incident::generateIncidentNumber();
            $data['reported_by'] = $itStaff[array_rand($itStaff)];
            $data['assigned_to'] = $data['status'] !== 'Open' ? $itStaff[array_rand($itStaff)] : null;
            $data['related_asset_id'] = !empty($assets) ? $assets[array_rand($assets)] : null;
            $data['related_ticket_id'] = !empty($tickets) ? $tickets[array_rand($tickets)] : null;

            $incident = Incident::create($data);

            // Create RCA for resolved/closed critical incidents
            if (in_array($data['status'], ['Resolved', 'Closed']) && $data['severity'] === 'Critical') {
                RootCauseAnalysis::create([
                    'incident_id' => $incident->id,
                    'title' => 'RCA: ' . $data['title'],
                    'root_cause' => 'Analisis menunjukkan masalah terjadi karena konfigurasi yang tidak optimal.',
                    'contributing_factors' => 'Monitoring tidak aktif, SOP tidak diikuti.',
                    'corrective_action' => 'Perbaikan konfigurasi dan restart service terkait.',
                    'preventive_action' => 'Setup monitoring alert dan review SOP.',
                    'analyzed_by' => $itStaff[array_rand($itStaff)],
                    'status' => 'Approved',
                ]);
            }
        }
    }
}
