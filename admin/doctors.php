<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Dokter';
$section = 'doctors';
$breadcrumbs = [['label' => 'Dokter', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
$schedMap = [];
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM doctors WHERE id = ?', [$id]);
    if ($edit) {
        foreach (get_doctor_schedules($id) as $s) {
            $schedMap[(int)$s['day']] = $s;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $row = db_one('SELECT * FROM doctors WHERE id = ?', [$rid]);
        if ($row) {
            delete_uploaded('doctors', $row['photo']);
            db_exec('DELETE FROM doctors WHERE id = ?', [$rid]);
            flash('success', 'Dokter berhasil dihapus.');
        }
        redirect(base_url('admin/doctors.php'));
    }

    $name = trim((string)($_POST['name'] ?? ''));
    $specialist = trim((string)($_POST['specialist'] ?? ''));
    $specialistEn = trim((string)($_POST['specialist_en'] ?? ''));
    $serviceId = (int)($_POST['service_id'] ?? 0);
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $bio = trim((string)($_POST['bio'] ?? ''));
    $bioEn = trim((string)($_POST['bio_en'] ?? ''));
    $sort = (int)($_POST['sort'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;
    $schedStart = $_POST['sched_start'] ?? [];
    $schedEnd = $_POST['sched_end'] ?? [];

    if ($name === '') {
        flash('error', 'Nama dokter wajib diisi.');
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Format email tidak valid.');
    } else {
        $oldPhoto = $edit['photo'] ?? null;
        $up = upload_image('photo', 'doctors', $oldPhoto);
        if (!$up['success']) {
            flash('error', $up['error']);
        } else {
            if ($rid > 0) {
                db_exec('UPDATE doctors SET name = ?, specialist = ?, specialist_en = ?, service_id = ?, photo = ?, email = ?, phone = ?, bio = ?, bio_en = ?, sort = ?, active = ? WHERE id = ?',
                    [$name, $specialist, $specialistEn, $serviceId, $up['file'], $email, $phone, $bio, $bioEn, $sort, $active, $rid]);
                save_doctor_schedules($rid, $schedStart, $schedEnd);
                flash('success', 'Dokter berhasil diperbarui.');
            } else {
                db_exec('INSERT INTO doctors (name, specialist, specialist_en, service_id, photo, email, phone, bio, bio_en, sort, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [$name, $specialist, $specialistEn, $serviceId, $up['file'], $email, $phone, $bio, $bioEn, $sort, $active]);
                save_doctor_schedules((int)db()->lastInsertId(), $schedStart, $schedEnd);
                flash('success', 'Dokter berhasil ditambahkan.');
            }
            redirect(base_url('admin/doctors.php'));
        }
    }
}

$rows = db_all('SELECT * FROM doctors ORDER BY sort ASC, id ASC');
$schedMapList = get_schedules_map(array_column($rows, 'id'));
$services = db_all('SELECT * FROM services ORDER BY sort ASC, id ASC');
$serviceMap = [];
foreach ($services as $svc) {
    $serviceMap[(int)$svc['id']] = $svc['title'];
}

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Dokter' : 'Tambah Dokter' ?></h3></div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="text-center mb-3">
            <?php $photo = $edit['photo'] ?? ''; ?>
            <img id="previewPhoto" src="<?= $photo ? e(img_url('doctors', $photo)) : e(base_url('assets/img/doctor-placeholder.svg')) ?>" class="img-thumbnail" style="width:130px;height:130px;object-fit:cover;border-radius:1rem" alt="Foto Dokter">
          </div>
          <div class="form-group">
            <label>Nama Lengkap &amp; Gelar <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="<?= e($edit['name'] ?? '') ?>" placeholder="dr. Contoh, Sp.PD">
          </div>
          <div class="form-group">
            <label>Spesialisasi</label>
            <input type="text" name="specialist" id="docSpec" class="form-control" value="<?= e($edit['specialist'] ?? '') ?>" placeholder="Penyakit Dalam">
          </div>
          <div class="form-group">
            <label>Spesialisasi (EN - terjemahan Inggris)</label>
            <div class="input-group">
              <input type="text" name="specialist_en" id="docSpecEn" class="form-control" value="<?= e($edit['specialist_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#docSpec" data-target="#docSpecEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Layanan / Poli</label>
            <select name="service_id" class="form-control">
              <option value="0">— Tidak terhubung ke poli —</option>
              <?php foreach ($services as $svc): ?>
              <option value="<?= (int)$svc['id'] ?>" <?= selected((int)($edit['service_id'] ?? 0) === (int)$svc['id']) ?>><?= e($svc['title']) ?></option>
              <?php endforeach; ?>
            </select>
            <small class="icon-helper">Poli tempat dokter ini praktik. Daftar dokter akan tampil di halaman layanan sesuai pilihan ini.</small>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <input type="file" name="photo" class="form-control-file" data-preview="#previewPhoto" accept="image/*">
          </div>
          <div class="form-group">
            <label>Jadwal Praktik <span class="text-muted small">(jam per hari)</span></label>
            <div class="table-responsive">
              <table class="table table-sm table-bordered mb-1">
                <thead><tr><th style="width:110px">Hari</th><th>Jam Mulai</th><th>Jam Selesai</th></tr></thead>
                <tbody>
                  <?php foreach (doctor_days() as $dayIdx => $dayName): ?>
                  <?php $s = $schedMap[$dayIdx] ?? null; ?>
                  <tr>
                    <td class="align-middle"><?= $dayName ?></td>
                    <td><input type="time" name="sched_start[<?= $dayIdx ?>]" class="form-control form-control-sm" value="<?= $s && $s['start_time'] ? e(substr((string)$s['start_time'], 0, 5)) : '' ?>"></td>
                    <td><input type="time" name="sched_end[<?= $dayIdx ?>]" class="form-control form-control-sm" value="<?= $s && $s['end_time'] ? e(substr((string)$s['end_time'], 0, 5)) : '' ?>"></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <small class="text-muted">Kosongkan jam mulai &amp; selesai untuk hari libur / tidak praktik.</small>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= e($edit['email'] ?? '') ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Telepon</label>
                <input type="text" name="phone" class="form-control" value="<?= e($edit['phone'] ?? '') ?>">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Biografi</label>
            <textarea name="bio" id="docBio" class="form-control" rows="4"><?= e($edit['bio'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Biografi (EN)</label>
            <div class="input-group">
              <textarea name="bio_en" id="docBioEn" class="form-control" rows="4"><?= e($edit['bio_en'] ?? '') ?></textarea>
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#docBio" data-target="#docBioEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
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
          <button type="submit" class="btn btn-accent"><i class="fas fa-save mr-2"></i><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
          <?php if ($edit): ?><a href="doctors.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Dokter</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($rows) ?> data</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($rows): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Foto</th><th>Nama</th><th>Spesialisasi</th><th>Poli</th><th>Jadwal</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
              <?php $rPhoto = $r['photo'] ? img_url('doctors', $r['photo']) : base_url('assets/img/doctor-placeholder.svg'); ?>
              <?php $rSched = schedule_compact($schedMapList[$r['id']] ?? []); ?>
              <tr>
                <td><img src="<?= e($rPhoto) ?>" class="img-preview-sm rounded-circle" alt=""></td>
                <td class="font-weight-bold"><?= e($r['name']) ?></td>
                <td><?= e($r['specialist']) ?><?php if (empty($r['specialist_en'])): ?> <span class="badge badge-soft-warning" title="Belum diterjemahkan ke Inggris">EN-</span><?php endif; ?></td>
                <td><?= isset($serviceMap[(int)$r['service_id']]) ? e($serviceMap[(int)$r['service_id']]) : '<span class="text-muted">-</span>' ?></td>
                <td class="text-muted small"><?= $rSched !== '' ? e($rSched) : '-' ?></td>
                <td>
                  <?php if ((int)$r['active'] === 1): ?><span class="badge badge-soft-success">Aktif</span>
                  <?php else: ?><span class="badge badge-soft-danger">Nonaktif</span><?php endif; ?>
                </td>
                <td class="text-right">
                  <a href="doctors.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus dokter ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada dokter.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
