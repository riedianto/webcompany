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
        $content = (string)($_POST[$key . '_content'] ?? '');
        $existing = db_one('SELECT id FROM about WHERE section_key = ?', [$key]);
        if ($existing) {
            db_exec('UPDATE about SET title = ?, content = ? WHERE section_key = ?', [$title, $content, $key]);
        } else {
            db_exec('INSERT INTO about (section_key, title, content) VALUES (?, ?, ?)', [$key, $title, $content]);
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
            <input type="text" name="profil_title" class="form-control" value="<?= e($profil['title']) ?>">
          </div>
          <div class="form-group">
            <label>Isi Profil</label>
            <textarea name="profil_content" class="form-control" rows="8"><?= e($profil['content']) ?></textarea>
            <small class="icon-helper">Teks bebas. Baris baru otomatis menjadi paragraf.</small>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-2 text-primary"></i>Sejarah</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label>Judul Bagian</label>
            <input type="text" name="sejarah_title" class="form-control" value="<?= e($sejarah['title']) ?>">
          </div>
          <div class="form-group">
            <label>Isi Sejarah</label>
            <textarea name="sejarah_content" class="form-control" rows="6"><?= e($sejarah['content']) ?></textarea>
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
            <input type="text" name="visi_title" class="form-control" value="<?= e($visi['title']) ?>">
          </div>
          <div class="form-group">
            <label>Isi Visi</label>
            <textarea name="visi_content" class="form-control" rows="5"><?= e($visi['content']) ?></textarea>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-bullseye mr-2 text-primary"></i>Misi</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label>Judul</label>
            <input type="text" name="misi_title" class="form-control" value="<?= e($misi['title']) ?>">
          </div>
          <div class="form-group">
            <label>Isi Misi</label>
            <textarea name="misi_content" class="form-control" rows="8"><?= e($misi['content']) ?></textarea>
            <small class="icon-helper">Setiap poin ditulis di baris baru.</small>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-accent w-100"><i class="fas fa-save mr-2"></i>Simpan Semua</button>
    </div>
  </div>
</form>

<?php include __DIR__ . '/layout/footer.php'; ?>
