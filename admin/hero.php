<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Slide Banner';
$section = 'hero';
$breadcrumbs = [['label' => 'Slide Banner', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM hero_slides WHERE id = ?', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $sid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $row = db_one('SELECT * FROM hero_slides WHERE id = ?', [$sid]);
        if ($row) {
            delete_uploaded('heroes', $row['image']);
            db_exec('DELETE FROM hero_slides WHERE id = ?', [$sid]);
            flash('success', 'Slide berhasil dihapus.');
        }
        redirect(base_url('admin/hero.php'));
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $subtitle = trim((string)($_POST['subtitle'] ?? ''));
    $btnText = trim((string)($_POST['btn_text'] ?? ''));
    $btnLink = trim((string)($_POST['btn_link'] ?? ''));
    $sort = (int)($_POST['sort'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($title === '') {
        flash('error', 'Judul slide wajib diisi.');
    } else {
        $oldImage = $edit['image'] ?? null;
        $up = upload_image('image', 'heroes', $oldImage);
        if (!$up['success']) {
            flash('error', $up['error']);
        } else {
            if ($sid > 0) {
                db_exec(
                    'UPDATE hero_slides SET title = ?, subtitle = ?, image = ?, btn_text = ?, btn_link = ?, sort = ?, active = ? WHERE id = ?',
                    [$title, $subtitle, $up['file'], $btnText, $btnLink, $sort, $active, $sid]
                );
                flash('success', 'Slide berhasil diperbarui.');
            } else {
                db_exec(
                    'INSERT INTO hero_slides (title, subtitle, image, btn_text, btn_link, sort, active) VALUES (?, ?, ?, ?, ?, ?, ?)',
                    [$title, $subtitle, $up['file'], $btnText, $btnLink, $sort, $active]
                );
                flash('success', 'Slide berhasil ditambahkan.');
            }
            redirect(base_url('admin/hero.php'));
        }
    }
}

$slides = db_all('SELECT * FROM hero_slides ORDER BY sort ASC, id ASC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?= $edit ? 'Edit Slide' : 'Tambah Slide' ?></h3>
      </div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Judul <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required value="<?= e($edit['title'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Subjudul</label>
            <textarea name="subtitle" class="form-control" rows="2"><?= e($edit['subtitle'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Gambar (opsional)</label>
            <input type="file" name="image" class="form-control-file" data-preview="#previewSlide" accept="image/*">
            <?php $img = $edit['image'] ?? ''; ?>
            <img id="previewSlide" class="img-preview mt-2 <?= $img ? '' : 'd-none' ?>" src="<?= $img ? e(img_url('heroes', $img)) : '' ?>" alt="Preview">
            <small class="icon-helper d-block">Kosongkan untuk memakai latar gradasi otomatis.</small>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Teks Tombol</label>
                <input type="text" name="btn_text" class="form-control" value="<?= e($edit['btn_text'] ?? '') ?>">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label>Link Tombol</label>
                <input type="text" name="btn_link" class="form-control" value="<?= e($edit['btn_link'] ?? '') ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Urutan</label>
                <input type="number" name="sort" class="form-control" value="<?= (int)($edit['sort'] ?? 0) ?>">
              </div>
            </div>
            <div class="col-6 d-flex align-items-end">
              <div class="custom-control custom-switch mb-3">
                <input type="checkbox" class="custom-control-input" id="active" name="active" <?= checked((int)($edit['active'] ?? 1) === 1) ?>>
                <label class="custom-control-label" for="active">Aktif</label>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-accent"><i class="fas fa-save mr-2"></i><?= $edit ? 'Simpan Perubahan' : 'Simpan Slide' ?></button>
          <?php if ($edit): ?>
            <a href="hero.php" class="btn btn-outline-secondary">Batal</a>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Slide</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($slides) ?> slide</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($slides): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr><th>Urutan</th><th>Judul</th><th>Gambar</th><th>Status</th><th class="text-right">Aksi</th></tr>
            </thead>
            <tbody>
              <?php foreach ($slides as $s): ?>
              <tr>
                <td><?= (int)$s['sort'] ?></td>
                <td class="font-weight-bold"><?= e($s['title']) ?></td>
                <td>
                  <?php if ($s['image']): ?>
                    <img src="<?= e(img_url('heroes', $s['image'])) ?>" class="img-preview-sm" alt="">
                  <?php else: ?>
                    <span class="text-muted small">Gradasi</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ((int)$s['active'] === 1): ?>
                    <span class="badge badge-soft-success">Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-soft-danger">Nonaktif</span>
                  <?php endif; ?>
                </td>
                <td class="text-right">
                  <a href="hero.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus slide ini?" title="Hapus"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada slide. Tambahkan slide pertama Anda.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
