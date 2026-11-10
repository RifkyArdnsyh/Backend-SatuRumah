# SATU RUMAH - Backend & Portal Verifikator Disperwaskim Kota Tasikmalaya

Aplikasi backend RESTful API dan portal verifikasi perumahan serta PSU (Prasarana, Sarana, dan Utilitas Umum) untuk Dinas Perumahan Rakyat dan Kawasan Permukiman (Disperwaskim) Kota Tasikmalaya berbasis Laravel 12.

---

## Prasyarat Sistem

Pastikan perangkat Anda telah terpasang:
- **PHP** >= 8.2 (dilengkapi ekstensi: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer** >= 2.x
- **Web Browser** (Chrome / Edge / Firefox) atau API Client (Postman / Thunder Client)

---

## Panduan Menjalankan Proyek

Jika Anda baru pertama kali meng-clone atau membuka proyek ini, ikuti langkah-langkah berikut:

### 1. Salin Berkas Lingkungan (.env)
Salin template konfigurasi dari `.env.example`:
```powershell
Copy-Item .env.example .env
```
*(Atau gunakan perintah bash/cmd: `cp .env.example .env`)*

### 2. Instal Dependensi Composer
Unduh seluruh pustaka PHP yang dibutuhkan aplikasi:
```bash
composer install
```

### 3. Buat Kunci Aplikasi (App Key)
Generate enkripsi unik aplikasi Laravel:
```bash
php artisan key:generate
```

### 4. Siapkan Database SQLite & Jalankan Migrasi
Secara default, proyek ini menggunakan SQLite yang praktis tanpa konfigurasi database server tambahan:
```powershell
# Buat file database jika belum ada
New-Item -ItemType File database/database.sqlite -Force

# Jalankan skema migrasi tabel
php artisan migrate
```

### 5. Masukkan Data Awal (Seeding)
Isi database dengan akun pengguna, data pengajuan sampel, dokumen, dan riwayat notifikasi:
```bash
php artisan db:seed
```

### 6. Jalankan Server Pengembangan
Nyalakan local development server Laravel:
```bash
php artisan serve
```
Aplikasi web dapat diakses melalui browser di:  
**http://127.0.0.1:8000**  
Dan Base URL RESTful API:  
**http://127.0.0.1:8000/api/v1**

---

## Akun Login Default (Hasil Seeder)

Gunakan kredensial berikut untuk masuk ke portal web atau otentikasi via API:

| Role | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Verifikator Utama** | `admin@saturumah.go.id` | `password` | Administrator Disperwaskim |
| **Verifikator Teknis** | `verifikator@saturumah.go.id` | `password` | Verifikasi berkas & persyaratan teknis |
| **Surveyor / Perwaskim**| `rahmat@saturumah.go.id` | `password` | Pemeriksaan lapangan & pengisian monitoring |

---

## Struktur Fitur & Endpoint Utama

### A. Web Portal Admin (Session Auth)
- **Authentication**: `GET /login`, `POST /login`, `POST /logout`
- **Dashboard**: Ringkasan statistik, filter kecamatan, grafik status, dan export CSV (`/dashboard`, `/dashboard/export`)
- **Daftar Antrean Pengajuan**: Monitoring status berkas pengembang, pencarian, dan pagination (`/pengajuan`)
- **Detail Pengajuan**: Checklist berkas, verifikasi berkas, validasi manual (`/pengajuan/{id}`)
- **Penjadwalan Survey**: Form penugasan tim dan jadwal survey lapangan (`POST /pengajuan/{id}/survey`)
- **Monitoring Lapangan**: Pencatatan hasil survey, upload bukti temuan, dan toggle status final (`/pengajuan/{id}/monitoring/*`)
- **Notifikasi**: Pusat notifikasi aktivitas pengajuan (`/notifikasi`)

### B. RESTful API v1 (Laravel Sanctum & Role Guards)
- **Auth Token**:
  - `POST /api/v1/auth/login` (Menerbitkan Sanctum Bearer Token)
  - `GET  /api/v1/auth/me` & `POST /api/v1/auth/logout`
- **Permohonan (Pengajuan)**:
  - `GET  /api/v1/pengajuan` (Scoping otomatis untuk developer)
  - `POST /api/v1/pengajuan` (Terkunci role pengembang)
  - `GET  /api/v1/pengajuan/{id}` & `PUT /api/v1/pengajuan/{id}` (Dilindungi Policy IDOR)
  - `POST /api/v1/pengajuan/{id}/verifikasi-berkas` (Role: `verifikator`, `admin_disperwaskim`)
  - `POST /api/v1/pengajuan/{id}/approve-sk` (Role: `pimpinan`, `admin_disperwaskim`)
- **Manajemen Berkas & Dokumen**:
  - `POST /api/v1/pengajuan/{id}/dokumen` (Validasi Magic Bytes: PDF maks 10MB, DWG maks 50MB)
  - `GET  /api/v1/dokumen/{id}/download` (Stream berkas privat dengan otorisasi Policy)

---

## Perintah Berguna Lainnya

- **Menjalankan Pengujian Keamanan & Fitur (Test Suite)**:
  ```bash
  php artisan test
  ```
- **Reset Ulang Database & Seed Kembali**:
  ```bash
  php artisan migrate:fresh --seed
  ```
- **Membersihkan Cache Aplikasi**:
  ```bash
  php artisan optimize:clear
  ```
