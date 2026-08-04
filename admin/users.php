<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Pengguna Admin';
$section = 'users';
$breadcrumbs = [['label' => 'Pengguna Admin', 'url' => '']];

$currentUser = auth_user();

$id = (int)($_GET['id'] ?? 0);
$edit = null;
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM users WHERE id = ?', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete' && $rid > 0) {
        $target = db_one('SELECT * FROM users WHERE id = ?', [$rid]);
        if (!$target) {
            flash('error', 'Pengguna tidak ditemukan.');
        } elseif ($rid === (int)($currentUser['id'] ?? 0)) {
            flash('error', 'Anda tidak dapat menghapus akun sendiri.');
        } else {
            $superAdmins = db_one("SELECT COUNT(*) AS c FROM users WHERE role = 'superadmin'");
            if ($target['role'] === 'superadmin' && (int)$superAdmins['c'] <= 1) {
                flash('error', 'Tidak dapat menghapus superadmin terakhir.');
            } else {
                db_exec('DELETE FROM users WHERE id = ?', [$rid]);
                flash('success', 'Pengguna berhasil dihapus.');
            }
        }
        redirect(base_url('admin/users.php'));
    }

    $name = trim((string)($_POST['name'] ?? ''));
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $role = ($_POST['role'] ?? 'admin') === 'superadmin' ? 'superadmin' : 'admin';

    $errors = [];
    if ($name === '') {
        $errors[] = 'Nama wajib diisi.';
    }
    if ($username === '') {
        $errors[] = 'Username wajib diisi.';
    } elseif ($rid === 0 && db_one('SELECT id FROM users WHERE username = ?', [$username])) {
        $errors[] = 'Username sudah digunakan.';
    } elseif ($rid > 0 && db_one('SELECT id FROM users WHERE username = ? AND id <> ?', [$username, $rid])) {
        $errors[] = 'Username sudah digunakan.';
    }
    if ($rid === 0 && $password === '') {
        $errors[] = 'Password wajib diisi untuk pengguna baru.';
    }
    if ($password !== '' && strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter.';
    }

    if ($errors) {
        flash('error', implode(' ', $errors));
    } else {
        if ($rid > 0) {
            if ($password !== '') {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                db_exec('UPDATE users SET name = ?, username = ?, role = ?, password_hash = ? WHERE id = ?', [$name, $username, $role, $hash, $rid]);
            } else {
                db_exec('UPDATE users SET name = ?, username = ?, role = ? WHERE id = ?', [$name, $username, $role, $rid]);
            }
            flash('success', 'Pengguna berhasil diperbarui.');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            db_exec('INSERT INTO users (name, username, password_hash, role) VALUES (?, ?, ?, ?)', [$name, $username, $hash, $role]);
            flash('success', 'Pengguna berhasil ditambahkan.');
        }
        redirect(base_url('admin/users.php'));
    }
}

$rows = db_all('SELECT * FROM users ORDER BY id ASC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Pengguna' : 'Tambah Pengguna' ?></h3></div>
      <div class="card-body">
        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="<?= e($edit['name'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" required value="<?= e($edit['username'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Password <?= $edit ? '(kosongkan jika tidak diubah)' : '<span class="text-danger">*</span>' ?></label>
            <input type="password" name="password" class="form-control" <?= $edit ? '' : 'required' ?> minlength="6">
            <small class="icon-helper">Minimal 6 karakter.</small>
          </div>
          <div class="form-group">
            <label>Peran</label>
            <select name="role" class="form-control">
              <option value="admin" <?= selected(($edit['role'] ?? 'admin') === 'admin') ?>>Admin</option>
              <option value="superadmin" <?= selected(($edit['role'] ?? 'admin') === 'superadmin') ?>>Super Admin</option>
            </select>
          </div>
          <button type="submit" class="btn btn-accent"><i class="fas fa-save mr-2"></i><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
          <?php if ($edit): ?><a href="users.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($rows) ?> data</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($rows): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Nama</th><th>Username</th><th>Peran</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
              <?php foreach ($rows as $u): ?>
              <tr>
                <td class="font-weight-bold"><?= e($u['name']) ?><?= (int)$u['id'] === (int)($currentUser['id'] ?? 0) ? ' <span class="badge badge-soft-info">Anda</span>' : '' ?></td>
                <td><?= e($u['username']) ?></td>
                <td>
                  <?php if ($u['role'] === 'superadmin'): ?><span class="badge badge-soft-info">Super Admin</span>
                  <?php else: ?><span class="badge badge-secondary">Admin</span><?php endif; ?>
                </td>
                <td class="text-right">
                  <a href="users.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus pengguna ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
