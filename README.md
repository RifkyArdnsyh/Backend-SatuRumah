# SATU RUMAH - Portal Verifikator Disperwaskim Kota Tasikmalaya

Aplikasi backend portal verifikasi perumahan dan PSU (Prasarana, Sarana, dan Utilitas Umum) untuk Dinas Perumahan Rakyat dan Kawasan Permukiman (Disperwaskim) Kota Tasikmalaya berbasis Laravel.

---

## Prasyarat Sistem

Pastikan perangkat Anda telah terpasang:
- **PHP** >= 8.2 (dilengkapi ekstensi: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer** >= 2.x
- **Web Browser** (Chrome / Edge / Firefox)

---

## Panduan Menjalankan Proyek 

Jika Anda baru pertama kali meng-clone atau membuka proyek ini, ikuti langkah-langkah berikut:

### 1. Salin Berkas Lingkungan (`.env`)
Salin template konfigurasi dari `.env.example`:
```powershell
Copy-Item .env.example .env
```
*(Atau gunakan perintah bash/cmd: `cp .env.example .env`)*

### 2. Instal Dependensi Composer
Unduh seluruh pustaka PHP yang dibutuhkan aplikasi:
```bashs
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
Isi database dengan akun admin verifikator, data pengajuan sampel, dokumen, dan riwayat notifikasi:
```bash
php artisan db:seed
```

### 6. Jalankan Server Pengembangan
Nyalakan local server Laravel:
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui browser di:  
**[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## kun Login Default (Hasil Seeder)

Gunakan kredensial berikut untuk masuk ke portal:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Verifikator Utama** | `admin@saturumah.go.id` | `password` |
| **Verifikator Teknis** | `verifikator@saturumah.go.id` | `password` |
| **Surveyor / Perwaskim** | `rahmat@saturumah.go.id` | `password` |

---

## truktur Fitur Utama

- **Authentication**: `GET/POST /login`, `POST /logout`
- **Dashboard**: Ringkasan statistik, filter kecamatan, grafik status, dan export data (`/dashboard`, `/dashboard/export`)
- **Daftar Pengajuan**: Monitoring status berkas pengembang, pencarian, dan pagination (`/pengajuan`)
- **Detail Pengajuan**: Checklist kelengkapan berkas, auto-verifikasi berkas, validasi manual (`/pengajuan/{id}`)
- **Penjadwalan Survey**: Form penugasan tim dan jadwal survey lapangan (`POST /pengajuan/{id}/survey`)
- **Monitoring Lapangan**: Pencatatan hasil survey, upload bukti temuan, dan toggle status final (`/pengajuan/{id}/monitoring/*`)
- **Notifikasi**: Pusat notifikasi aktivitas pengajuan (`/notifikasi`)

---

- **Reset Ulang Database dan Seed Kembali**:
  ```bash
  php artisan migrate:fresh --seed
  ```
- **Membersihkan Cache Aplikasi**:
  ```bash
  php artisan optimize:clear
  ```
