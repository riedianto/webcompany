<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Fasilitas';
$section = 'facilities';
$breadcrumbs = [['label' => 'Fasilitas', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
$facImages = [];
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM facilities WHERE id = ?', [$id]);
    if ($edit) {
        $facImages = db_all('SELECT * FROM facility_images WHERE facility_id = ? ORDER BY sort ASC, id ASC', [$id]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $row = db_one('SELECT * FROM facilities WHERE id = ?', [$rid]);
        if ($row) {
            delete_uploaded('facilities', $row['image']);
            foreach (db_all('SELECT * FROM facility_images WHERE facility_id = ?', [$rid]) as $fi) {
                delete_uploaded('facilities', $fi['image']);
            }
            db_exec('DELETE FROM facilities WHERE id = ?', [$rid]);
            flash('success', 'Fasilitas berhasil dihapus.');
        }
        redirect(base_url('admin/facilities.php'));
    }

    if ($action === 'delete_image') {
        $imgId = (int)($_POST['image_id'] ?? 0);
        $row = db_one('SELECT * FROM facility_images WHERE id = ?', [$imgId]);
        if ($row) {
            delete_uploaded('facilities', $row['image']);
            db_exec('DELETE FROM facility_images WHERE id = ?', [$imgId]);
            flash('success', 'Gambar slide berhasil dihapus.');
            redirect(base_url('admin/facilities.php?id=' . (int)$row['facility_id']));
        }
        redirect(base_url('admin/facilities.php'));
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $icon = trim((string)($_POST['icon'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $sort = (int)($_POST['sort'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($title === '') {
        flash('error', 'Nama fasilitas wajib diisi.');
    } else {
        $oldImage = $edit['image'] ?? null;
        $up = upload_image('image', 'facilities', $oldImage);
        if (!$up['success']) {
            flash('error', $up['error']);
        } else {
            if ($rid > 0) {
                db_exec('UPDATE facilities SET title = ?, icon = ?, description = ?, image = ?, sort = ?, active = ? WHERE id = ?',
                    [$title, $icon, $description, $up['file'], $sort, $active, $rid]);
                $targetId = $rid;
                flash('success', 'Fasilitas berhasil diperbarui.');
            } else {
                db_exec('INSERT INTO facilities (title, icon, description, image, sort, active) VALUES (?, ?, ?, ?, ?, ?)',
                    [$title, $icon, $description, $up['file'], $sort, $active]);
                $targetId = (int)db()->lastInsertId();
                flash('success', 'Fasilitas berhasil ditambahkan.');
            }

            $upMany = upload_images('images', 'facilities');
            if ($upMany['files']) {
                $maxSort = (int)(db_one('SELECT COALESCE(MAX(sort), 0) AS m FROM facility_images WHERE facility_id = ?', [$targetId])['m'] ?? 0);
                foreach ($upMany['files'] as $mi => $fn) {
                    db_exec('INSERT INTO facility_images (facility_id, image, sort) VALUES (?, ?, ?)', [$targetId, $fn, $maxSort + $mi + 1]);
                }
            }
            if ($upMany['errors']) {
                flash('error', implode(' ', $upMany['errors']));
            }
            redirect(base_url('admin/facilities.php'));
        }
    }
}

$rows = db_all('SELECT * FROM facilities ORDER BY sort ASC, id ASC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Fasilitas' : 'Tambah Fasilitas' ?></h3></div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Nama Fasilitas <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required value="<?= e($edit['title'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Ikon (Bootstrap Icons)</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text" id="biIconPreview"><i class="<?= e($edit['icon'] ?? '') ?: 'bi bi-hospital' ?>"></i></span></div>
              <input type="text" name="icon" id="iconField" class="form-control js-icon-field" placeholder="bi bi-hospital" data-default-icon="bi bi-hospital" value="<?= e($edit['icon'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent" data-bi-picker data-target="#iconField"><i class="fas fa-icons mr-1"></i>Pilih Ikon</button>
              </div>
            </div>
            <small class="icon-helper">Contoh: bi bi-hospital, bi bi-droplet-fill, bi bi-x-ray.</small>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="3"><?= e($edit['description'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Gambar (opsional)</label>
            <input type="file" name="image" class="form-control-file" data-preview="#previewImg" accept="image/*">
            <?php $img = $edit['image'] ?? ''; ?>
            <img id="previewImg" class="img-preview mt-2 <?= $img ? '' : 'd-none' ?>" src="<?= $img ? e(img_url('facilities', $img)) : '' ?>" alt="Preview">
            <small class="icon-helper">Gambar utama / sampul fasilitas.</small>
          </div>
          <div class="form-group">
            <label>Gambar Slide (tambah beberapa)</label>
            <input type="file" name="images[]" class="form-control-file" multiple accept="image/*">
            <small class="icon-helper">Pilih satu atau beberapa gambar sekaligus. Semua gambar tampil sebagai slide show di halaman detail fasilitas.</small>
          </div>
          <?php if ($facImages): ?>
          <div class="form-group">
            <label>Slide Saat Ini</label>
            <div class="row g-2">
              <?php foreach ($facImages as $fi): ?>
              <div class="col-4">
                <div class="position-relative">
                  <img src="<?= e(img_url('facilities', $fi['image'])) ?>" class="img-thumbnail" style="width:100%;height:80px;object-fit:cover;border-radius:0.6rem" alt="Slide">
                  <form method="post" class="position-absolute" style="top:4px;right:4px">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_image">
                    <input type="hidden" name="image_id" value="<?= (int)$fi['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger js-confirm" data-confirm="Hapus gambar slide ini?"><i class="fas fa-times"></i></button>
                  </form>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
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
          <?php if ($edit): ?><a href="facilities.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Fasilitas</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($rows) ?> data</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($rows): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Urut</th><th>Ikon</th><th>Nama</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= (int)$r['sort'] ?></td>
                <td style="font-size:1.2rem;color:var(--accent)"><i class="<?= e($r['icon'] ?: 'bi bi-building') ?>"></i></td>
                <td class="font-weight-bold"><?= e($r['title']) ?></td>
                <td>
                  <?php if ((int)$r['active'] === 1): ?><span class="badge badge-soft-success">Aktif</span>
                  <?php else: ?><span class="badge badge-soft-danger">Nonaktif</span><?php endif; ?>
                </td>
                <td class="text-right">
                  <a href="facilities.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus fasilitas ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada fasilitas.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/icon-picker.php'; ?>
<?php include __DIR__ . '/layout/footer.php'; ?>
