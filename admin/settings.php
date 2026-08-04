<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Pengaturan Website';
$section = 'settings';
$breadcrumbs = [['label' => 'Pengaturan Website', 'url' => '']];

$logoSettings = ['site_logo' => 'logos', 'site_favicon' => 'logos', 'home_about_image' => 'general', 'banner_default_image' => 'general'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $textFields = [
        'site_name', 'site_tagline', 'site_description',
        'site_address', 'site_phone', 'site_whatsapp', 'site_email', 'site_hours',
        'site_maps', 'site_instagram', 'site_facebook', 'site_youtube', 'site_twitter',
        'site_announcement', 'home_about_title', 'home_about_text',
        'stat_years', 'stat_patients', 'stat_doctors', 'stat_departments',
        'contact_subjects', 'register_url',
    ];

    foreach ($textFields as $key) {
        save_setting($key, trim((string)($_POST[$key] ?? '')));
    }

    $primary = trim((string)($_POST['site_primary_color'] ?? ''));
    if (preg_match('/^#[0-9a-fA-F]{6}$/', $primary)) {
        save_setting('site_primary_color', $primary);
    }
    $secondary = trim((string)($_POST['site_secondary_color'] ?? ''));
    if (preg_match('/^#[0-9a-fA-F]{6}$/', $secondary)) {
        save_setting('site_secondary_color', $secondary);
    }

    foreach ($logoSettings as $key => $subdir) {
        if (!empty($_FILES[$key]) && (int)$_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = upload_image($key, $subdir, setting($key));
            if ($up['success']) {
                save_setting($key, (string)$up['file']);
            } else {
                flash('error', $up['error']);
            }
        }
    }

    flash('success', 'Pengaturan website berhasil disimpan.');
    redirect(base_url('admin/settings.php'));
}

include __DIR__ . '/layout/header.php';

$logoVal = setting('site_logo');
$faviconVal = setting('site_favicon');
$homeImg = setting('home_about_image');
$bannerVal = setting('banner_default_image');
?>

<div class="row">
  <div class="col-md-7">
    <form method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-building mr-2 text-primary"></i>Identitas &amp; Tampilan</h3></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Nama Rumah Sakit</label>
                <input type="text" name="site_name" class="form-control" value="<?= e(setting('site_name')) ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Warna Utama</label>
                <div class="input-group">
                  <input type="color" name="site_primary_color" class="form-control form-control-color" style="height:38px" value="<?= e(setting('site_primary_color', '#0a7d8c')) ?>">
                  <input type="text" class="form-control" value="<?= e(setting('site_primary_color', '#0a7d8c')) ?>" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Tagline</label>
            <input type="text" name="site_tagline" class="form-control" value="<?= e(setting('site_tagline')) ?>">
          </div>
          <div class="form-group">
            <label>Deskripsi Website</label>
            <textarea name="site_description" class="form-control" rows="3"><?= e(setting('site_description')) ?></textarea>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Warna Sekunder</label>
                <input type="color" name="site_secondary_color" class="form-control form-control-color" style="height:38px" value="<?= e(setting('site_secondary_color', '#f4a261')) ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Logo Rumah Sakit</label>
                <input type="file" name="site_logo" class="form-control-file" data-preview="#previewLogo" accept="image/*">
                <img id="previewLogo" class="img-preview mt-2 <?= $logoVal ? '' : 'd-none' ?>" src="<?= $logoVal ? e(img_url('logos', $logoVal)) : '' ?>" alt="Logo">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Favicon (ikon kecil)</label>
                <input type="file" name="site_favicon" class="form-control-file" data-preview="#previewFavicon" accept="image/*">
                <img id="previewFavicon" class="img-preview-sm mt-2 <?= $faviconVal ? '' : 'd-none' ?>" src="<?= $faviconVal ? e(img_url('logos', $faviconVal)) : '' ?>" alt="Favicon">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Gambar Banner Halaman</label>
            <input type="file" name="banner_default_image" class="form-control-file" data-preview="#previewBannerImg" accept="image/*">
            <img id="previewBannerImg" class="img-preview mt-2 <?= $bannerVal ? '' : 'd-none' ?>" src="<?= $bannerVal ? e(img_url('general', $bannerVal)) : '' ?>" alt="Banner Halaman">
            <small class="icon-helper">Gambar latar untuk banner di bagian atas semua halaman (selain beranda). Kosongkan untuk memakai gradient bawaan. Disarankan lebar 1600px.</small>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-phone-alt mr-2 text-primary"></i>Kontak &amp; Sosial Media</h3></div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="site_address" class="form-control" value="<?= e(setting('site_address')) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Telepon</label>
                <input type="text" name="site_phone" class="form-control" value="<?= e(setting('site_phone')) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>WhatsApp (format: 628xxxx)</label>
                <input type="text" name="site_whatsapp" class="form-control" value="<?= e(setting('site_whatsapp')) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Link Daftar Online</label>
                <input type="url" name="register_url" class="form-control" value="<?= e(setting('register_url')) ?>" placeholder="https://...">
                <small class="icon-helper">Tombol "Daftar Online" di navbar, beranda, dan halaman dokter. Kosongkan untuk memakai link bawaan.</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="site_email" class="form-control" value="<?= e(setting('site_email')) ?>">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Jam Operasional</label>
                <input type="text" name="site_hours" class="form-control" value="<?= e(setting('site_hours')) ?>">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Embed Peta (Google Maps URL)</label>
                <input type="text" name="site_maps" id="siteMaps" class="form-control" value="<?= e(setting('site_maps')) ?>">
                <small class="icon-helper">Gunakan "Bagikan &gt; Sematkan peta &gt; Salin iframe", ambil bagian <code>src</code>-nya (biasanya berisi <code>output=embed</code> atau <code>maps/embed?pb=</code>). Jika pratinjau di bawah kosong, berarti URL bukan format embed.</small>
                <div id="mapPreviewWrap" class="mt-2 <?= setting('site_maps') ? '' : 'd-none' ?>">
                  <iframe id="mapPreview" src="<?= e(setting('site_maps')) ?>" loading="lazy" title="Pratinjau peta" style="width:100%;height:240px;border:0;border-radius:0.5rem;box-shadow:0 6px 20px rgba(15,43,51,0.08)"></iframe>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Instagram</label>
                <input type="url" name="site_instagram" class="form-control" value="<?= e(setting('site_instagram')) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Facebook</label>
                <input type="url" name="site_facebook" class="form-control" value="<?= e(setting('site_facebook')) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>YouTube</label>
                <input type="url" name="site_youtube" class="form-control" value="<?= e(setting('site_youtube')) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Twitter / X</label>
                <input type="url" name="site_twitter" class="form-control" value="<?= e(setting('site_twitter')) ?>">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Subjek Formulir Kontak (satu opsi per baris)</label>
                <textarea name="contact_subjects" class="form-control" rows="4" placeholder="Umum&#10;Pendaftaran &amp; Jadwal Dokter&#10;BPJS &amp; Administrasi&#10;Lainnya"><?= e(setting('contact_subjects')) ?></textarea>
                <small class="icon-helper">Tulis satu pilihan subjek di setiap baris. Muncul sebagai dropdown di halaman Kontak. Kosongkan untuk memakai daftar bawaan.</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-home mr-2 text-primary"></i>Konten Halaman Beranda</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label>Pengumuman (bar berjalan di atas)</label>
            <input type="text" name="site_announcement" class="form-control" value="<?= e(setting('site_announcement')) ?>">
          </div>
          <div class="form-group">
            <label>Judul Sambutan Beranda</label>
            <input type="text" name="home_about_title" class="form-control" value="<?= e(setting('home_about_title')) ?>">
          </div>
          <div class="form-group">
            <label>Teks Sambutan Beranda</label>
            <textarea name="home_about_text" class="form-control" rows="4"><?= e(setting('home_about_text')) ?></textarea>
          </div>
          <div class="form-group">
            <label>Gambar Sambutan Beranda</label>
            <input type="file" name="home_about_image" class="form-control-file" data-preview="#previewHomeImg" accept="image/*">
            <img id="previewHomeImg" class="img-preview mt-2 <?= $homeImg ? '' : 'd-none' ?>" src="<?= $homeImg ? e(img_url('general', $homeImg)) : '' ?>" alt="Gambar Beranda">
          </div>
          <hr class="sep">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Statistik: Tahun</label>
                <input type="number" name="stat_years" class="form-control" value="<?= e(setting('stat_years', '0')) ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Statistik: Pasien</label>
                <input type="number" name="stat_patients" class="form-control" value="<?= e(setting('stat_patients', '0')) ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Statistik: Dokter</label>
                <input type="number" name="stat_doctors" class="form-control" value="<?= e(setting('stat_doctors', '0')) ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Statistik: Departemen</label>
                <input type="number" name="stat_departments" class="form-control" value="<?= e(setting('stat_departments', '0')) ?>">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-4">
        <button type="submit" class="btn btn-accent px-4"><i class="fas fa-save mr-2"></i>Simpan Pengaturan</button>
        <a href="<?= e(base_url('index.php')) ?>" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-globe mr-1"></i>Lihat Hasil</a>
      </div>
    </form>
  </div>

  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-palette mr-2 text-primary"></i>Pratinjau Warna</h3></div>
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-3">
          <span class="badge badge-soft-info" style="width:80px;height:40px;background:<?= e(setting('site_primary_color', '#0a7d8c')) ?>"></span>
          <span>Warna Utama</span>
        </div>
        <div class="d-flex align-items-center gap-3 mb-3">
          <span class="badge badge-soft-info" style="width:80px;height:40px;background:<?= e(setting('site_secondary_color', '#f4a261')) ?>"></span>
          <span>Warna Sekunder</span>
        </div>
        <hr class="sep">
        <p class="icon-helper mb-0">
          Warna utama dipakai untuk menu, tombol, dan aksen website.
          Warna sekunder untuk aksen gradasi dan highlight.
          Simpan lalu buka website untuk melihat hasilnya.
        </p>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle mr-2 text-primary"></i>Panduan</h3></div>
      <div class="card-body">
        <ul class="mb-0" style="font-size:.9rem;color:#5f7d86">
          <li class="mb-1">Logo wajib berupa gambar (JPG/PNG/SVG/WebP).</li>
          <li class="mb-1">Konten lain (dokter, artikel, layanan) dikelola di menu samping.</li>
          <li class="mb-1">Pesan dari formulir kontak tampil di menu "Pesan Masuk".</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
