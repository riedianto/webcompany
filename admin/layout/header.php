<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/includes/config.php';

auth_check();

$pageTitle = $pageTitle ?? 'Dashboard';
$breadcrumbs = $breadcrumbs ?? [];
$section = $section ?? 'dashboard';
$current = basename($_SERVER['PHP_SELF']);
$user = auth_user();
$primary = setting('site_primary_color', '#0a7d8c');
$logo = setting('site_logo') ? img_url('logos', setting('site_logo')) : base_url('assets/img/logo.svg');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> | Admin <?= e(setting('site_name')) ?></title>
<link rel="icon" type="image/svg+xml" href="<?= e($logo) ?>">
<link rel="stylesheet" href="<?= e(base_url('admin/assets/vendor/bootstrap/css/bootstrap.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('admin/assets/vendor/fa/css/all.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('assets/vendor/bootstrap-icons/bootstrap-icons.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('admin/assets/vendor/adminlte/css/adminlte.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('admin/assets/admin.css')) ?>">
<style>
  :root { --accent: <?= e($primary) ?>; --accent-dark: <?= e($primary) ?>; }
  .main-sidebar {
    background: linear-gradient(180deg, <?= e($primary) ?> 0%, #0b3b44 100%) !important;
  }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a class="nav-link" href="<?= e(base_url('index.php')) ?>" target="_blank"><i class="fas fa-globe mr-1"></i>Lihat Website</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item d-none d-sm-inline-block mr-2">
        <span class="nav-link text-muted"><i class="far fa-calendar-alt mr-1"></i><?= date('d M Y') ?></span>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-label="Akun">
          <i class="fas fa-user-circle fa-lg"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header text-muted"><?= e($user['name']) ?> (<?= e($user['role']) ?>)</span>
          <div class="dropdown-divider"></div>
          <a href="users.php" class="dropdown-item"><i class="fas fa-user-edit mr-2"></i>Pengguna Admin</a>
          <div class="dropdown-divider"></div>
          <a href="logout.php" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt mr-2"></i>Keluar</a>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
      <img src="<?= e($logo) ?>" alt="Logo" class="brand-image img-circle elevation-3" style="background:#fff;padding:2px">
      <span class="brand-text font-weight-light"><?= e(setting('site_name')) ?></span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="index.php" class="nav-link <?= $section === 'dashboard' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
            </a>
          </li>
          <li class="nav-header">Konten Website</li>
          <li class="nav-item">
            <a href="settings.php" class="nav-link <?= $section === 'settings' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-cog"></i><p>Pengaturan Website</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="hero.php" class="nav-link <?= $section === 'hero' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-images"></i><p>Slide Banner</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="about.php" class="nav-link <?= $section === 'about' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-building"></i><p>Tentang Kami</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="services.php" class="nav-link <?= $section === 'services' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-stethoscope"></i><p>Layanan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="facilities.php" class="nav-link <?= $section === 'facilities' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-hospital"></i><p>Fasilitas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="doctors.php" class="nav-link <?= $section === 'doctors' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user-md"></i><p>Dokter</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="posts.php" class="nav-link <?= $section === 'posts' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-newspaper"></i><p>Artikel &amp; Berita</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="categories.php" class="nav-link <?= $section === 'categories' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tags"></i><p>Kategori</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="careers.php" class="nav-link <?= $section === 'careers' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-briefcase"></i><p>Karir</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="partners.php" class="nav-link <?= $section === 'partners' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-handshake"></i><p>Rekanan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="gallery.php" class="nav-link <?= $section === 'gallery' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-camera"></i><p>Galeri</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="messages.php" class="nav-link <?= $section === 'messages' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-envelope"></i><p>Pesan Masuk</p>
            </a>
          </li>
          <li class="nav-header">Sistem</li>
          <li class="nav-item">
            <a href="users.php" class="nav-link <?= $section === 'users' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i><p>Pengguna Admin</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?= e($pageTitle) ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <?php foreach ($breadcrumbs as $bc): ?>
                <?php if (!empty($bc['url'])): ?>
                  <li class="breadcrumb-item"><a href="<?= e($bc['url']) ?>"><?= e($bc['label']) ?></a></li>
                <?php else: ?>
                  <li class="breadcrumb-item active"><?= e($bc['label']) ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
      <?php $adminFlash = flash_get(); if ($adminFlash): ?>
      <div class="alert alert-<?= $adminFlash['type'] === 'success' ? 'success' : ($adminFlash['type'] === 'error' ? 'danger' : 'info') ?> alert-dismissible fade show" id="adminFlash">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fas fa-<?= $adminFlash['type'] === 'success' ? 'check' : 'exclamation-triangle' ?> mr-2"></i><?= e($adminFlash['msg']) ?>
      </div>
      <?php endif; ?>
