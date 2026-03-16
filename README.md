# 🖥️ ITMS — IT Management System

Sistem manajemen IT terpadu berbasis web untuk kebutuhan operasional tim IT. Dibangun menggunakan **Laravel 12** dengan antarmuka yang modern dan responsif.

---

## ✨ Fitur Utama

### 🎫 Helpdesk & Ticketing
- Buat, kelola, dan pantau tiket IT dari staff
- Sistem komentar/balasan pada tiket
- Status tiket: Open, In Progress, Resolved, Closed
- Prioritas tiket: Low, Medium, High, Critical
- Filter & pencarian tiket
- Histori tiket per pengguna
- Export laporan tiket ke Excel

### 🖥️ Asset Management
- Inventaris aset IT (Laptop, PC, Printer, dll.)
- Multi-status: Active, In Repair, In Stock, Retired, Lost, Broken
- Penugasan (assignment) aset ke pengguna
- QR Code per aset untuk identifikasi cepat (**on development**)
- Kalkulasi depresiasi aset otomatis (**on development**)
- Import / Export data aset via Excel
- Manajemen file & lampiran per aset (**on development**)
- Riwayat perubahan aset (audit log)
- **Konfigurasi format Asset Tag** (prefix, separator, jumlah digit, nomor urut)
- **Edit Asset Tag** langsung dari halaman inventaris via modal

### 🔧 Asset Maintenance (**on development**)
- Jadwal preventive maintenance aset
- Log perbaikan / maintenance korektif
- Status: Scheduled, In Progress, Completed, Overdue

### 📦 Software & Licenses (**Basic Feature**)
- Manajemen daftar software
- Manajemen lisensi software (jumlah lisensi, tanggal kedaluwarsa)
- Penugasan lisensi ke pengguna

### 📋 IT Documentation
- Sentralisasi dokumentasi IT (SOP, Network, Server Rack, Akun Sistem, Umum)
- Rich text editor (TinyMCE) untuk konten dokumen
- Kategori & tag dokumentasi
- Lampiran file per dokumen
- Status draft / published
- Metadata terstruktur per kategori dokumen
- Filter dan pencarian dokumentasi

### 🔀 Change Requests (**on development**)
- Pengajuan permintaan perubahan sistem IT
- Alur persetujuan (Submit → Approve/Reject → Implement)
- Export laporan perubahan ke Excel

### ⚠️ Incident Management (**on development**)
- Pencatatan insiden IT
- Root Cause Analysis (RCA)
- Export laporan insiden ke Excel

### 📊 Reporting & Analytics
- Laporan tiket (volume, SLA, distribusi)
- Laporan aset (status, kategori, lokasi)
- Laporan SLA (response time, resolution time)
- Export laporan ke Excel

### 👥 User Management
- Manajemen pengguna sistem
- Role berbasis akses: **Admin**, **Manager**, **IT Staff**
- Aktivasi / deaktivasi akun pengguna
- Export data pengguna ke Excel

### 📖 Guide & FAQ
- Panduan penggunaan sistem untuk pengguna
- Halaman FAQ
- Dapat dikelola oleh Admin via panel khusus

---

## 🛠️ Tech Stack & Requirements

| Komponen | Versi |
|---|---|
| **PHP** | ^8.2 |
| **Laravel** | ^12.0 |
| **Node.js** | ^18.x (LTS) atau lebih baru |
| **npm** | ^9.x atau lebih baru |
| **Composer** | ^2.x |
| **Database** | MySQL 8.0+ / MariaDB 10.4+ |
| **Web Server** | Apache / Nginx (atau Laragon / XAMPP untuk lokal) |

### Package Utama
- `maatwebsite/excel` ^3.1 — Import/Export Excel
- `simplesoftwareio/simple-qrcode` ^4.2 — Generate QR Code
- `laravel/breeze` ^2.3 — Autentikasi
- Vite + Alpine.js — Frontend build & interaktivitas

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/itms.git
cd itms
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itms
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database

Buat database baru di MySQL/MariaDB dengan nama `itms` (sesuaikan dengan `.env`):

```sql
CREATE DATABASE itms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan Migrasi & Seeder

```bash
php artisan migrate --seed
```

> Seeder akan membuat data awal: roles, user default, kategori aset, dan contoh data.

### 6. Install Node Dependencies & Build Assets

```bash
npm install
npm run build
```

### 7. Konfigurasi Storage

```bash
php artisan storage:link
```

### 8. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses: **http://localhost:8000**

---

## 🔑 Akun Default

Setelah seeder dijalankan, gunakan akun berikut untuk login:

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@itms.test | password |
| **Manager** | manager@itms.test | password |
| **IT Staff** | budi@itms.test | password |
| **IT Staff** | sari@itms.test | password |
| **IT Staff** | andi@itms.test | password |
| **Regular User** | ahmad@itms.test | password |

> ⚠️ **Penting:** Segera ganti password default setelah login pertama kali.

---

## 📁 Struktur Direktori Penting

```
itms/
├── app/
│   ├── Http/Controllers/       # Controller per modul
│   │   └── Documentation/      # Controller dokumentasi
│   ├── Models/                 # Eloquent models
│   ├── Policies/               # Authorization policies
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Skema database
│   └── seeders/                # Data awal
├── resources/
│   └── views/                  # Blade templates
│       ├── assets/
│       ├── documentation/
│       ├── reports/
│       └── tickets/
├── routes/
│   └── web.php                 # Definisi route
└── storage/
    └── app/
        └── asset_tag_format.json  # Konfigurasi format asset tag
```

---

## 🔐 Role & Hak Akses

| Fitur | Admin | Manager | IT Staff |
|---|:---:|:---:|:---:|
| Ticketing (baca/balas) | ✅ | ✅ | ✅ |
| Ticketing (kelola semua) | ✅ | ✅ | ✅ |
| Asset Management | ✅ | ✅ | ✅ |
| Format Asset Tag | ✅ | ✅ | ✅ |
| Change Requests | ✅ | ✅ | ✅ |
| Approve CR | ✅ | ✅ | ❌ |
| Incident Management | ✅ | ✅ | ✅ |
| Documentation (baca) | ✅ | ✅ | ✅ |
| Documentation (kelola) | ✅ | ✅ | ✅ |
| Doc Categories | ✅ | ❌ | ❌ |
| Doc Tags | ✅ | ✅ | ❌ |
| Reports | ✅ | ✅ | ❌ |
| User Management | ✅ | ❌ | ❌ |

---

## 🐛 Troubleshooting

**Error: `SQLSTATE table doesn't exist`**
```bash
php artisan migrate
```

**Error: Storage tidak bisa diakses**
```bash
php artisan storage:link
```

**Aset CSS/JS tidak muncul**
```bash
npm run build
```

**Cache lama menyebabkan error**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 📝 Lisensi

Proyek ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).
