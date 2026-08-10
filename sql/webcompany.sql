-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2026 at 06:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webcompany`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `section_key` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `section_key`, `title`, `title_en`, `content`, `content_en`) VALUES
(1, 'profil', 'Profil Rumah Sakit', NULL, 'RSU Artha Medica adalah rumah sakit swasta yang berdiri sejak tahun 2000 di Jakarta. Kami memiliki visi menjadi rumah sakit terpercaya yang memberikan pelayanan kesehatan berkualitas dunia dengan harga terjangkau. Dengan dukungan lebih dari 48 dokter spesialis dan 200 tenaga kesehatan, kami melayani lebih dari 120.000 pasien setiap tahunnya.', NULL),
(2, 'visi', 'Visi', NULL, 'Menjadi rumah sakit pilihan utama masyarakat yang unggul dalam pelayanan kesehatan yang aman, bermutu, dan berkelanjutan.', NULL),
(3, 'misi', 'Misi', NULL, '1. Memberikan pelayanan kesehatan yang prima dan terjangkau bagi seluruh lapisan masyarakat.\n2. Mengembangkan kompetensi tenaga medis dan non-medis secara berkelanjutan.\n3. Menerapkan teknologi medis terkini dalam pelayanan dan diagnosa.\n4. Membangun kemitraan dengan berbagai pihak untuk peningkatan derajat kesehatan masyarakat.', NULL),
(4, 'sejarah', 'Sejarah', NULL, 'RSU Artha Medica didirikan pada tahun 2000 dengan 40 tempat tidur dan 6 dokter. Seiring berjalannya waktu, kami terus berkembang menjadi rumah sakit dengan lebih dari 250 tempat tidur, 32 departemen pelayanan, dan fasilitas modern. Pada tahun 2020, kami meluncurkan layanan telemedicine untuk memudahkan konsultasi pasien dari rumah.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `careers`
--

CREATE TABLE `careers` (
  `id` int(11) NOT NULL,
  `position` varchar(150) NOT NULL,
  `position_en` varchar(150) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `department_en` varchar(100) DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `requirements_en` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `deadline` date DEFAULT NULL,
  `apply_link` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `name_en`, `slug`) VALUES
(1, 'Artikel Kesehatan', NULL, 'artikel-kesehatan'),
(2, 'Berita Rumah Sakit', NULL, 'berita-rumah-sakit'),
(3, 'Tips & Edukasi', NULL, 'tips-edukasi');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `specialist` varchar(150) DEFAULT NULL,
  `specialist_en` varchar(150) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `bio_en` text DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialist`, `specialist_en`, `service_id`, `photo`, `schedule`, `email`, `phone`, `bio`, `bio_en`, `sort`, `active`, `created_at`) VALUES
(9, 'dr. ARUSTA TARIGAN, Sp.OG', 'Dokter Spesialis Obstetri dan Ginekologi (Obgyn)', NULL, 3, NULL, NULL, '', '', 'Dokter Spesialis Obstetri dan Ginekologi memberikan pelayanan kesehatan bagi perempuan dalam berbagai tahap kehidupan, mulai dari kesehatan reproduksi, kehamilan, persalinan, masa nifas, hingga kesehatan organ reproduksi. Pelayanan meliputi pemeriksaan kehamilan, pemantauan tumbuh kembang janin, penanganan gangguan menstruasi, masalah kesuburan, serta berbagai kondisi kesehatan kandungan lainnya.', NULL, 1, 1, '2026-08-07 10:04:05'),
(10, 'dr. T. JOHAN AVISENNA, Sp.OG', 'Dokter Spesialis Obstetri dan Ginekologi (Obgyn)', NULL, 3, NULL, NULL, '', '', 'Dokter Spesialis Obstetri dan Ginekologi memberikan pelayanan kesehatan bagi perempuan dalam berbagai tahap kehidupan, mulai dari kesehatan reproduksi, kehamilan, persalinan, masa nifas, hingga kesehatan organ reproduksi. Pelayanan meliputi pemeriksaan kehamilan, pemantauan tumbuh kembang janin, penanganan gangguan menstruasi, masalah kesuburan, serta berbagai kondisi kesehatan kandungan lainnya.', NULL, 2, 1, '2026-08-07 10:05:52'),
(11, 'dr. INDRA TARIGAN, Sp.OG', 'Dokter Spesialis Obstetri dan Ginekologi (Obgyn)', NULL, 3, NULL, NULL, '', '', 'Dokter Spesialis Obstetri dan Ginekologi memberikan pelayanan kesehatan bagi perempuan dalam berbagai tahap kehidupan, mulai dari kesehatan reproduksi, kehamilan, persalinan, masa nifas, hingga kesehatan organ reproduksi. Pelayanan meliputi pemeriksaan kehamilan, pemantauan tumbuh kembang janin, penanganan gangguan menstruasi, masalah kesuburan, serta berbagai kondisi kesehatan kandungan lainnya.', NULL, 3, 1, '2026-08-08 04:52:49'),
(12, 'dr. ATIKHA APRILIA, Sp.OG', 'Dokter Spesialis Obstetri dan Ginekologi (Obgyn)', NULL, 3, NULL, NULL, '', '', 'Dokter Spesialis Obstetri dan Ginekologi memberikan pelayanan kesehatan bagi perempuan dalam berbagai tahap kehidupan, mulai dari kesehatan reproduksi, kehamilan, persalinan, masa nifas, hingga kesehatan organ reproduksi. Pelayanan meliputi pemeriksaan kehamilan, pemantauan tumbuh kembang janin, penanganan gangguan menstruasi, masalah kesuburan, serta berbagai kondisi kesehatan kandungan lainnya.', NULL, 4, 1, '2026-08-08 04:55:32'),
(13, 'dr. ROSIHAN SIPAYUNG, Sp.PD', 'Dokter Spesialis Penyakit Dalam', NULL, 10, NULL, NULL, '', '', 'Dokter Spesialis Penyakit Dalam memberikan pelayanan untuk diagnosis, pencegahan, dan penanganan berbagai penyakit yang terjadi pada organ tubuh bagian dalam, termasuk gangguan metabolik, saluran pencernaan, ginjal, hati, serta penyakit kronis seperti diabetes dan hipertensi. Pelayanan diberikan secara menyeluruh dengan mempertimbangkan kondisi dan kebutuhan masing-masing pasien.', NULL, 5, 1, '2026-08-08 05:05:13'),
(14, 'dr. HENRY SATYA PUTRA SIHITE, Sp.PD', 'Dokter Spesialis Penyakit Dalam', NULL, 10, NULL, NULL, '', '', 'Dokter Spesialis Penyakit Dalam memberikan pelayanan untuk diagnosis, pencegahan, dan penanganan berbagai penyakit yang terjadi pada organ tubuh bagian dalam, termasuk gangguan metabolik, saluran pencernaan, ginjal, hati, serta penyakit kronis seperti diabetes dan hipertensi. Pelayanan diberikan secara menyeluruh dengan mempertimbangkan kondisi dan kebutuhan masing-masing pasien.', NULL, 6, 1, '2026-08-08 05:07:48'),
(15, 'dr. ANNISA DWI ANDRIANI, Sp.PD', 'Dokter Spesialis Penyakit Dalam', NULL, 10, NULL, NULL, '', '', 'Dokter Spesialis Penyakit Dalam memberikan pelayanan untuk diagnosis, pencegahan, dan penanganan berbagai penyakit yang terjadi pada organ tubuh bagian dalam, termasuk gangguan metabolik, saluran pencernaan, ginjal, hati, serta penyakit kronis seperti diabetes dan hipertensi. Pelayanan diberikan secara menyeluruh dengan mempertimbangkan kondisi dan kebutuhan masing-masing pasien.', NULL, 7, 1, '2026-08-08 05:09:55'),
(16, 'dr. SURYA M.H HARAHAP, Sp.B', 'Dokter Spesialis Bedah', NULL, 11, NULL, NULL, '', '', 'Dokter Spesialis Bedah memberikan pelayanan dalam penanganan berbagai kondisi yang memerlukan tindakan pembedahan maupun evaluasi sebelum dan sesudah operasi. Pelayanan mencakup pemeriksaan, diagnosis, konsultasi tindakan operasi, perawatan luka, serta pemantauan kondisi pasien selama masa pemulihan.', NULL, 8, 1, '2026-08-08 05:11:34'),
(17, 'dr. JOS ARNO M. SILITONGA, Sp.B', 'Dokter Spesialis Bedah', NULL, 11, NULL, NULL, '', '', 'Dokter Spesialis Bedah memberikan pelayanan dalam penanganan berbagai kondisi yang memerlukan tindakan pembedahan maupun evaluasi sebelum dan sesudah operasi. Pelayanan mencakup pemeriksaan, diagnosis, konsultasi tindakan operasi, perawatan luka, serta pemantauan kondisi pasien selama masa pemulihan.', NULL, 9, 1, '2026-08-08 05:13:12'),
(18, 'dr. MARLINA JUMRAKH, Sp.A', 'Dokter Spesialis Anak', NULL, 2, NULL, NULL, '', '', 'Dokter Spesialis Anak memberikan pelayanan kesehatan menyeluruh bagi bayi, anak, dan remaja, mulai dari pemantauan pertumbuhan dan perkembangan, imunisasi, pemeriksaan kesehatan rutin, hingga diagnosis dan penanganan berbagai penyakit pada anak. Pelayanan juga mencakup edukasi kepada orang tua untuk mendukung kesehatan dan tumbuh kembang anak secara optimal.', NULL, 10, 1, '2026-08-08 05:15:01'),
(19, 'dr. FADHILAH ELVINA Sp.A', 'Dokter Spesialis Anak', NULL, 2, NULL, NULL, '', '', 'Dokter Spesialis Anak memberikan pelayanan kesehatan menyeluruh bagi bayi, anak, dan remaja, mulai dari pemantauan pertumbuhan dan perkembangan, imunisasi, pemeriksaan kesehatan rutin, hingga diagnosis dan penanganan berbagai penyakit pada anak. Pelayanan juga mencakup edukasi kepada orang tua untuk mendukung kesehatan dan tumbuh kembang anak secara optimal.', NULL, 11, 1, '2026-08-08 05:18:02'),
(20, 'dr. INDRA MUSTAWA, Sp.A', 'Dokter Spesialis Anak', NULL, 2, NULL, NULL, '', '', 'Dokter Spesialis Anak memberikan pelayanan kesehatan menyeluruh bagi bayi, anak, dan remaja, mulai dari pemantauan pertumbuhan dan perkembangan, imunisasi, pemeriksaan kesehatan rutin, hingga diagnosis dan penanganan berbagai penyakit pada anak. Pelayanan juga mencakup edukasi kepada orang tua untuk mendukung kesehatan dan tumbuh kembang anak secara optimal.', NULL, 12, 1, '2026-08-08 05:19:44'),
(21, 'dr. JULIA EVALINA GINTING, Sp.N', 'Dokter Spesialis Saraf', NULL, 6, NULL, NULL, '', '', 'Dokter Spesialis Saraf memberikan pelayanan untuk diagnosis dan penanganan berbagai gangguan pada sistem saraf, termasuk otak, saraf tepi, dan sistem saraf lainnya. Pelayanan meliputi penanganan keluhan seperti sakit kepala, pusing, gangguan keseimbangan, kesemutan, kejang, stroke, serta berbagai gangguan neurologis lainnya.', NULL, 13, 1, '2026-08-08 05:22:01'),
(22, 'dr. KHANSA SALSABILA, Sp.N', 'Dokter Spesialis Saraf', NULL, 6, NULL, NULL, '', '', 'Dokter Spesialis Saraf memberikan pelayanan untuk diagnosis dan penanganan berbagai gangguan pada sistem saraf, termasuk otak, saraf tepi, dan sistem saraf lainnya. Pelayanan meliputi penanganan keluhan seperti sakit kepala, pusing, gangguan keseimbangan, kesemutan, kejang, stroke, serta berbagai gangguan neurologis lainnya.', NULL, 14, 1, '2026-08-08 05:23:26'),
(23, 'dr. ERIKA MADONNA, Sp.THT-KL', 'Dokter Spesialis Telinga, HIdung, Tenggorokan, Kepala dan Leher', NULL, 12, NULL, NULL, '', '', 'Dokter Spesialis THT-KL memberikan pelayanan untuk menangani berbagai gangguan pada telinga, hidung, tenggorokan, serta area kepala dan leher. Pelayanan meliputi pemeriksaan dan penanganan gangguan pendengaran, infeksi telinga, sinusitis, gangguan hidung, tenggorokan, suara, hingga berbagai kondisi lain pada area THT-KL.', NULL, 15, 1, '2026-08-08 05:25:07'),
(24, 'dr. NURUL YUSFANI, Sp.THT', 'Dokter Spesialis Telinga, HIdung, Tenggorokan, Kepala dan Leher', NULL, 12, NULL, NULL, '', '', 'Dokter Spesialis THT-KL memberikan pelayanan untuk menangani berbagai gangguan pada telinga, hidung, tenggorokan, serta area kepala dan leher. Pelayanan meliputi pemeriksaan dan penanganan gangguan pendengaran, infeksi telinga, sinusitis, gangguan hidung, tenggorokan, suara, hingga berbagai kondisi lain pada area THT-KL.', NULL, 16, 1, '2026-08-08 05:28:19'),
(25, 'dr. DIAN PRASTUTY, Sp.P', 'Dokter Spesialis Paru', NULL, 7, NULL, NULL, '', '', 'Dokter Spesialis Paru memberikan pelayanan untuk diagnosis, pengobatan, dan pemantauan berbagai penyakit pada sistem pernapasan. Pelayanan mencakup penanganan asma, penyakit paru obstruktif kronis, infeksi saluran pernapasan, tuberkulosis, gangguan pernapasan, serta berbagai penyakit paru lainnya.', NULL, 17, 1, '2026-08-08 05:30:08'),
(26, 'dr. ELLA RHINSILVA, Sp.P', 'Dokter Spesialis Paru', NULL, 7, NULL, NULL, '', '', 'Dokter Spesialis Paru memberikan pelayanan untuk diagnosis, pengobatan, dan pemantauan berbagai penyakit pada sistem pernapasan. Pelayanan mencakup penanganan asma, penyakit paru obstruktif kronis, infeksi saluran pernapasan, tuberkulosis, gangguan pernapasan, serta berbagai penyakit paru lainnya.', NULL, 18, 1, '2026-08-08 05:31:15'),
(27, 'dr. ABDILLAH LUBIS, Sp.JP', 'Dokter Spesialis Jantung dan Pembuluh Darah', NULL, 4, NULL, NULL, '', '', 'Dokter Spesialis Jantung dan Pembuluh Darah memberikan pelayanan untuk pemeriksaan, diagnosis, pencegahan, dan penanganan berbagai penyakit jantung serta pembuluh darah. Pelayanan meliputi evaluasi tekanan darah, gangguan irama jantung, penyakit jantung koroner, gagal jantung, serta kondisi kardiovaskular lainnya.', NULL, 19, 1, '2026-08-08 05:33:10'),
(28, 'dr. YAN W CHRISTOPER S, Sp.An', 'Dokter Spesialis Anastesiologi', NULL, 13, NULL, NULL, '', '', 'Dokter Spesialis Anestesiologi memberikan pelayanan medis yang berkaitan dengan pembiusan dan pengelolaan kondisi pasien selama tindakan medis maupun pembedahan. Pelayanan meliputi evaluasi kondisi pasien sebelum tindakan, pemberian anestesi yang sesuai, pemantauan kondisi pasien selama prosedur, serta pengelolaan nyeri dan pemulihan setelah tindakan.', NULL, 20, 1, '2026-08-08 05:37:53'),
(29, 'dr. WINARDI S LESMAN, Sp.An', 'Dokter Spesialis Anastesiologi', NULL, 13, NULL, NULL, '', '', 'Dokter Spesialis Anestesiologi memberikan pelayanan medis yang berkaitan dengan pembiusan dan pengelolaan kondisi pasien selama tindakan medis maupun pembedahan. Pelayanan meliputi evaluasi kondisi pasien sebelum tindakan, pemberian anestesi yang sesuai, pemantauan kondisi pasien selama prosedur, serta pengelolaan nyeri dan pemulihan setelah tindakan.', NULL, 21, 1, '2026-08-08 05:41:14'),
(30, 'dr. BHENRY SISWANTO MANURUNG, Sp.An', 'Dokter Spesialis Anastesiologi', NULL, 13, NULL, NULL, '', '', 'Dokter Spesialis Anestesiologi memberikan pelayanan medis yang berkaitan dengan pembiusan dan pengelolaan kondisi pasien selama tindakan medis maupun pembedahan. Pelayanan meliputi evaluasi kondisi pasien sebelum tindakan, pemberian anestesi yang sesuai, pemantauan kondisi pasien selama prosedur, serta pengelolaan nyeri dan pemulihan setelah tindakan.', NULL, 22, 1, '2026-08-08 05:44:18'),
(31, 'dr. SOSOR TUAH INDRA, Sp.Rad', 'Dokter Spesialis Radiologi', NULL, 17, NULL, NULL, '', '', 'Dokter Spesialis Radiologi memberikan pelayanan penunjang diagnosis melalui berbagai pemeriksaan pencitraan medis. Pemeriksaan radiologi digunakan untuk membantu dokter dalam mengetahui kondisi organ dan jaringan tubuh serta mendukung penegakan diagnosis dan pemantauan perkembangan penyakit.', NULL, 23, 1, '2026-08-08 05:46:44'),
(32, 'dr. BUDI DARMANTA SEMBIRING, Sp.PK', 'Dokter Spesialis Patologi Klinik', NULL, 18, NULL, NULL, '', '', 'Dokter Spesialis Patologi Klinik memberikan pelayanan pemeriksaan laboratorium medis untuk membantu proses diagnosis, pemantauan penyakit, serta evaluasi kondisi kesehatan pasien. Pemeriksaan dapat mencakup berbagai parameter darah, urine, cairan tubuh, serta pemeriksaan laboratorium lainnya sesuai kebutuhan klinis pasien.', NULL, 24, 1, '2026-08-08 05:48:14'),
(33, 'dr. INDRI MAHRANI, Sp. PA', 'Dokter Spesialis Patologi Anatomi', NULL, 19, NULL, NULL, '', '', 'Dokter Spesialis Patologi Anatomi memberikan pelayanan pemeriksaan jaringan dan sel tubuh untuk membantu menentukan diagnosis suatu penyakit. Pemeriksaan dilakukan terhadap spesimen yang diperoleh melalui tindakan medis tertentu dan berperan penting dalam diagnosis berbagai kondisi, termasuk tumor dan kanker.', NULL, 25, 1, '2026-08-08 05:49:19'),
(34, 'dr. ARIFAI LUMBANGAOL, Sp.U', 'Dokter Spesialis Urologi', NULL, 14, NULL, NULL, '', '', 'Dokter Spesialis Urologi memberikan pelayanan untuk diagnosis dan penanganan berbagai penyakit pada sistem saluran kemih serta sistem reproduksi pria. Pelayanan meliputi gangguan ginjal, ureter, kandung kemih, saluran kemih, batu saluran kemih, gangguan prostat, serta berbagai masalah urologi lainnya.', NULL, 26, 1, '2026-08-08 05:50:49'),
(35, 'dr. DEASY HENDRIATI, Sp.K.J', 'Dokter Spesialis Kejiwaan (Psikiatri)', NULL, 15, NULL, NULL, '', '', 'Dokter Spesialis Kejiwaan memberikan pelayanan kesehatan yang berfokus pada diagnosis, penanganan, dan pemantauan berbagai kondisi kesehatan jiwa. Pelayanan mencakup konsultasi dan penanganan gangguan kecemasan, gangguan suasana perasaan, gangguan tidur, stres, serta berbagai kondisi psikologis dan kejiwaan lainnya secara menyeluruh.', NULL, 27, 1, '2026-08-08 05:52:23'),
(36, 'dr. IRINA DAMAYANTI Sp.DV', 'Dokter Spesialis Kulit dan Kelamin', NULL, 16, NULL, NULL, '', '', 'Dokter Spesialis Kulit dan Kelamin memberikan pelayanan untuk diagnosis, pengobatan, dan perawatan berbagai penyakit kulit, rambut, kuku, serta penyakit yang berkaitan dengan kesehatan organ reproduksi dan infeksi menular seksual. Pelayanan diberikan berdasarkan kondisi dan kebutuhan pasien dengan memperhatikan aspek kesehatan kulit dan fungsi tubuh secara menyeluruh.', NULL, 28, 1, '2026-08-08 05:53:52');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `day` tinyint(4) NOT NULL COMMENT '0=Senin ... 6=Minggu',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_schedules`
--

INSERT INTO `doctor_schedules` (`id`, `doctor_id`, `day`, `start_time`, `end_time`) VALUES
(35, 9, 0, '12:00:00', '16:00:00'),
(36, 9, 1, '12:00:00', '16:00:00'),
(37, 9, 2, '12:00:00', '16:00:00'),
(38, 9, 3, '13:00:00', '17:00:00'),
(39, 9, 4, '13:00:00', '17:00:00'),
(40, 10, 0, '16:00:00', '17:30:00'),
(41, 10, 1, '10:30:00', '12:30:00'),
(42, 10, 2, '16:00:00', '17:30:00'),
(43, 10, 3, '16:00:00', '19:30:00'),
(44, 10, 4, '10:30:00', '12:30:00'),
(45, 10, 5, '10:30:00', '12:30:00'),
(46, 11, 0, '07:30:00', '09:00:00'),
(47, 11, 2, '07:30:00', '09:00:00'),
(48, 11, 4, '07:30:00', '09:00:00'),
(55, 12, 0, '16:15:00', '19:00:00'),
(56, 12, 1, '16:15:00', '18:00:00'),
(57, 12, 2, '16:15:00', '18:00:00'),
(58, 12, 3, '10:00:00', '13:00:00'),
(59, 12, 4, '15:00:00', '18:00:00'),
(60, 12, 5, '15:00:00', '18:00:00'),
(61, 13, 0, '09:00:00', '12:30:00'),
(62, 13, 1, '09:00:00', '12:30:00'),
(63, 13, 2, '09:00:00', '12:30:00'),
(64, 13, 3, '09:00:00', '12:30:00'),
(65, 13, 4, '09:00:00', '12:30:00'),
(66, 14, 0, '14:30:00', '16:55:00'),
(67, 14, 2, '14:30:00', '16:55:00'),
(68, 14, 4, '14:30:00', '16:55:00'),
(69, 15, 0, '14:00:00', '19:00:00'),
(70, 15, 1, '14:00:00', '21:00:00'),
(71, 15, 3, '14:00:00', '21:00:00'),
(72, 15, 5, '14:00:00', '21:00:00'),
(73, 16, 0, '15:00:00', '17:00:00'),
(74, 16, 2, '15:00:00', '17:00:00'),
(75, 16, 3, '15:00:00', '17:00:00'),
(76, 17, 0, '11:00:00', '13:00:00'),
(77, 17, 1, '13:00:00', '15:00:00'),
(78, 17, 2, '11:00:00', '13:00:00'),
(79, 17, 3, '11:00:00', '13:00:00'),
(80, 17, 4, '11:00:00', '13:00:00'),
(81, 18, 0, '12:30:00', '16:00:00'),
(82, 18, 1, '08:00:00', '12:00:00'),
(83, 18, 2, '08:00:00', '12:00:00'),
(84, 18, 3, '08:00:00', '16:00:00'),
(85, 18, 4, '08:00:00', '12:00:00'),
(86, 18, 5, '08:00:00', '12:00:00'),
(87, 19, 0, '14:00:00', '15:00:00'),
(88, 19, 1, '14:00:00', '15:00:00'),
(89, 19, 2, '14:00:00', '15:00:00'),
(90, 19, 4, '14:00:00', '15:00:00'),
(95, 20, 0, '15:30:00', '16:45:00'),
(96, 20, 1, '15:30:00', '16:45:00'),
(97, 20, 2, '15:30:00', '16:45:00'),
(98, 20, 3, '15:30:00', '16:45:00'),
(99, 20, 4, '15:30:00', '16:45:00'),
(100, 21, 0, '10:00:00', '13:30:00'),
(101, 21, 1, '09:00:00', '13:30:00'),
(102, 21, 2, '09:00:00', '13:30:00'),
(103, 21, 4, '14:00:00', '18:30:00'),
(104, 22, 3, '13:00:00', '17:00:00'),
(105, 22, 5, '13:00:00', '17:00:00'),
(106, 23, 0, '15:00:00', '18:00:00'),
(107, 23, 1, '15:00:00', '18:00:00'),
(108, 23, 4, '15:00:00', '18:00:00'),
(109, 24, 2, '09:00:00', '12:00:00'),
(110, 24, 3, '13:00:00', '15:00:00'),
(111, 24, 5, '08:30:00', '10:30:00'),
(112, 25, 0, '10:05:00', '12:30:00'),
(113, 25, 2, '10:05:00', '12:30:00'),
(114, 25, 4, '10:05:00', '12:30:00'),
(115, 26, 1, '09:00:00', '12:00:00'),
(116, 26, 3, '09:30:00', '12:00:00'),
(117, 26, 5, '08:30:00', '11:00:00'),
(118, 27, 0, '14:30:00', '20:30:00'),
(119, 27, 1, '08:30:00', '13:55:00'),
(120, 27, 2, '14:00:00', '20:00:00'),
(121, 27, 3, '08:30:00', '13:55:00'),
(122, 27, 4, '13:30:00', '19:30:00'),
(123, 28, 0, '08:00:00', '17:00:00'),
(124, 28, 1, '08:00:00', '17:00:00'),
(125, 28, 2, '08:00:00', '17:00:00'),
(126, 28, 4, '08:00:00', '17:00:00'),
(127, 28, 5, '08:00:00', '17:00:00'),
(133, 29, 0, '08:00:00', '14:30:00'),
(134, 29, 2, '15:30:00', '17:30:00'),
(135, 29, 4, '15:30:00', '17:30:00'),
(136, 29, 5, '15:30:00', '17:30:00'),
(137, 30, 0, '19:00:00', '21:00:00'),
(138, 30, 1, '19:00:00', '21:00:00'),
(139, 30, 2, '19:00:00', '21:00:00'),
(140, 30, 3, '19:00:00', '21:00:00'),
(141, 30, 4, '19:00:00', '21:00:00'),
(142, 30, 5, '19:00:00', '21:00:00'),
(143, 30, 6, '19:00:00', '21:00:00'),
(144, 31, 0, '15:30:00', '17:30:00'),
(145, 31, 1, '15:30:00', '17:30:00'),
(146, 31, 2, '15:30:00', '17:30:00'),
(147, 31, 3, '15:30:00', '17:30:00'),
(148, 31, 4, '15:30:00', '17:30:00'),
(149, 31, 5, '15:30:00', '17:30:00'),
(150, 31, 6, '08:00:00', '17:00:00'),
(151, 32, 1, '15:00:00', '17:00:00'),
(152, 32, 3, '15:00:00', '17:00:00'),
(153, 32, 5, '15:00:00', '17:00:00'),
(154, 33, 1, '09:00:00', '11:00:00'),
(155, 34, 1, '14:30:00', '21:00:00'),
(156, 34, 4, '14:30:00', '21:00:00'),
(157, 34, 5, '13:00:00', '19:00:00'),
(158, 35, 0, '14:30:00', '18:30:00'),
(159, 35, 2, '14:30:00', '18:30:00'),
(160, 35, 3, '14:30:00', '18:30:00'),
(161, 36, 1, '15:00:00', '18:30:00'),
(162, 36, 3, '15:00:00', '18:30:00'),
(163, 36, 4, '16:30:00', '19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facility_images`
--

CREATE TABLE `facility_images` (
  `id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `btn_text` varchar(100) DEFAULT NULL,
  `btn_link` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `image`, `btn_text`, `btn_link`, `sort`, `active`, `created_at`) VALUES
(1, 'Kesehatan Anda Prioritas Kami', 'Pelayanan kesehatan modern dengan tenaga medis profesional dan berpengalaman.', '20260804075248_ca092f0b.png', '', '', 1, 1, '2026-08-03 09:03:58'),
(2, 'Pelayanan IGD 24 Jam', 'Tim medis siap siaga setiap saat untuk menangani keadaan darurat.', '20260804075238_7a39a864.png', '', '', 2, 1, '2026-08-03 09:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_en` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `excerpt_en` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `title_en`, `icon`, `description`, `description_en`, `image`, `sort`, `active`, `created_at`) VALUES
(2, 'Poli Anak', NULL, 'bi bi-balloon-heart', 'Dokter Spesialis Anak memberikan pelayanan kesehatan menyeluruh bagi bayi, anak, dan remaja, mulai dari pemantauan tumbuh kembang, imunisasi, hingga diagnosis dan penanganan berbagai penyakit anak. Dengan keahlian dalam konsultasi laktasi dan infant massage (pijat bayi), dokter membantu mendukung keberhasilan menyusui, mengatasi berbagai tantangan laktasi, serta memberikan edukasi pijat bayi yang aman untuk menunjang kenyamanan, relaksasi, dan tumbuh kembang optimal.', NULL, NULL, 4, 1, '2026-08-03 09:03:58'),
(3, 'Poli Obgyn', NULL, 'bi bi-flower1', 'Dokter Spesialis Obstetri dan Ginekologi (Obgyn) adalah dokter yang menangani kesehatan reproduksi wanita, kehamilan, persalinan, dan masa menopause. Layanannya meliputi pemeriksaan kehamilan, USG, program hamil, persalinan, konsultasi KB, serta diagnosis dan penanganan berbagai gangguan pada organ reproduksi wanita.', NULL, NULL, 1, 1, '2026-08-03 09:03:58'),
(4, 'Poli Jantung', NULL, 'bi bi-heart-pulse', 'Dokter Spesialis Jantung dan Pembuluh Darah memberikan pelayanan kesehatan komprehensif bagi pasien dengan berbagai gangguan pada jantung dan sistem pembuluh darah, mulai dari pemeriksaan, diagnosis, hingga penanganan dan pemantauan kondisi pasien. Dengan keahlian dalam menangani berbagai penyakit seperti hipertensi, penyakit jantung koroner, gangguan irama jantung, gagal jantung, serta kelainan jantung lainnya, dokter memberikan penanganan yang sesuai dengan kondisi dan kebutuhan pasien, disertai edukasi mengenai pencegahan dan pengelolaan penyakit jantung untuk mendukung kesehatan serta meningkatkan kualitas hidup pasien.', NULL, NULL, 8, 1, '2026-08-03 09:03:58'),
(6, 'Poli Saraf', NULL, 'bi bi-braces-asterisk', 'Dokter Spesialis Saraf memberikan pelayanan kesehatan komprehensif bagi pasien dengan berbagai gangguan pada sistem saraf, mulai dari pemeriksaan, diagnosis, hingga penanganan dan pemantauan kondisi pasien. Dengan keahlian dalam menangani berbagai penyakit saraf seperti sakit kepala, migrain, stroke, epilepsi, gangguan saraf tepi, serta gangguan gerak, dokter memberikan penanganan yang sesuai dengan kondisi dan kebutuhan pasien, disertai edukasi untuk mendukung pengelolaan penyakit dan meningkatkan kualitas hidup pasien', NULL, NULL, 5, 1, '2026-08-03 09:03:58'),
(7, 'Poli Paru', NULL, 'bi bi-funnel-fill', 'Dokter Spesialis Paru memberikan pelayanan kesehatan komprehensif bagi pasien dengan berbagai gangguan pada sistem pernapasan, mulai dari pemeriksaan, diagnosis, hingga penanganan dan pemantauan kondisi pasien. Dengan keahlian dalam menangani berbagai penyakit seperti asma, penyakit paru obstruktif kronis (PPOK), tuberkulosis (TBC), pneumonia, infeksi saluran pernapasan, serta gangguan paru lainnya, dokter memberikan penanganan yang sesuai dengan kondisi dan kebutuhan pasien, disertai edukasi mengenai pencegahan, pengobatan, dan pemeliharaan kesehatan paru untuk meningkatkan kualitas hidup pasien.', NULL, NULL, 7, 1, '2026-08-03 09:03:58'),
(10, 'Poli Penyakit dalam', NULL, '', 'Dokter Spesialis Penyakit Dalam memberikan pelayanan diagnosis, pengobatan, dan pencegahan berbagai penyakit pada orang dewasa, seperti diabetes, hipertensi, gangguan saluran cerna, penyakit ginjal, serta penyakit metabolik lainnya. Dokter dengan ahli dalam diagnosis, pengobatan, dan pencegahan penyakit-penyakit yang memengaruhi organ dalam. Ini mencakup jantung, paru-paru, ginjal, saluran pencernaan, hati, hingga sistem endokrin.', NULL, NULL, 2, 1, '2026-08-07 09:44:08'),
(11, 'Poli Bedah', NULL, 'bi bi-wrench-adjustable', 'Dokter Spesialis Bedah memberikan pelayanan kesehatan komprehensif bagi pasien dengan berbagai kondisi yang memerlukan penanganan bedah, mulai dari pemeriksaan dan diagnosis, konsultasi tindakan, hingga pemantauan setelah tindakan. Dengan keahlian dalam menangani berbagai kasus bedah, dokter memberikan penanganan yang tepat sesuai kondisi dan kebutuhan pasien, termasuk perawatan luka, evaluasi pascaoperasi, serta edukasi mengenai persiapan dan pemulihan setelah tindakan bedah.', NULL, NULL, 3, 1, '2026-08-07 09:47:03'),
(12, 'Poli THT', NULL, 'bi bi-earbuds', 'Dokter Spesialis THT memberikan pelayanan kesehatan komprehensif bagi pasien dengan berbagai gangguan pada telinga, hidung, tenggorokan, serta kepala dan leher, mulai dari pemeriksaan, diagnosis, hingga penanganan dan pemantauan kondisi pasien. Dengan keahlian dalam menangani berbagai keluhan seperti gangguan pendengaran, infeksi telinga, sinusitis, alergi hidung, gangguan tenggorokan, serta masalah suara dan keseimbangan, dokter memberikan penanganan yang sesuai dengan kondisi dan kebutuhan pasien disertai edukasi untuk mendukung kesehatan dan kualitas hidup pasien.', NULL, NULL, 6, 1, '2026-08-07 09:50:16'),
(13, 'Anastesi', NULL, 'bi bi-usb-symbol', 'Dokter Spesialis Anestesiologi dan Terapi Intensif memberikan pelayanan kesehatan bagi pasien yang memerlukan penilaian dan persiapan sebelum tindakan operasi, pemantauan selama prosedur, serta perawatan setelah tindakan. Dengan keahlian dalam pengelolaan anestesi, pengendalian nyeri, dan pemantauan kondisi pasien, dokter memastikan setiap tindakan dilakukan dengan aman dan sesuai dengan kondisi serta kebutuhan pasien, disertai edukasi mengenai persiapan anestesi dan pemulihan setelah tindakan.', NULL, NULL, 9, 1, '2026-08-07 09:53:27'),
(14, 'Poli Urologi', NULL, 'bi bi-currency-euro', 'Dokter Spesialis Urologi memberikan pelayanan kesehatan komprehensif bagi pasien dengan berbagai gangguan pada sistem saluran kemih dan sistem reproduksi pria, mulai dari pemeriksaan, diagnosis, hingga penanganan dan pemantauan kondisi pasien. Dengan keahlian dalam menangani berbagai penyakit seperti batu ginjal dan saluran kemih, infeksi saluran kemih, pembesaran prostat, gangguan kandung kemih, serta masalah urologi lainnya, dokter memberikan penanganan yang sesuai dengan kondisi dan kebutuhan pasien, disertai edukasi mengenai pencegahan dan perawatan untuk mendukung kesehatan serta meningkatkan kualitas hidup pasien.', NULL, NULL, 13, 1, '2026-08-07 09:54:48'),
(15, 'Poli Kejiwaan', NULL, 'bi bi-balloon-heart-fill', 'Dokter Spesialis Kedokteran Jiwa memberikan pelayanan kesehatan komprehensif bagi pasien dengan berbagai kondisi yang berkaitan dengan kesehatan jiwa dan emosional, mulai dari konsultasi, pemeriksaan, diagnosis, hingga penanganan dan pemantauan kondisi pasien. Dengan keahlian dalam menangani berbagai kondisi seperti gangguan kecemasan, depresi, gangguan tidur, gangguan suasana hati, serta berbagai masalah kesehatan jiwa lainnya, dokter memberikan penanganan yang sesuai dengan kondisi dan kebutuhan pasien, disertai edukasi dan pendampingan untuk membantu meningkatkan kesejahteraan serta kualitas hidup pasien.', NULL, NULL, 14, 1, '2026-08-07 09:55:36'),
(16, 'Poli Kulit dan Kelamin', NULL, 'bi bi-circle-half', 'Dokter Spesialis Dermatologi, Venereologi, dan Estetika memberikan pelayanan kesehatan komprehensif bagi pasien dengan berbagai masalah pada kulit, rambut, kuku, serta kesehatan genital, mulai dari pemeriksaan, diagnosis, hingga penanganan dan pemantauan kondisi pasien. Dengan keahlian dalam menangani berbagai penyakit seperti jerawat, dermatitis, alergi kulit, infeksi kulit, gangguan rambut dan kuku, serta penyakit menular seksual, dokter memberikan penanganan yang sesuai dengan kondisi dan kebutuhan pasien, disertai edukasi mengenai perawatan dan kesehatan kulit untuk mendukung kesehatan serta meningkatkan kualitas hidup pasien.', NULL, NULL, 15, 1, '2026-08-07 09:56:50'),
(17, 'Radiologi', NULL, 'bi bi-0-square-fill', 'Dokter Spesialis Radiologi memberikan pelayanan pemeriksaan penunjang diagnostik menggunakan berbagai teknologi pencitraan medis untuk membantu mendeteksi, mengevaluasi, dan memantau berbagai kondisi kesehatan pasien. Dengan keahlian dalam menginterpretasikan hasil pemeriksaan seperti radiografi (X-ray), ultrasonografi (USG), CT Scan, dan pemeriksaan pencitraan lainnya, dokter membantu memberikan informasi diagnostik yang akurat sebagai dasar bagi dokter dalam menentukan diagnosis, penanganan, dan tindak lanjut pasien sesuai dengan kebutuhan medis.', NULL, NULL, 10, 1, '2026-08-07 09:58:05'),
(18, 'Patologi Klinik', NULL, 'bi bi-amd', 'Dokter Spesialis Patologi Klinik memberikan pelayanan diagnostik melalui berbagai pemeriksaan laboratorium terhadap spesimen pasien, seperti darah, urine, dan cairan tubuh lainnya, untuk membantu mendeteksi, mendiagnosis, serta memantau berbagai kondisi kesehatan. Dengan keahlian dalam pemeriksaan hematologi, kimia klinik, imunologi, mikrobiologi, dan bidang laboratorium lainnya, dokter membantu memastikan hasil pemeriksaan yang akurat dan dapat menjadi dasar bagi dokter dalam menentukan diagnosis, pengobatan, serta pemantauan kondisi pasien.', NULL, NULL, 11, 1, '2026-08-07 09:58:52'),
(19, 'Patologi Anatomi', NULL, 'bi bi-5-circle', 'Dokter Spesialis Patologi Anatomi memberikan pelayanan diagnostik melalui pemeriksaan jaringan dan sel tubuh untuk membantu mendeteksi, mendiagnosis, serta menentukan karakteristik berbagai penyakit. Dengan keahlian dalam pemeriksaan histopatologi, sitologi, dan pemeriksaan jaringan lainnya, dokter membantu memberikan informasi diagnostik yang akurat, termasuk dalam mendeteksi dan menentukan jenis serta karakteristik tumor atau keganasan, sebagai dasar bagi dokter dalam menentukan penanganan dan tindak lanjut pasien.', NULL, NULL, 12, 1, '2026-08-07 09:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'site_name', 'RSU ARTHA MEDICA'),
(2, 'site_tagline', 'Hospital Of Choice'),
(3, 'site_description', 'RSU Artha Medica adalah rumah sakit modern yang berkomitmen memberikan pelayanan kesehatan terbaik dengan teknologi terkini dan tenaga medis profesional.'),
(4, 'site_address', 'Jl. Samanhudi No.22, Satria, Kec. Binjai Kota, Kota Binjai, Sumatera Utara 20741'),
(5, 'site_phone', '061-8827277'),
(6, 'site_whatsapp', '6281234567890'),
(7, 'site_email', 'officialrsuarthamedica@gmail.com'),
(8, 'site_hours', 'Setiap Hari 24 Jam'),
(9, 'site_instagram', ''),
(10, 'site_facebook', ''),
(11, 'site_youtube', ''),
(12, 'site_twitter', ''),
(13, 'site_logo', '20260803111613_07e59a3a.jpg'),
(14, 'site_favicon', '20260803111613_e9484788.jpg'),
(15, 'site_primary_color', '#004080'),
(16, 'site_secondary_color', '#00a8cc'),
(17, 'site_announcement', 'Pendaftaran online kini tersedia 24 jam melalui website resmi kami.'),
(18, 'site_maps', 'https://maps.google.com/maps?q=3.598879,98.478679&z=17&output=embed'),
(19, 'stat_years', '25'),
(20, 'stat_patients', '120000'),
(21, 'stat_doctors', '48'),
(22, 'stat_departments', '32'),
(23, 'home_about_image', '20260804063421_8e56fc65.jpg'),
(24, 'home_about_title', 'Selamat Datang di RSU Artha Medica'),
(25, 'home_about_text', 'Sejak tahun 2000, RSU Artha Medica telah melayani masyarakat dengan pelayanan kesehatan yang ramah, profesional, dan terjangkau. Kami terus berinovasi dengan teknologi medis terbaru untuk memberikan perawatan terbaik bagi pasien.'),
(92, 'contact_subjects', ''),
(218, 'register_url', 'https://192.168.189.60:2026/'),
(221, 'banner_default_image', '20260804081811_a509c2d3.jpg'),
(248, 'banner_about', ''),
(274, 'reply_email_template', 'Halo {nama},\r\n\r\nTerima kasih telah menghubungi {site}. Pesan Anda dengan subjek \"{subjek}\" sudah kami terima dan akan segera kami tindak lanjuti.\r\n\r\nHormat kami,\r\nTim {site}\r\n{email} | {telp}'),
(275, 'reply_wa_template', 'Halo {nama}, terima kasih sudah menghubungi {site}. Pesan Anda tentang \"{subjek}\" telah kami terima dan akan segera kami tindak lanjuti.'),
(330, 'site_header_color', '#ffffff'),
(331, 'site_footer_color', '#004080'),
(472, 'site_tagline_en', ''),
(473, 'site_description_en', ''),
(474, 'site_announcement_en', ''),
(475, 'site_hours_en', ''),
(476, 'site_address_en', ''),
(477, 'home_about_title_en', ''),
(478, 'home_about_text_en', ''),
(479, 'contact_subjects_en', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','superadmin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$73GSiphUORmopBYk2RHQP.sd.2FH.EsXLxGifAL8PwkbFp0tm8reK', 'Administrator', 'superadmin', '2026-08-03 09:03:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section_key` (`section_key`);

--
-- Indexes for table `careers`
--
ALTER TABLE `careers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_doctor_day` (`doctor_id`,`day`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facility_images`
--
ALTER TABLE `facility_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_facimg_facility` (`facility_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_posts_category` (`category_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `careers`
--
ALTER TABLE `careers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `facility_images`
--
ALTER TABLE `facility_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=480;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD CONSTRAINT `fk_schedule_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `facility_images`
--
ALTER TABLE `facility_images`
  ADD CONSTRAINT `fk_facimg_facility` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
