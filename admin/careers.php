<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Karir';
$section = 'careers';
$breadcrumbs = [['label' => 'Karir', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM careers WHERE id = ?', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        db_exec('DELETE FROM careers WHERE id = ?', [$rid]);
        flash('success', 'Lowongan berhasil dihapus.');
        redirect(base_url('admin/careers.php'));
    }

    $position = trim((string)($_POST['position'] ?? ''));
    $positionEn = trim((string)($_POST['position_en'] ?? ''));
    $department = trim((string)($_POST['department'] ?? ''));
    $departmentEn = trim((string)($_POST['department_en'] ?? ''));
    $requirements = trim((string)($_POST['requirements'] ?? ''));
    $requirementsEn = trim((string)($_POST['requirements_en'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $descriptionEn = trim((string)($_POST['description_en'] ?? ''));
    $status = ($_POST['status'] ?? 'open') === 'closed' ? 'closed' : 'open';
    $applyLink = trim((string)($_POST['apply_link'] ?? ''));

    if ($applyLink !== '' && !preg_match('~^https?://~i', $applyLink)) {
        $applyLink = '';
    }

    $deadline = trim((string)($_POST['deadline'] ?? ''));
    if ($deadline !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $deadline) !== 1) {
        $deadline = '';
    }

    if ($position === '') {
        flash('error', 'Nama posisi wajib diisi.');
    } else {
        if ($rid > 0) {
            db_exec('UPDATE careers SET position = ?, position_en = ?, department = ?, department_en = ?, requirements = ?, requirements_en = ?, description = ?, description_en = ?, status = ?, deadline = ?, apply_link = ? WHERE id = ?',
                [$position, $positionEn, $department, $departmentEn, $requirements, $requirementsEn, $description, $descriptionEn, $status, $deadline !== '' ? $deadline : null, $applyLink !== '' ? $applyLink : null, $rid]);
            flash('success', 'Lowongan berhasil diperbarui.');
        } else {
            db_exec('INSERT INTO careers (position, position_en, department, department_en, requirements, requirements_en, description, description_en, status, deadline, apply_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [$position, $positionEn, $department, $departmentEn, $requirements, $requirementsEn, $description, $descriptionEn, $status, $deadline !== '' ? $deadline : null, $applyLink !== '' ? $applyLink : null]);
            flash('success', 'Lowongan berhasil ditambahkan.');
        }
        redirect(base_url('admin/careers.php'));
    }
}

$rows = db_all('SELECT * FROM careers ORDER BY status ASC, created_at DESC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Lowongan' : 'Tambah Lowongan' ?></h3></div>
      <div class="card-body">
        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Posisi <span class="text-danger">*</span></label>
            <input type="text" name="position" id="carPosition" class="form-control" required value="<?= e($edit['position'] ?? '') ?>" placeholder="Perawat / Bidan">
          </div>
          <div class="form-group">
            <label>Posisi (EN - terjemahan Inggris)</label>
            <div class="input-group">
              <input type="text" name="position_en" id="carPositionEn" class="form-control" value="<?= e($edit['position_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#carPosition" data-target="#carPositionEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Departemen</label>
            <input type="text" name="department" id="carDept" class="form-control" value="<?= e($edit['department'] ?? '') ?>" placeholder="Rawat Inap">
          </div>
          <div class="form-group">
            <label>Departemen (EN)</label>
            <div class="input-group">
              <input type="text" name="department_en" id="carDeptEn" class="form-control" value="<?= e($edit['department_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#carDept" data-target="#carDeptEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Deskripsi Pekerjaan</label>
            <textarea name="description" id="carDesc" class="form-control" rows="4"><?= e($edit['description'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Deskripsi Pekerjaan (EN)</label>
            <div class="input-group">
              <textarea name="description_en" id="carDescEn" class="form-control" rows="4"><?= e($edit['description_en'] ?? '') ?></textarea>
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#carDesc" data-target="#carDescEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Kualifikasi</label>
            <textarea name="requirements" id="carReq" class="form-control" rows="5"><?= e($edit['requirements'] ?? '') ?></textarea>
            <small class="icon-helper">Tulis setiap kualifikasi di baris baru.</small>
          </div>
          <div class="form-group">
            <label>Kualifikasi (EN)</label>
            <div class="input-group">
              <textarea name="requirements_en" id="carReqEn" class="form-control" rows="5"><?= e($edit['requirements_en'] ?? '') ?></textarea>
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#carReq" data-target="#carReqEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                  <option value="open" <?= selected(($edit['status'] ?? 'open') === 'open') ?>>Terbuka</option>
                  <option value="closed" <?= selected(($edit['status'] ?? 'open') === 'closed') ?>>Ditutup</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Batas Lamaran (tanggal)</label>
                <input type="date" name="deadline" class="form-control" value="<?= e(isset($edit['deadline']) && $edit['deadline'] ? $edit['deadline'] : '') ?>">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Link Google Form Lamaran</label>
            <input type="url" name="apply_link" class="form-control" value="<?= e($edit['apply_link'] ?? '') ?>" placeholder="https://forms.gle/xxxxxxxx">
            <small class="icon-helper">Kosongkan jika belum ada. Tombol "Kirim Lamaran" hanya muncul jika link ini terisi.</small>
          </div>
          <button type="submit" class="btn btn-accent"><i class="fas fa-save mr-2"></i><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
          <?php if ($edit): ?><a href="careers.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Lowongan</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($rows) ?> data</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($rows): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Posisi</th><th>Departemen</th><th>Batas</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
              <tr>
                <td class="font-weight-bold"><?= e($r['position']) ?><?php if (empty($r['position_en'])): ?> <span class="badge badge-soft-warning" title="Belum diterjemahkan ke Inggris">EN-</span><?php endif; ?></td>
                <td><?= e($r['department']) ?></td>
                <td class="text-muted small"><?= $r['deadline'] ? e(format_date_id($r['deadline'])) : '-' ?></td>
                <td>
                  <?php if ($r['status'] === 'open'): ?><span class="badge badge-soft-success">Terbuka</span>
                  <?php else: ?><span class="badge badge-soft-danger">Ditutup</span><?php endif; ?>
                </td>
                <td class="text-right">
                  <a href="careers.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus lowongan ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada lowongan.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
