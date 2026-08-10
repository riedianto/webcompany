-- ============================================================
-- MIGRATION: Terjemahan Konten (ID <-> EN)
-- Menambahkan kolom bahasa Inggris (_en) pada tabel konten.
-- Aman dijalankan ulang (MariaDB: ADD COLUMN IF NOT EXISTS).
-- ============================================================

USE webcompany;

-- Services
ALTER TABLE services ADD COLUMN IF NOT EXISTS title_en VARCHAR(255) DEFAULT NULL AFTER title;
ALTER TABLE services ADD COLUMN IF NOT EXISTS description_en TEXT DEFAULT NULL AFTER description;

-- Facilities
ALTER TABLE facilities ADD COLUMN IF NOT EXISTS title_en VARCHAR(255) DEFAULT NULL AFTER title;
ALTER TABLE facilities ADD COLUMN IF NOT EXISTS description_en TEXT DEFAULT NULL AFTER description;

-- Posts
ALTER TABLE posts ADD COLUMN IF NOT EXISTS title_en VARCHAR(255) DEFAULT NULL AFTER title;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS excerpt_en TEXT DEFAULT NULL AFTER excerpt;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS content_en LONGTEXT DEFAULT NULL AFTER content;

-- Categories
ALTER TABLE categories ADD COLUMN IF NOT EXISTS name_en VARCHAR(100) DEFAULT NULL AFTER name;

-- Doctors
ALTER TABLE doctors ADD COLUMN IF NOT EXISTS specialist_en VARCHAR(150) DEFAULT NULL AFTER specialist;
ALTER TABLE doctors ADD COLUMN IF NOT EXISTS bio_en TEXT DEFAULT NULL AFTER bio;

-- Careers
ALTER TABLE careers ADD COLUMN IF NOT EXISTS position_en VARCHAR(150) DEFAULT NULL AFTER position;
ALTER TABLE careers ADD COLUMN IF NOT EXISTS department_en VARCHAR(100) DEFAULT NULL AFTER department;
ALTER TABLE careers ADD COLUMN IF NOT EXISTS requirements_en TEXT DEFAULT NULL AFTER requirements;
ALTER TABLE careers ADD COLUMN IF NOT EXISTS description_en TEXT DEFAULT NULL AFTER description;

-- Gallery
ALTER TABLE gallery ADD COLUMN IF NOT EXISTS title_en VARCHAR(255) DEFAULT NULL AFTER title;

-- Partners
ALTER TABLE partners ADD COLUMN IF NOT EXISTS name_en VARCHAR(150) DEFAULT NULL AFTER name;

-- About sections
ALTER TABLE about ADD COLUMN IF NOT EXISTS title_en VARCHAR(255) DEFAULT NULL AFTER title;
ALTER TABLE about ADD COLUMN IF NOT EXISTS content_en LONGTEXT DEFAULT NULL AFTER content;

-- Settings: key terjemahan (kosong = fallback ke versi Indonesia)
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('site_tagline_en', ''),
('site_description_en', ''),
('site_announcement_en', ''),
('site_hours_en', ''),
('site_address_en', ''),
('home_about_title_en', ''),
('home_about_text_en', ''),
('contact_subjects_en', '');