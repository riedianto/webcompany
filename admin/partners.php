<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Rekanan';
$section = 'partners';
$breadcrumbs = [['label' => 'Rekanan', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM partners WHERE id = ?', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $row = db_one('SELECT * FROM partners WHERE id = ?', [$rid]);
        if ($row) {
            delete_uploaded('partners', $row['logo']);
            db_exec('DELETE FROM partners WHERE id = ?', [$rid]);
            flash('success', 'Rekanan berhasil dihapus.');
        }
        redirect(base_url('admin/partners.php'));
    }

    $name = trim((string)($_POST['name'] ?? ''));
    $website = trim((string)($_POST['website'] ?? ''));
    $sort = (int)($_POST['sort'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($name === '') {
        flash('error', 'Nama rekanan wajib diisi.');
    } elseif ($website !== '' && !filter_var($website, FILTER_VALIDATE_URL)) {
        flash('error', 'Format URL website tidak valid.');
    } else {
        $oldLogo = $edit['logo'] ?? null;
        $up = upload_image('logo', 'partners', $oldLogo);
        if (!$up['success']) {
            flash('error', $up['error']);
        } else {
            if ($rid > 0) {
                db_exec('UPDATE partners SET name = ?, logo = ?, website = ?, sort = ?, active = ? WHERE id = ?',
                    [$name, $up['file'], $website, $sort, $active, $rid]);
                flash('success', 'Rekanan berhasil diperbarui.');
            } else {
                db_exec('INSERT INTO partners (name, logo, website, sort, active) VALUES (?, ?, ?, ?, ?)',
                    [$name, $up['file'], $website, $sort, $active]);
                flash('success', 'Rekanan berhasil ditambahkan.');
            }
            redirect(base_url('admin/partners.php'));
        }
    }
}

$rows = db_all('SELECT * FROM partners ORDER BY sort ASC, id ASC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Rekanan' : 'Tambah Rekanan' ?></h3></div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Nama Rekanan / Instansi <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="<?= e($edit['name'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Logo (opsional)</label>
            <input type="file" name="logo" class="form-control-file" data-preview="#previewLogo" accept="image/*">
            <?php $logo = $edit['logo'] ?? ''; ?>
            <img id="previewLogo" class="img-preview mt-2 <?= $logo ? '' : 'd-none' ?>" src="<?= $logo ? e(img_url('partners', $logo)) : '' ?>" alt="Preview">
            <small class="icon-helper d-block">Kosongkan untuk menampilkan nama saja.</small>
          </div>
          <div class="form-group">
            <label>Website</label>
            <input type="url" name="website" class="form-control" value="<?= e($edit['website'] ?? '') ?>">
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
          <?php if ($edit): ?><a href="partners.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Rekanan</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($rows) ?> data</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($rows): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Logo</th><th>Nama</th><th>Website</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
              <tr>
                <td>
                  <?php if ($r['logo']): ?><img src="<?= e(img_url('partners', $r['logo'])) ?>" class="img-preview-sm" alt=""><?php else: ?><span class="text-muted small">-</span><?php endif; ?>
                </td>
                <td class="font-weight-bold"><?= e($r['name']) ?></td>
                <td class="small"><?= $r['website'] ? '<a href="' . e($r['website']) . '" target="_blank" rel="noopener">' . e(parse_url($r['website'], PHP_URL_HOST)) . '</a>' : '-' ?></td>
                <td>
                  <?php if ((int)$r['active'] === 1): ?><span class="badge badge-soft-success">Aktif</span>
                  <?php else: ?><span class="badge badge-soft-danger">Nonaktif</span><?php endif; ?>
                </td>
                <td class="text-right">
                  <a href="partners.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus rekanan ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada rekanan.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
