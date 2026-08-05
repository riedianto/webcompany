<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Galeri';
$section = 'gallery';
$breadcrumbs = [['label' => 'Galeri', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM gallery WHERE id = ?', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $row = db_one('SELECT * FROM gallery WHERE id = ?', [$rid]);
        if ($row) {
            delete_uploaded('gallery', $row['image']);
            db_exec('DELETE FROM gallery WHERE id = ?', [$rid]);
            flash('success', 'Gambar galeri berhasil dihapus.');
        }
        redirect(base_url('admin/gallery.php'));
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $sort = (int)($_POST['sort'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    $oldImage = $edit['image'] ?? null;
    $up = upload_image('image', 'gallery', $oldImage);
    if (!$up['success']) {
        flash('error', $up['error']);
    } elseif ($up['file'] === '' && $rid === 0) {
        flash('error', 'Gambar galeri wajib diunggah.');
    } else {
        if ($rid > 0) {
            db_exec('UPDATE gallery SET title = ?, image = ?, sort = ?, active = ? WHERE id = ?',
                [$title, $up['file'], $sort, $active, $rid]);
            flash('success', 'Gambar galeri berhasil diperbarui.');
        } else {
            db_exec('INSERT INTO gallery (title, image, sort, active) VALUES (?, ?, ?, ?)',
                [$title, $up['file'], $sort, $active]);
            flash('success', 'Gambar galeri berhasil ditambahkan.');
        }
        redirect(base_url('admin/gallery.php'));
    }
}

$rows = db_all('SELECT * FROM gallery ORDER BY sort ASC, id ASC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Gambar Galeri' : 'Tambah Gambar Galeri' ?></h3></div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Gambar <span class="text-danger">*</span></label>
            <input type="file" name="image" class="form-control-file" data-preview="#previewImg" accept="image/*">
            <?php $img = $edit['image'] ?? ''; ?>
            <img id="previewImg" class="img-preview mt-2 <?= $img ? '' : 'd-none' ?>" src="<?= $img ? e(img_url('gallery', $img)) : '' ?>" alt="Preview">
          </div>
          <div class="form-group">
            <label>Judul / Keterangan (opsional)</label>
            <input type="text" name="title" class="form-control" value="<?= e($edit['title'] ?? '') ?>">
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
          <button type="submit" class="btn btn-accent"><i class="fas fa-save mr-2"></i><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
          <?php if ($edit): ?><a href="gallery.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Galeri</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($rows) ?> data</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($rows): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Gambar</th><th>Judul</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
              <tr>
                <td>
                  <?php if ($r['image']): ?><img src="<?= e(img_url('gallery', $r['image'])) ?>" class="img-preview-sm" alt=""><?php else: ?><span class="text-muted small">-</span><?php endif; ?>
                </td>
                <td class="font-weight-bold"><?= $r['title'] !== '' ? e($r['title']) : '<span class="text-muted">-</span>' ?></td>
                <td>
                  <?php if ((int)$r['active'] === 1): ?><span class="badge badge-soft-success">Aktif</span>
                  <?php else: ?><span class="badge badge-soft-danger">Nonaktif</span><?php endif; ?>
                </td>
                <td class="text-right">
                  <a href="gallery.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus gambar galeri ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada gambar galeri.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
