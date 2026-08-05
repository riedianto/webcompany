-- ============================================================
-- WEBSITE COMPANY PROFILE RUMAH SAKIT
-- Schema + Seed Data (MySQL / MariaDB)
-- ============================================================

CREATE DATABASE IF NOT EXISTS webcompany CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webcompany;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL,
  role ENUM('admin','superadmin') DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS hero_slides;
CREATE TABLE hero_slides (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle TEXT,
  image VARCHAR(255) DEFAULT NULL,
  btn_text VARCHAR(100) DEFAULT NULL,
  btn_link VARCHAR(255) DEFAULT NULL,
  sort INT DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS about;
CREATE TABLE about (
  id INT AUTO_INCREMENT PRIMARY KEY,
  section_key VARCHAR(50) NOT NULL UNIQUE,
  title VARCHAR(255) DEFAULT NULL,
  content LONGTEXT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS facilities;
CREATE TABLE facilities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  icon VARCHAR(100) DEFAULT NULL,
  description TEXT,
  image VARCHAR(255) DEFAULT NULL,
  sort INT DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS services;
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  icon VARCHAR(100) DEFAULT NULL,
  description TEXT,
  image VARCHAR(255) DEFAULT NULL,
  sort INT DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS doctors;
CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  specialist VARCHAR(150) DEFAULT NULL,
  service_id INT DEFAULT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  schedule VARCHAR(255) DEFAULT NULL,
  email VARCHAR(150) DEFAULT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  bio TEXT,
  sort INT DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS doctor_schedules;
CREATE TABLE doctor_schedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT NOT NULL,
  day TINYINT NOT NULL COMMENT '0=Senin ... 6=Minggu',
  start_time TIME DEFAULT NULL,
  end_time TIME DEFAULT NULL,
  UNIQUE KEY uq_doctor_day (doctor_id, day),
  CONSTRAINT fk_schedule_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS gallery;
CREATE TABLE gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  sort INT DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS facility_images;
CREATE TABLE facility_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  facility_id INT NOT NULL,
  image VARCHAR(255) NOT NULL,
  sort INT DEFAULT 0,
  CONSTRAINT fk_facimg_facility FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS posts;
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT DEFAULT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  image VARCHAR(255) DEFAULT NULL,
  excerpt TEXT,
  content LONGTEXT,
  author VARCHAR(100) DEFAULT NULL,
  published_at DATETIME DEFAULT NULL,
  status ENUM('draft','published') DEFAULT 'published',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_posts_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS careers;
CREATE TABLE careers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  position VARCHAR(150) NOT NULL,
  department VARCHAR(100) DEFAULT NULL,
  requirements TEXT,
  description TEXT,
  status ENUM('open','closed') DEFAULT 'open',
  deadline DATE DEFAULT NULL,
  apply_link VARCHAR(500) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS partners;
CREATE TABLE partners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  logo VARCHAR(255) DEFAULT NULL,
  website VARCHAR(255) DEFAULT NULL,
  sort INT DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  subject VARCHAR(255) DEFAULT NULL,
  message TEXT NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin user (username: admin / password: admin123)
INSERT INTO users (username, password_hash, name, role) VALUES
('admin', '$2y$10$73GSiphUORmopBYk2RHQP.sd.2FH.EsXLxGifAL8PwkbFp0tm8reK', 'Administrator', 'superadmin');

-- Site settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'RSU Artha Medica'),
('site_tagline', 'Melayani dengan Sepenuh Hati untuk Kesehatan Anda'),
('site_description', 'Rumah Sakit Artha Medica adalah rumah sakit modern yang berkomitmen memberikan pelayanan kesehatan terbaik dengan teknologi terkini dan tenaga medis profesional.'),
('site_address', 'Jl. Kesehatan Raya No. 12, Jakarta Selatan 12240'),
('site_phone', '(021) 555-0123'),
('site_whatsapp', '6281234567890'),
('site_email', 'info@rsuarthamedica.co.id'),
('site_hours', 'Senin - Jumat: 08.00 - 21.00 WIB, Sabtu - Minggu: 09.00 - 17.00 WIB'),
('site_instagram', 'https://instagram.com/rsuarthamedica'),
('site_facebook', 'https://facebook.com/rsuarthamedica'),
('site_youtube', 'https://youtube.com/@rsuarthamedica'),
('site_twitter', ''),
('site_logo', ''),
('site_favicon', ''),
('site_primary_color', '#0a7d8c'),
('site_secondary_color', '#f4a261'),
('site_header_color', ''),
('site_footer_color', ''),
('site_announcement', 'Pendaftaran online kini tersedia 24 jam melalui website resmi kami.'),
('site_maps', 'https://maps.google.com/maps?q=jakarta%20selatan&t=&z=13&ie=UTF8&iwloc=&output=embed'),
('stat_years', '25'),
('stat_patients', '120000'),
('stat_doctors', '48'),
('stat_departments', '32'),
('home_about_image', ''),
('banner_default_image', ''),
('banner_about', ''),
('banner_services', ''),
('banner_doctors', ''),
('banner_news', ''),
('banner_careers', ''),
('banner_partners', ''),
('banner_contact', ''),
('reply_email_template', 'Halo {nama},

Terima kasih telah menghubungi {site}. Pesan Anda dengan subjek "{subjek}" sudah kami terima dan akan segera kami tindak lanjuti.

Hormat kami,
Tim {site}
{email} | {telp}'),
('reply_wa_template', 'Halo {nama}, terima kasih sudah menghubungi {site}. Pesan Anda tentang "{subjek}" telah kami terima dan akan segera kami tindak lanjuti.'),
('home_about_title', 'Selamat Datang di RSU Artha Medica'),
('home_about_text', 'Sejak tahun 2000, RSU Artha Medica telah melayani masyarakat dengan pelayanan kesehatan yang ramah, profesional, dan terjangkau. Kami terus berinovasi dengan teknologi medis terbaru untuk memberikan perawatan terbaik bagi pasien.');

-- Hero slides
INSERT INTO hero_slides (title, subtitle, image, btn_text, btn_link, sort, active) VALUES
('Kesehatan Anda Prioritas Kami', 'Pelayanan kesehatan modern dengan tenaga medis profesional dan berpengalaman.', '', 'Lihat Layanan', 'services.php', 1, 1),
('Pelayanan IGD 24 Jam', 'Tim medis siap siaga setiap saat untuk menangani keadaan darurat.', '', 'Jadwalkan Konsultasi', 'https://daftar.rsuarthamedica.co.id', 2, 1),
('Teknologi Medis Terkini', 'Fasilitas diagnostik dan perawatan dengan teknologi mutakhir.', '', 'Tentang Kami', 'about.php', 3, 1);

-- About sections
INSERT INTO about (section_key, title, content) VALUES
('profil', 'Profil Rumah Sakit', 'RSU Artha Medica adalah rumah sakit swasta yang berdiri sejak tahun 2000 di Jakarta. Kami memiliki visi menjadi rumah sakit terpercaya yang memberikan pelayanan kesehatan berkualitas dunia dengan harga terjangkau. Dengan dukungan lebih dari 48 dokter spesialis dan 200 tenaga kesehatan, kami melayani lebih dari 120.000 pasien setiap tahunnya.'),
('visi', 'Visi', 'Menjadi rumah sakit pilihan utama masyarakat yang unggul dalam pelayanan kesehatan yang aman, bermutu, dan berkelanjutan.'),
('misi', 'Misi', '1. Memberikan pelayanan kesehatan yang prima dan terjangkau bagi seluruh lapisan masyarakat.\n2. Mengembangkan kompetensi tenaga medis dan non-medis secara berkelanjutan.\n3. Menerapkan teknologi medis terkini dalam pelayanan dan diagnosa.\n4. Membangun kemitraan dengan berbagai pihak untuk peningkatan derajat kesehatan masyarakat.'),
('sejarah', 'Sejarah', 'RSU Artha Medica didirikan pada tahun 2000 dengan 40 tempat tidur dan 6 dokter. Seiring berjalannya waktu, kami terus berkembang menjadi rumah sakit dengan lebih dari 250 tempat tidur, 32 departemen pelayanan, dan fasilitas modern. Pada tahun 2020, kami meluncurkan layanan telemedicine untuk memudahkan konsultasi pasien dari rumah.');

-- Facilities
INSERT INTO facilities (title, icon, description, image, sort, active) VALUES
('Laboratorium', 'bi bi-droplet-fill', 'Laboratorium diagnostik lengkap dengan hasil cepat dan akurat untuk berbagai pemeriksaan.', '', 1, 1),
('Radiologi', 'bi bi-x-ray', 'Layanan rontgen, MRI, CT-Scan, dan USG dengan teknologi pencitraan terkini.', '', 2, 1),
('IGD 24 Jam', 'bi bi-hospital', 'Instalasi Gawat Darurat yang siap siaga 24 jam dengan tim medis berpengalaman.', '', 3, 1),
('ICU / HCU', 'bi bi-heart-pulse-fill', 'Ruang perawatan intensif dengan monitor canggih dan pengawasan ketat.', '', 4, 1),
('Farmasi', 'bi bi-capsule', 'Apotek rumah sakit yang menyediakan obat-obatan lengkap dan terjamin.', '', 5, 1),
('Kamar Operasi', 'bi bi-signal', 'Ruang operasi modern dengan standar kebersihan dan keamanan tinggi.', '', 6, 1),
('Ambulans', 'bi bi-truck', 'Layanan ambulans 24 jam dengan peralatan darurat lengkap.', '', 7, 1),
('Parkir Luas', 'bi bi-p-square-fill', 'Area parkir yang luas dan aman untuk kenyamanan pengunjung.', '', 8, 1);

-- Services
INSERT INTO services (title, icon, description, image, sort, active) VALUES
('Poli Umum', 'bi bi-person-heart', 'Pelayanan kesehatan umum untuk semua usia dengan dokter umum yang ramah.', '', 1, 1),
('Poli Anak', 'bi bi-balloon-heart', 'Pelayanan kesehatan anak lengkap dengan suasana yang nyaman dan ramah anak.', '', 2, 1),
('Poli Kandungan', 'bi bi-flower1', 'Pelayanan kesehatan ibu dan anak, termasuk persalinan dan perawatan prenatal.', '', 3, 1),
('Poli Jantung', 'bi bi-heart-pulse', 'Diagnosa dan perawatan penyakit jantung dengan kardiolog terpercaya.', '', 4, 1),
('Poli Gigi', 'bi bi-stars', 'Perawatan gigi dan mulut lengkap dengan alat modern.', '', 5, 1),
('Poli Saraf', 'bi bi-brain', 'Penanganan gangguan saraf dengan neurolog dan peralatan diagnostik lengkap.', '', 6, 1),
('Poli Mata', 'bi bi-eye-fill', 'Pemeriksaan dan perawatan kesehatan mata untuk semua usia.', '', 7, 1),
('Telemedicine', 'bi bi-laptop', 'Konsultasi dokter secara online dari rumah kapan saja dan di mana saja.', '', 8, 1);

-- Doctors
INSERT INTO doctors (name, specialist, service_id, photo, schedule, email, phone, bio, sort, active) VALUES
('dr. Ahmad Fauzi, Sp.PD', 'Penyakit Dalam', 1, '', 'Senin - Jumat, 08.00 - 14.00', 'ahmad.fauzi@rsuarthamedica.co.id', '081234567001', 'Spesialis penyakit dalam dengan pengalaman lebih dari 15 tahun, lulusan Fakultas Kedokteran Universitas Indonesia.', 1, 1),
('dr. Siti Rahma, Sp.A', 'Anak', 2, '', 'Senin - Sabtu, 09.00 - 15.00', 'siti.rahma@rsuarthamedica.co.id', '081234567002', 'Spesialis anak yang ramah dan sabar, berpengalaman menangani berbagai penyakit pada bayi dan anak.', 2, 1),
('dr. Budi Santoso, Sp.OG', 'Obstetri & Ginekologi', 3, '', 'Selasa - Sabtu, 10.00 - 16.00', 'budi.santoso@rsuarthamedica.co.id', '081234567003', 'Spesialis kandungan dan kebidanan, menangani persalinan normal maupun caesar dengan aman.', 3, 1),
('dr. Maya Lestari, Sp.JP', 'Jantung & Pembuluh Darah', 4, '', 'Senin - Kamis, 08.00 - 13.00', 'maya.lestari@rsuarthamedica.co.id', '081234567004', 'Kardiolog dengan keahlian dalam diagnosa dan penanganan penyakit jantung koroner.', 4, 1),
('dr. Rizky Pratama, Sp.S', 'Saraf', 6, '', 'Rabu - Jumat, 09.00 - 14.00', 'rizky.pratama@rsuarthamedica.co.id', '081234567005', 'Neurolog yang berfokus pada penanganan stroke, migrain, dan gangguan saraf lainnya.', 5, 1),
('drg. Dewi Anggraini', 'Kedokteran Gigi', 5, '', 'Senin - Sabtu, 09.00 - 17.00', 'dewi.anggraini@rsuarthamedica.co.id', '081234567006', 'Dokter gigi dengan layanan perawatan gigi estetik, pembersihan karang gigi, dan kawat gigi.', 6, 1);

-- Doctor schedules (per-day): day 0=Senin, 1=Selasa, 2=Rabu, 3=Kamis, 4=Jumat, 5=Sabtu, 6=Minggu
INSERT INTO doctor_schedules (doctor_id, day, start_time, end_time) VALUES
(1, 0, '08:00:00', '14:00:00'), (1, 1, '08:00:00', '14:00:00'), (1, 2, '08:00:00', '14:00:00'), (1, 3, '08:00:00', '14:00:00'), (1, 4, '08:00:00', '14:00:00'),
(2, 0, '09:00:00', '15:00:00'), (2, 1, '09:00:00', '15:00:00'), (2, 2, '09:00:00', '15:00:00'), (2, 3, '09:00:00', '15:00:00'), (2, 4, '09:00:00', '15:00:00'), (2, 5, '09:00:00', '13:00:00'),
(3, 1, '10:00:00', '16:00:00'), (3, 2, '10:00:00', '16:00:00'), (3, 3, '10:00:00', '16:00:00'), (3, 4, '10:00:00', '16:00:00'), (3, 5, '09:00:00', '14:00:00'),
(4, 0, '08:00:00', '13:00:00'), (4, 1, '08:00:00', '13:00:00'), (4, 2, '13:00:00', '17:00:00'), (4, 3, '08:00:00', '13:00:00'),
(5, 2, '09:00:00', '14:00:00'), (5, 3, '09:00:00', '14:00:00'), (5, 4, '09:00:00', '14:00:00'),
(6, 0, '09:00:00', '17:00:00'), (6, 1, '09:00:00', '17:00:00'), (6, 2, '09:00:00', '17:00:00'), (6, 3, '09:00:00', '17:00:00'), (6, 4, '09:00:00', '17:00:00'), (6, 5, '09:00:00', '15:00:00');

-- Categories
INSERT INTO categories (name, slug) VALUES
('Artikel Kesehatan', 'artikel-kesehatan'),
('Berita Rumah Sakit', 'berita-rumah-sakit'),
('Tips & Edukasi', 'tips-edukasi');

-- Posts
INSERT INTO posts (category_id, title, slug, image, excerpt, content, author, published_at, status) VALUES
(2, 'RSU Artha Medica Resmikan Layanan Telemedicine Terbaru', 'rs-resmikan-layanan-telemedicine-terbaru', '', 'Layanan telemedicine terbaru kami memudahkan pasien berkonsultasi dengan dokter dari mana saja.', '<p>RSU Artha Medica dengan bangga mengumumkan peluncuran layanan telemedicine terbaru yang memungkinkan pasien melakukan konsultasi dokter secara daring.</p><p>Layanan ini hadir untuk menjawab kebutuhan masyarakat akan akses layanan kesehatan yang lebih mudah dan fleksibel.</p><p>Pasien dapat memilih jadwal konsultasi, melakukan pembayaran secara digital, dan menerima resep elektronik tanpa harus datang ke rumah sakit.</p>', 'Tim Media RS', DATE_SUB(NOW(), INTERVAL 2 DAY), 'published'),
(1, 'Pentingnya Pemeriksaan Kesehatan Rutin (Medical Check Up)', 'pentingnya-pemeriksaan-kesehatan-rutin', '', 'Pemeriksaan kesehatan rutin membantu mendeteksi penyakit sejak dini. Simak manfaatnya di sini.', '<p>Pemeriksaan kesehatan rutin atau medical check up sangat penting untuk mendeteksi penyakit sejak dini sebelum menjadi lebih parah.</p><p>Banyak penyakit seperti hipertensi, diabetes, dan kolesterol tinggi tidak menunjukkan gejala pada tahap awal.</p><p>Dengan pemeriksaan rutin, Anda dan dokter dapat mengambil langkah pencegahan yang tepat.</p>', 'dr. Ahmad Fauzi', DATE_SUB(NOW(), INTERVAL 5 DAY), 'published'),
(3, '5 Tips Menjaga Daya Tahan Tubuh di Musim Hujan', '5-tips-menjaga-daya-tahan-tubuh-di-musim-hujan', '', 'Musim hujan meningkatkan risiko penyakit. Berikut tips sederhana untuk menjaga tubuh tetap sehat.', '<p>Musim hujan sering kali diiringi meningkatnya kasus demam berdarah, flu, dan penyakit kulit.</p><p>Berikut 5 tips menjaga daya tahan tubuh: 1) Konsumsi makanan bergizi, 2) Cukup tidur 7-8 jam, 3) Rutin berolahraga, 4) Jaga kebersihan lingkungan, 5) Minum air putih yang cukup.</p><p>Jangan lupa untuk segera berkonsultasi jika muncul gejala penyakit.</p>', 'dr. Siti Rahma', DATE_SUB(NOW(), INTERVAL 9 DAY), 'published'),
(1, 'Mengenal Gejala dan Penanganan Stroke', 'mengenal-gejala-dan-penanganan-stroke', '', 'Stroke adalah kondisi darurat medis. Kenali gejalanya untuk penanganan yang cepat.', '<p>Stroke terjadi ketika suplai darah ke otak terganggu. Penanganan cepat sangat menentukan tingkat keselamatan pasien.</p><p>Ingat metode CEPAT: C (senyum tidak simetris), E (gerak anggota tubuh melemah), P (bicara pelo), A (amatir sesak), T (tembak ke rumah sakit terdekat).</p>', 'dr. Rizky Pratama', DATE_SUB(NOW(), INTERVAL 14 DAY), 'published'),
(3, 'Pola Makan Sehat untuk Jantung yang Kuat', 'pola-makan-sehat-untuk-jantung-yang-kuat', '', 'Menjaga kesehatan jantung dimulai dari pola makan. Yuk terapkan pola makan sehat berikut.', '<p>Kesehatan jantung sangat dipengaruhi oleh pola makan sehari-hari. Kurangi makanan tinggi garam dan lemak jenuh.</p><p>Perbanyak konsumsi sayur, buah, ikan, dan kacang-kacangan. Batasi makanan olahan dan minuman manis.</p><p>Kombinasikan dengan olahraga teratur minimal 30 menit per hari.</p>', 'dr. Maya Lestari', DATE_SUB(NOW(), INTERVAL 20 DAY), 'published');

-- Careers
INSERT INTO careers (position, department, requirements, description, status, deadline, apply_link) VALUES
('Perawat / Bidan', 'Rawat Inap', '1. D3/S1 Keperawatan dengan STR aktif.\n2. Pengalaman minimal 1 tahun (diutamakan).\n3. Teliti, sabar, dan berorientasi pada pelayanan.', 'Bertanggung jawab memberikan asuhan keperawatan kepada pasien serta membantu dokter dalam proses perawatan dan pemulihan pasien.', 'open', DATE_ADD(CURDATE(), INTERVAL 45 DAY), NULL),
('Dokter Umum', 'Poliklinik', '1. Dokter umum dengan SIP aktif.\n2. Mampu bekerja dalam tim.\n3. Komunikatif dan ramah.', 'Melayani pasien di poli umum, melakukan pemeriksaan, diagnosa, serta memberikan terapi dan edukasi kesehatan.', 'open', DATE_ADD(CURDATE(), INTERVAL 30 DAY), NULL),
('Petugas IT / Admin', 'IT & Sistem Informasi', '1. D3/S1 Teknik Informatika atau sejenisnya.\n2. Menguasai jaringan dan perangkat lunak.\n3. Siap bekerja sesuai shift.', 'Mengelola sistem informasi rumah sakit, jaringan, serta membantu pengembangan layanan digital.', 'open', DATE_ADD(CURDATE(), INTERVAL 60 DAY), NULL),
('Kasir', 'Keuangan', '1. SMA/SMK sederajat.\n2. Jujur, teliti, dan mampu menghitung cepat.\n3. Pengalaman di bidang pelayanan diutamakan.', 'Melayani pembayaran pasien, mengelola kas, dan membuat laporan keuangan harian.', 'closed', DATE_SUB(CURDATE(), INTERVAL 5 DAY), NULL);

-- Partners
INSERT INTO partners (name, logo, website, sort, active) VALUES
('BPJS Kesehatan', '', 'https://bpjs-kesehatan.go.id', 1, 1),
('Kementerian Kesehatan RI', '', 'https://kemkes.go.id', 2, 1),
('Universitas Indonesia', '', 'https://ui.ac.id', 3, 1),
('RS Harapan Sehat', '', 'https://www.google.com', 4, 1),
('Asuransi Jiwasraya', '', 'https://www.google.com', 5, 1),
('Pharmacy Mitra', '', 'https://www.google.com', 6, 1);
