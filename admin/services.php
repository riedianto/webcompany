<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Layanan';
$section = 'services';
$breadcrumbs = [['label' => 'Layanan', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM services WHERE id = ?', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $row = db_one('SELECT * FROM services WHERE id = ?', [$rid]);
        if ($row) {
            delete_uploaded('services', $row['image']);
            db_exec('DELETE FROM services WHERE id = ?', [$rid]);
            flash('success', 'Layanan berhasil dihapus.');
        }
        redirect(base_url('admin/services.php'));
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $titleEn = trim((string)($_POST['title_en'] ?? ''));
    $icon = trim((string)($_POST['icon'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $descriptionEn = trim((string)($_POST['description_en'] ?? ''));
    $sort = (int)($_POST['sort'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($title === '') {
        flash('error', 'Nama layanan wajib diisi.');
    } else {
        $oldImage = $edit['image'] ?? null;
        $up = upload_image('image', 'services', $oldImage);
        if (!$up['success']) {
            flash('error', $up['error']);
        } else {
            if ($rid > 0) {
                db_exec('UPDATE services SET title = ?, title_en = ?, icon = ?, description = ?, description_en = ?, image = ?, sort = ?, active = ? WHERE id = ?',
                    [$title, $titleEn, $icon, $description, $descriptionEn, $up['file'], $sort, $active, $rid]);
                flash('success', 'Layanan berhasil diperbarui.');
            } else {
                db_exec('INSERT INTO services (title, title_en, icon, description, description_en, image, sort, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                    [$title, $titleEn, $icon, $description, $descriptionEn, $up['file'], $sort, $active]);
                flash('success', 'Layanan berhasil ditambahkan.');
            }
            redirect(base_url('admin/services.php'));
        }
    }
}

$rows = db_all('SELECT * FROM services ORDER BY sort ASC, id ASC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Layanan' : 'Tambah Layanan' ?></h3></div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Nama Layanan <span class="text-danger">*</span></label>
            <input type="text" name="title" id="svcTitle" class="form-control" required value="<?= e($edit['title'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Nama Layanan (EN - terjemahan Inggris)</label>
            <div class="input-group">
              <input type="text" name="title_en" id="svcTitleEn" class="form-control" value="<?= e($edit['title_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#svcTitle" data-target="#svcTitleEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Ikon (Bootstrap Icons)</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text" id="biIconPreview"><i class="<?= e($edit['icon'] ?? '') ?: 'bi bi-heart-pulse' ?>"></i></span></div>
              <input type="text" name="icon" id="iconField" class="form-control js-icon-field" placeholder="bi bi-heart-pulse" data-default-icon="bi bi-heart-pulse" value="<?= e($edit['icon'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent" data-bi-picker data-target="#iconField"><i class="fas fa-icons mr-1"></i>Pilih Ikon</button>
              </div>
            </div>
            <small class="icon-helper">Contoh: bi bi-heart-pulse, bi bi-brain, bi bi-eye-fill.</small>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" id="svcDesc" class="form-control" rows="3"><?= e($edit['description'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Deskripsi (EN)</label>
            <div class="input-group">
              <textarea name="description_en" id="svcDescEn" class="form-control" rows="3"><?= e($edit['description_en'] ?? '') ?></textarea>
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#svcDesc" data-target="#svcDescEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Gambar (opsional)</label>
            <input type="file" name="image" class="form-control-file" data-preview="#previewImg" accept="image/*">
            <?php $img = $edit['image'] ?? ''; ?>
            <img id="previewImg" class="img-preview mt-2 <?= $img ? '' : 'd-none' ?>" src="<?= $img ? e(img_url('services', $img)) : '' ?>" alt="Preview">
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
          <?php if ($edit): ?><a href="services.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Layanan</h3>
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
                <td style="font-size:1.2rem;color:var(--accent)"><i class="<?= e($r['icon'] ?: 'bi bi-heart-pulse-fill') ?>"></i></td>
                <td class="font-weight-bold"><?= e($r['title']) ?><?php if (empty($r['title_en'])): ?> <span class="badge badge-soft-warning" title="Belum diterjemahkan ke Inggris">EN-</span><?php endif; ?></td>
                <td>
                  <?php if ((int)$r['active'] === 1): ?><span class="badge badge-soft-success">Aktif</span>
                  <?php else: ?><span class="badge badge-soft-danger">Nonaktif</span><?php endif; ?>
                </td>
                <td class="text-right">
                  <a href="services.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus layanan ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada layanan.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/icon-picker.php'; ?>
<?php include __DIR__ . '/layout/footer.php'; ?>
