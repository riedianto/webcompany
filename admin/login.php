<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

if (!empty($_SESSION['admin_id'])) {
    redirect(base_url('admin/index.php'));
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {
        $user = db_one('SELECT * FROM users WHERE username = ?', [$username]);
        if ($user && password_verify($password, (string)$user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$user['id'];
            $_SESSION['admin_user'] = [
                'id' => (int)$user['id'],
                'username' => (string)$user['username'],
                'name' => (string)$user['name'],
                'role' => (string)$user['role'],
            ];
            redirect(base_url('admin/index.php'));
        }
        $error = 'Username atau password salah.';
    } else {
        $error = 'Username dan password wajib diisi.';
    }
}

$primary = setting('site_primary_color', '#0a7d8c');
$logo = setting('site_logo') ? img_url('logos', setting('site_logo')) : base_url('assets/img/logo.svg');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Admin | <?= e(setting('site_name')) ?></title>
<link rel="icon" type="image/svg+xml" href="<?= e($logo) ?>">
<link rel="stylesheet" href="<?= e(base_url('admin/assets/vendor/bootstrap/css/bootstrap.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('admin/assets/vendor/fa/css/all.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('admin/assets/vendor/adminlte/css/adminlte.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('admin/assets/admin.css')) ?>">
<style>
  .login-page { background: linear-gradient(135deg, #0b3b44 0%, <?= e($primary) ?> 60%, #f4a261 130%); }
</style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo text-white mb-3 d-flex align-items-center justify-content-center">
    <img src="<?= e($logo) ?>" alt="Logo" style="width:52px;height:52px;border-radius:12px;object-fit:cover;background:#fff;padding:3px" class="mr-2">
    <span><?= e(setting('site_name')) ?></span>
  </div>
  <div class="card login-card">
    <div class="card-body login-card-body">
      <p class="login-box-msg text-center">Masuk ke Panel Admin</p>

      <?php if ($error): ?>
      <div class="alert alert-danger py-2">
        <i class="fas fa-exclamation-triangle mr-2"></i><?= e($error) ?>
      </div>
      <?php endif; ?>

      <form method="post" action="<?= e(base_url('admin/login.php')) ?>">
        <?= csrf_field() ?>
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" value="<?= e($username) ?>" autofocus required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="togglePass" tabindex="-1"><span class="fas fa-eye"></span></button>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-accent btn-block"><i class="fas fa-sign-in-alt mr-2"></i>Masuk</button>
          </div>
        </div>
      </form>
      <div class="text-center mt-3">
        <a href="<?= e(base_url('index.php')) ?>" class="text-muted small"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Website</a>
      </div>
    </div>
  </div>
</div>

<script src="<?= e(base_url('admin/assets/vendor/jquery/jquery.min.js')) ?>"></script>
<script src="<?= e(base_url('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')) ?>"></script>
<script src="<?= e(base_url('admin/assets/vendor/adminlte/js/adminlte.min.js')) ?>"></script>
<script>
$(function () {
  $('#togglePass').on('click', function () {
    var p = $('#password');
    var type = p.attr('type') === 'password' ? 'text' : 'password';
    p.attr('type', type);
    $(this).find('span').toggleClass('fa-eye fa-eye-slash');
  });
});
</script>
</body>
</html>
