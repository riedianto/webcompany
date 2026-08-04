<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

$pageTitle = $pageTitle ?? setting('site_tagline');
$active    = $active ?? '';
$primary   = setting('site_primary_color', '#0a7d8c');
$secondary = setting('site_secondary_color', '#f4a261');
$logo      = setting('site_logo') ? img_url('logos', setting('site_logo')) : base_url('assets/img/logo.svg');
$favicon   = setting('site_favicon') ? img_url('logos', setting('site_favicon')) : $logo;
?>
<!DOCTYPE html>
<html lang="<?= e(lang_code()) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
  (function () {
    try {
      var t = localStorage.getItem('theme');
      if (!t) t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    } catch (e) {}
  })();
</script>
<title><?= e($pageTitle) ?> | <?= e(setting('site_name')) ?></title>
<meta name="description" content="<?= e(setting('site_description')) ?>">
<link rel="icon" type="image/svg+xml" href="<?= e($favicon) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(base_url('assets/vendor/bootstrap/css/bootstrap.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('assets/vendor/bootstrap-icons/bootstrap-icons.min.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('assets/vendor/aos/aos.css')) ?>">
<link rel="stylesheet" href="<?= e(base_url('assets/css/style.css')) ?>?v=3">
<style>
  :root {
    --primary: <?= e($primary) ?>;
    --secondary: <?= e($secondary) ?>;
    --primary-dark: <?= e($primary) ?>;
  }
</style>
</head>
<body>

<?php if (setting('site_announcement')): ?>
<div class="announcement">
  <div class="announce-track">
    <span class="px-2"><?= e(setting('site_announcement')) ?></span>
  </div>
</div>
<?php endif; ?>

<div class="topbar d-none d-lg-block">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="d-flex gap-4">
      <span><i class="bi bi-geo-alt-fill"></i><?= e(setting('site_address')) ?></span>
      <a href="tel:<?= e(preg_replace('/\D+/', '', setting('site_phone'))) ?>"><i class="bi bi-telephone-fill"></i><?= e(setting('site_phone')) ?></a>
      <a href="mailto:<?= e(setting('site_email')) ?>"><i class="bi bi-envelope-fill"></i><?= e(setting('site_email')) ?></a>
    </div>
    <div class="d-flex gap-3 align-items-center">
      <div class="lang-switch">
        <a class="lang-btn <?= lang_code() === 'id' ? 'active' : '' ?>" href="<?= e(lang_url('id')) ?>" title="Bahasa Indonesia"><img src="<?= e(base_url('assets/img/flags/id.svg')) ?>" alt="ID"></a>
        <a class="lang-btn <?= lang_code() === 'en' ? 'active' : '' ?>" href="<?= e(lang_url('en')) ?>" title="English"><img src="<?= e(base_url('assets/img/flags/gb.svg')) ?>" alt="EN"></a>
      </div>
      <span class="topbar-divider"></span>
      <?php if (setting('site_instagram')): ?><a href="<?= e(setting('site_instagram')) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a><?php endif; ?>
      <?php if (setting('site_facebook')): ?><a href="<?= e(setting('site_facebook')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a><?php endif; ?>
      <?php if (setting('site_youtube')): ?><a href="<?= e(setting('site_youtube')) ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a><?php endif; ?>
      <?php if (setting('site_twitter')): ?><a href="<?= e(setting('site_twitter')) ?>" target="_blank" rel="noopener" aria-label="X (Twitter)"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
    </div>
  </div>
</div>

<nav class="navbar navbar-expand-lg sticky-top site-navbar" id="siteNavbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="<?= e(base_url('index.php')) ?>">
      <img src="<?= e($logo) ?>" alt="<?= e(setting('site_name')) ?>" class="brand-logo">
      <span class="brand-text">
        <span class="brand-name"><?= e(setting('site_name')) ?></span>
        <span class="brand-tag"><?= e(setting('site_tagline')) ?></span>
      </span>
    </a>
    <div class="d-flex align-items-center gap-3 order-lg-3 ms-lg-4">
      <button id="themeToggle" class="theme-toggle" type="button" aria-label="<?= e(t('theme.title')) ?>" title="<?= e(t('theme.title')) ?>">
        <i class="bi bi-moon-stars-fill"></i>
        <i class="bi bi-sun-fill"></i>
      </button>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="<?= e(t('nav.menu')) ?>">
        <i class="bi bi-list fs-1" style="color:var(--primary)"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link <?= $active === 'home' ? 'active' : '' ?>" href="<?= e(base_url('index.php')) ?>"><?= e(t('nav.home')) ?></a></li>
        <li class="nav-item"><a class="nav-link <?= $active === 'about' ? 'active' : '' ?>" href="<?= e(base_url('about.php')) ?>"><?= e(t('nav.about')) ?></a></li>
        <li class="nav-item"><a class="nav-link <?= $active === 'services' ? 'active' : '' ?>" href="<?= e(base_url('services.php')) ?>"><?= e(t('nav.services')) ?></a></li>
        <li class="nav-item"><a class="nav-link <?= $active === 'doctors' ? 'active' : '' ?>" href="<?= e(base_url('doctors.php')) ?>"><?= e(t('nav.doctors')) ?></a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= in_array($active, ['news', 'careers', 'partners']) ? 'active' : '' ?>" href="#" id="infoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= e(t('nav.information')) ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="infoDropdown">
            <li><a class="dropdown-item" href="<?= e(base_url('news.php')) ?>"><i class="bi bi-newspaper me-2"></i><?= e(t('nav.news')) ?></a></li>
            <li><a class="dropdown-item" href="<?= e(base_url('careers.php')) ?>"><i class="bi bi-briefcase me-2"></i><?= e(t('nav.careers')) ?></a></li>
            <li><a class="dropdown-item" href="<?= e(base_url('partners.php')) ?>"><i class="bi bi-people me-2"></i><?= e(t('nav.partners')) ?></a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link <?= $active === 'contact' ? 'active' : '' ?>" href="<?= e(base_url('contact.php')) ?>"><?= e(t('nav.contact')) ?></a></li>
        <?php if (register_url()): ?>
        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
          <a class="btn btn-grad btn-sm" href="<?= e(register_url()) ?>" target="_blank" rel="noopener"><i class="bi bi-calendar-check me-1"></i><?= e(t('nav.register')) ?></a>
        </li>
        <?php endif; ?>
        <li class="nav-item d-lg-none pt-3 border-top mt-3">
          <div class="lang-switch navbar-lang">
            <span class="lang-switch-label me-2 small fw-semibold"><?= e(t('lang.label')) ?></span>
            <a class="lang-btn <?= lang_code() === 'id' ? 'active' : '' ?>" href="<?= e(lang_url('id')) ?>" title="Bahasa Indonesia"><img src="<?= e(base_url('assets/img/flags/id.svg')) ?>" alt="ID"></a>
            <a class="lang-btn <?= lang_code() === 'en' ? 'active' : '' ?>" href="<?= e(lang_url('en')) ?>" title="English"><img src="<?= e(base_url('assets/img/flags/gb.svg')) ?>" alt="EN"></a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
