# Website Company Profile RSU Artha Medica

Website company profile rumah sakit dengan panel admin lengkap. Dibangun dengan **PHP 8.1+, MySQL/MariaDB, Bootstrap 5** (frontend) dan **AdminLTE 3** (admin), tanpa framework — cocok untuk dijalankan di XAMPP maupun VPS.

## Fitur Utama

- **Dark mode** — tombol matahari/bulan di navbar, tersimpan di `localStorage`, default mengikuti pengaturan perangkat (`prefers-color-scheme`), tanpa kedip saat reload.
- **Bilingual Indonesia / Inggris** — switch via bendera di kanan atas (topbar) dan menu mobile, tersimpan 30 hari. Semua teks UI diterjemahkan.
- **Tombol WhatsApp mengambang** — pojok kanan bawah, langsung membuka chat ke nomor WA rumah sakit dengan pesan awal sesuai bahasa.
- **Formulir kontak** — Nama + No. WhatsApp wajib, subjek berupa dropdown yang dikelola dari admin, pesan tersimpan ke database.
- **Google Maps** — peta lokasi dengan pratinjau live di admin.
- **Tombol Daftar Online** — link dikelola dari admin (navbar, beranda, halaman dokter).
- **Panel admin lengkap** — kelola banner, layanan, fasilitas, dokter, artikel, karir, rekanan, pesan masuk, dan pengguna.

---

## Kebutuhan (Requirements)

| Komponen | Versi |
|---|---|
| PHP | 8.1+ |
| MySQL / MariaDB | 5.7+ / 10.4+ |
| Web server | Apache (XAMPP) atau VPS |
| Ekstensi PHP | `pdo_mysql`, `gd`, `fileinfo`, `mbstring`, `session` |

---

## Instalasi Lokal (XAMPP)

1. **Letakkan folder proyek** di `C:\xampp\htdocs\rsuarthamedica` (nama folder bebas).
2. **Aktifkan layanan** Apache dan MySQL di XAMPP Control Panel.
3. **Buat database** lewat phpMyAdmin (`http://localhost/phpmyadmin`) lalu import `sql/database.sql`. File ini membuat database `webcompany` beserta seluruh tabel dan data awal.
   > Jika ingin nama database berbeda, buat database kosong dahulu dengan nama tersebut, import file di atas, lalu sesuaikan `includes/config.php`.
4. **Cek konfigurasi database** di `includes/config.php`:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'webcompany');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

5. **Akses website**: buka `http://localhost/rsuarthamedica`
6. **Akses admin**: buka `http://localhost/rsuarthamedica/admin/`

### Akun Admin Bawaan

| Username | Password |
|---|---|
| `admin` | `admin123` |

> ⚠️ **WAJIB diganti** setelah instalasi lewat menu **Pengguna Admin** (`/admin/users.php`).

---

## Panduan Panel Admin

Masuk lewat `/admin/`, lalu login dengan username & password. Berikut menu sidebar dan fungsinya:

| Menu | Fungsi |
|---|---|
| Dashboard | Ringkasan statistik & pesan terbaru |
| Pengaturan Website | Identitas situs, warna, kontak, sosial media, peta, daftar online, subjek kontak |
| Slide Banner | Kelola gambar hero / banner beranda |
| Tentang Kami | Konten halaman "Tentang" |
| Layanan | Daftar layanan rumah sakit |
| Fasilitas | Fasilitas yang ditampilkan |
| Dokter | Profil dokter + jadwal |
| Artikel & Berita | Berita / artikel |
| Kategori | Kategori artikel |
| Karir | Lowongan pekerjaan |
| Rekanan | Mitra / asuransi rekanan |
| Pesan Masuk | Pesan dari formulir kontak website |
| Pengguna Admin | Kelola akun admin |

### Pengaturan Website yang Penting

| Field | Catatan |
|---|---|
| **WhatsApp** | Format `628xxxxxxxxxx` (tanpa `+`). Dipakai tombol WhatsApp mengambang. |
| **Link Daftar Online** | URL tujuan tombol "Daftar Online". Kosongkan untuk memakai link bawaan. |
| **Embed Peta** | Harus berupa **URL embed** (bukan link share). Contoh: `https://maps.google.com/maps?q=<lat>,<lng>&z=15&output=embed`. Ada pratinjau peta langsung di bawah field — jika pratinjau kosong, berarti URL bukan format embed. |
| **Subjek Formulir Kontak** | Satu opsi per baris. Muncul sebagai dropdown di halaman Kontak. Kosongkan untuk memakai daftar bawaan. |

---

## Fitur & Cara Kerja

### Dark Mode
- Tombol ikon bulan/matahari di navbar kanan.
- Tema disimpan di `localStorage` (`theme`), dipakai ulang saat kunjungan berikutnya.
- Jika belum pernah memilih, mengikuti `prefers-color-scheme` sistem.
- Script anti-kedip (`flash of wrong theme`) ada di `<head>` (`includes/header.php`).
- Warna gelap didefinisikan di `assets/css/style.css` pada blok `[data-theme="dark"]`.

### Bahasa Indonesia / Inggris
- Switch bahasa lewat bendera 🇮🇩 / 🇬🇧:
  - **Desktop**: kanan atas topbar.
  - **Mobile**: di dalam menu hamburger.
- Perpindahan bahasa memakai parameter `?lang=id` / `?lang=en`, disimpan di session + cookie 30 hari.
- Seluruh teks UI berada di file kamus:
  - `lang/id.php`
  - `lang/en.php`
- **Menambah terjemahan baru**: tambahkan key yang sama di kedua file, misalnya:

  ```php
  // lang/id.php
  'home.example' => 'Contoh teks Indonesia',
  // lang/en.php
  'home.example' => 'Example English text',
  ```

  lalu gunakan di halaman: `<?= e(t('home.example')) ?>`.

> Catatan: konten database (judul berita, nama dokter, dll.) tetap tampil apa adanya di kedua bahasa. Hanya teks antarmuka yang diterjemahkan.

### Tombol WhatsApp Mengambang
- Dibuat dari setting `site_whatsapp`.
- Saat diklik membuka `https://wa.me/<nomor>?text=<pesan>`, dengan pesan awal mengikuti bahasa aktif.

### Formulir Kontak
- Kolom **Nama** dan **Nomor WhatsApp** wajib (WhatsApp minimal 8 digit).
- **Subjek** berupa dropdown — opsi dikelola di admin (Pengaturan Website → Subjek Formulir Kontak).
- Data tersimpan ke tabel `messages` dan tampil di admin → **Pesan Masuk**.

### Google Maps
- Setting `Embed Peta` harus berisi URL embed (lihat panduan di atas).
- Pratinjau live di admin memudahkan verifikasi sebelum disimpan.
- Jika peta kosong di website, periksa kembali format URL (link `maps.app.goo.gl` atau link share biasa **tidak bisa** dirender di iframe).

### Tombol Daftar Online
- Mengikuti setting `Link Daftar Online` di admin.
- Dipakai di: navbar, CTA beranda (`index.php`), dan tombol janji di halaman detail dokter (`doctor-detail.php`).

---

## Struktur Folder

```
rsuarthamedica/
├── index.php            Halaman beranda
├── about.php            Tentang
├── services.php         Layanan
├── doctors.php          Daftar dokter
├── doctor-detail.php    Detail dokter + jadwal
├── contact.php          Kontak + formulir
├── news.php             Berita / artikel
├── news-detail.php      Detail artikel
├── careers.php          Karir
├── partners.php         Rekanan
├── includes/
│   ├── config.php       Konfigurasi DB & pemrosesan bahasa
│   ├── db.php           Koneksi PDO (MySQL)
│   ├── functions.php    Helper: t(), lang_code(), register_url(), dll.
│   ├── header.php       Header + navbar (flags, dark toggle)
│   ├── footer.php       Footer + tombol WhatsApp
│   └── sections/        Bagian reusable (page banner, dll.)
├── lang/
│   ├── id.php           Kamus Indonesia
│   └── en.php           Kamus Inggris
├── admin/               Panel admin (AdminLTE)
├── assets/              CSS, JS, gambar, vendor (Bootstrap, dll.)
├── uploads/             File upload (logo, foto) — tidak masuk git
└── sql/
    └── database.sql     Skema & data awal database
```

---

## Deployment ke VPS + SSL (Ringkas)

1. Pindahkan seluruh isi folder ke `/var/www/rsuarthamedica`.
2. Buat database di server, import `sql/database.sql`, sesuaikan kredensial di `includes/config.php`.
3. Atur vhost Apache: `DocumentRoot` ke folder proyek, aktifkan `mod_rewrite` dan `AllowOverride All`.
4. Pasang SSL gratis: `sudo apt install certbot python3-certbot-apache` lalu `sudo certbot --apache -d domain.com -d www.domain.com`.
5. Setelah online: **ganti password admin**, matikan `display_errors`, dan cek folder `uploads/` bisa ditulis web server.
6. Backup rutin: `mysqldump` database + folder `uploads/`.

---

## Troubleshooting

| Masalah | Solusi |
|---|---|
| **Peta kosong** | URL harus format embed (`output=embed` / `maps/embed?pb=`), bukan link share. Cek pratinjau di admin. |
| **Koneksi database gagal** | Periksa `DB_HOST/DB_NAME/DB_USER/DB_PASS` di `includes/config.php`; pastikan MySQL aktif dan database sudah di-import. |
| **Lupa password admin** | Reset via PHP CLI: `php -r "require 'includes/config.php'; db_exec('UPDATE users SET password_hash=? WHERE username=?', [password_hash('passwordBaru', PASSWORD_DEFAULT), 'admin']);"` |
| **Tombol Daftar Online tidak tampil** | Setting `Link Daftar Online` kosong & tidak ada fallback → isi di admin. |
| **Gambar tidak tampil** | Pastikan folder `uploads/` ikut ter-upload dan bisa ditulis (mis. izin `www-data` pada server). |

---

## Keamanan

- Repo git ini **private**; jangan pernah men-commit `includes/config.php` yang berisi kredensial database production.
- Selalu ganti password admin bawaan (`admin123`).
- Di production, matikan `display_errors` (atur di `php.ini` atau `includes/config.php`).
- Folder `uploads/` masuk `.gitignore` karena berisi data runtime, bukan kode.

---

## Lisensi & Catatan

Proyek ini dibuat untuk kebutuhan company profile rumah sakit. Kode diperbolehkan dimodifikasi dan digunakan sesuai kebutuhan masing-masing.
