<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Tentang Kami';
$section = 'about';
$breadcrumbs = [['label' => 'Tentang Kami', 'url' => '']];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $sections = ['profil', 'visi', 'misi', 'sejarah'];
    foreach ($sections as $key) {
        $title = trim((string)($_POST[$key . '_title'] ?? ''));
        $titleEn = trim((string)($_POST[$key . '_title_en'] ?? ''));
        $content = (string)($_POST[$key . '_content'] ?? '');
        $contentEn = (string)($_POST[$key . '_content_en'] ?? '');
        $existing = db_one('SELECT id FROM about WHERE section_key = ?', [$key]);
        if ($existing) {
            db_exec('UPDATE about SET title = ?, title_en = ?, content = ?, content_en = ? WHERE section_key = ?', [$title, $titleEn, $content, $contentEn, $key]);
        } else {
            db_exec('INSERT INTO about (section_key, title, title_en, content, content_en) VALUES (?, ?, ?, ?, ?)', [$key, $title, $titleEn, $content, $contentEn]);
        }
    }
    flash('success', 'Konten "Tentang Kami" berhasil disimpan.');
    redirect(base_url('admin/about.php'));
}

$profil = about_section('profil');
$visi = about_section('visi');
$misi = about_section('misi');
$sejarah = about_section('sejarah');

include __DIR__ . '/layout/header.php';
?>

<form method="post">
  <?= csrf_field() ?>
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-file-alt mr-2 text-primary"></i>Profil Rumah Sakit</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label>Judul Bagian</label>
            <input type="text" name="profil_title" id="profil_title" class="form-control" value="<?= e($profil['title']) ?>">
          </div>
          <div class="form-group">
            <label>Judul Bagian (EN - terjemahan Inggris)</label>
            <div class="input-group">
              <input type="text" name="profil_title_en" id="profil_title_en" class="form-control" value="<?= e($profil['title_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#profil_title" data-target="#profil_title_en" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Isi Profil</label>
            <textarea name="profil_content" id="profil_content" class="form-control" rows="8"><?= e($profil['content']) ?></textarea>
            <small class="icon-helper">Teks bebas. Baris baru otomatis menjadi paragraf.</small>
          </div>
          <div class="form-group">
            <label>Isi Profil (EN - terjemahan Inggris)</label>
            <textarea name="profil_content_en" id="profil_content_en" class="form-control" rows="8"><?= e($profil['content_en'] ?? '') ?></textarea>
            <button type="button" class="btn btn-sm btn-outline-accent js-auto-translate" data-src="#profil_content" data-target="#profil_content_en" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-2 text-primary"></i>Sejarah</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label>Judul Bagian</label>
            <input type="text" name="sejarah_title" id="sejarah_title" class="form-control" value="<?= e($sejarah['title']) ?>">
          </div>
          <div class="form-group">
            <label>Judul Bagian (EN - terjemahan Inggris)</label>
            <div class="input-group">
              <input type="text" name="sejarah_title_en" id="sejarah_title_en" class="form-control" value="<?= e($sejarah['title_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#sejarah_title" data-target="#sejarah_title_en" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Isi Sejarah</label>
            <textarea name="sejarah_content" id="sejarah_content" class="form-control" rows="6"><?= e($sejarah['content']) ?></textarea>
          </div>
          <div class="form-group">
            <label>Isi Sejarah (EN - terjemahan Inggris)</label>
            <textarea name="sejarah_content_en" id="sejarah_content_en" class="form-control" rows="6"><?= e($sejarah['content_en'] ?? '') ?></textarea>
            <button type="button" class="btn btn-sm btn-outline-accent js-auto-translate" data-src="#sejarah_content" data-target="#sejarah_content_en" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-eye mr-2 text-primary"></i>Visi</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label>Judul</label>
            <input type="text" name="visi_title" id="visi_title" class="form-control" value="<?= e($visi['title']) ?>">
          </div>
          <div class="form-group">
            <label>Judul (EN - terjemahan Inggris)</label>
            <div class="input-group">
              <input type="text" name="visi_title_en" id="visi_title_en" class="form-control" value="<?= e($visi['title_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#visi_title" data-target="#visi_title_en" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Isi Visi</label>
            <textarea name="visi_content" id="visi_content" class="form-control" rows="5"><?= e($visi['content']) ?></textarea>
          </div>
          <div class="form-group">
            <label>Isi Visi (EN - terjemahan Inggris)</label>
            <textarea name="visi_content_en" id="visi_content_en" class="form-control" rows="5"><?= e($visi['content_en'] ?? '') ?></textarea>
            <button type="button" class="btn btn-sm btn-outline-accent js-auto-translate" data-src="#visi_content" data-target="#visi_content_en" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-bullseye mr-2 text-primary"></i>Misi</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label>Judul</label>
            <input type="text" name="misi_title" id="misi_title" class="form-control" value="<?= e($misi['title']) ?>">
          </div>
          <div class="form-group">
            <label>Judul (EN - terjemahan Inggris)</label>
            <div class="input-group">
              <input type="text" name="misi_title_en" id="misi_title_en" class="form-control" value="<?= e($misi['title_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#misi_title" data-target="#misi_title_en" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Isi Misi</label>
            <textarea name="misi_content" id="misi_content" class="form-control" rows="8"><?= e($misi['content']) ?></textarea>
            <small class="icon-helper">Setiap poin ditulis di baris baru.</small>
          </div>
          <div class="form-group">
            <label>Isi Misi (EN - terjemahan Inggris)</label>
            <textarea name="misi_content_en" id="misi_content_en" class="form-control" rows="8"><?= e($misi['content_en'] ?? '') ?></textarea>
            <button type="button" class="btn btn-sm btn-outline-accent js-auto-translate" data-src="#misi_content" data-target="#misi_content_en" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-accent w-100"><i class="fas fa-save mr-2"></i>Simpan Semua</button>
    </div>
  </div>
</form>

<?php include __DIR__ . '/layout/footer.php'; ?>
