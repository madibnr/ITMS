<?php

namespace Database\Seeders;

use App\Models\GuideFaq;
use App\Models\GuideTip;
use Illuminate\Database\Seeder;

class GuideSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Solusi Mandiri (Tips) ─────────────────────────────
        $tips = [
            [
                'icon' => 'fas fa-wifi',
                'icon_bg' => '#dbeafe',
                'icon_color' => '#2563eb',
                'title' => 'Internet Bermasalah?',
                'body' => 'Restart router/modem, periksa kabel LAN, pastikan WiFi aktif. Jika masih bermasalah, buat tiket.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'icon' => 'fas fa-print',
                'icon_bg' => '#fef3c7',
                'icon_color' => '#d97706',
                'title' => 'Printer Error?',
                'body' => 'Restart printer, periksa kertas dan tinta, pastikan driver terinstall. Coba print test page.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'icon' => 'fas fa-laptop',
                'icon_bg' => '#fee2e2',
                'icon_color' => '#dc2626',
                'title' => 'PC/Laptop Lambat?',
                'body' => 'Restart komputer, tutup aplikasi yang tidak digunakan, cek penyimpanan disk hampir penuh.',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($tips as $tip) {
            GuideTip::firstOrCreate(['title' => $tip['title']], $tip);
        }

        // ─── FAQ ───────────────────────────────────────────────
        $faqs = [
            [
                'question' => 'Bagaimana cara membuat tiket baru?',
                'answer' => 'Klik tombol <strong>Buat Tiket</strong> di navbar atau halaman utama. Isi formulir dengan data diri dan detail kendala Anda. Setelah dikirim, Anda akan menerima nomor tiket.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa lama kendala akan ditangani?',
                'answer' => 'Tergantung prioritas: <strong>Critical</strong> = 4 jam, <strong>High</strong> = 8 jam, <strong>Medium</strong> = 24 jam, <strong>Low</strong> = 48 jam. SLA dihitung sejak tiket dibuat.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara melacak tiket?',
                'answer' => 'Kunjungi halaman <strong>Lacak Tiket</strong>, masukkan nomor tiket Anda untuk melihat status terbaru.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Apa yang harus dilakukan jika tiket belum ditangani?',
                'answer' => 'Jika SLA sudah terlewati dan tiket belum ditangani, Anda dapat menghubungi IT Support langsung di ext. 100 atau melalui WhatsApp. Sebutkan nomor tiket Anda.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah saya perlu membuat akun untuk melapor?',
                'answer' => '<strong>Tidak.</strong> Anda bisa melapor langsung melalui formulir publik ini tanpa perlu login atau membuat akun. Cukup masukkan data diri dan detail kendala.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            GuideFaq::firstOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
