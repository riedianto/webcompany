<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.about');
$active = 'about';

$profil  = about_section('profil');
$visi    = about_section('visi');
$misi    = about_section('misi');
$sejarah = about_section('sejarah');

$facilities = db_all('SELECT * FROM facilities WHERE active = 1 ORDER BY sort ASC LIMIT 6');
$doctors = db_all('SELECT * FROM doctors WHERE active = 1 ORDER BY sort ASC LIMIT 6');
$gallery = db_all('SELECT * FROM gallery WHERE active = 1 AND image <> "" ORDER BY sort ASC, id ASC');

include __DIR__ . '/includes/header.php';

$bannerTitle = t('nav.about');
$bannerBg = setting('banner_about') ? img_url('general', setting('banner_about')) : '';
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('nav.about'), 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';
?>

<!-- Profil -->
<section class="section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="about-img-wrap position-relative">
          <?php $aboutImg = setting('home_about_image') ? img_url('general', setting('home_about_image')) : base_url('assets/img/post-placeholder.svg'); ?>
          <img src="<?= e($aboutImg) ?>" alt="<?= e(pick($profil, 'title')) ?>" style="min-height:400px">
          <div class="experience-badge">
            <div class="num"><?= e(setting('stat_years', '25')) ?>+</div>
            <div class="lbl"><?= e(t('about.serving')) ?></div>
          </div>
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
        <span class="section-eyebrow"><?= e(t('about.profile')) ?></span>
        <h2 class="section-title"><?= e(pick($profil, 'title')) ?></h2>
        <div class="lead" style="color:var(--muted)"><?= nl2br(pick($profil, 'content')) ?></div>
      </div>
    </div>
  </div>
</section>

<!-- Visi Misi -->
<section class="section section-light">
  <div class="container">
    <div class="text-center" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('about.vismis')) ?></span>
      <h2 class="section-title"><?= e(t('about.vismis.sub')) ?></h2>
    </div>
    <div class="row g-4 mt-3">
      <div class="col-lg-6" data-aos="fade-up">
        <div class="vm-card">
          <span class="vm-icon"><i class="bi bi-eye-fill"></i></span>
          <h4><?= e(pick($visi, 'title')) ?></h4>
          <p class="mb-0" style="color:var(--muted)"><?= nl2br(pick($visi, 'content')) ?></p>
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="120">
        <div class="vm-card misi">
          <span class="vm-icon"><i class="bi bi-bullseye"></i></span>
          <h4><?= e(pick($misi, 'title')) ?></h4>
          <div style="color:var(--muted)"><?= nl2br(pick($misi, 'content')) ?></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Sejarah -->
<section class="section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 order-lg-2" data-aos="fade-left">
        <span class="section-eyebrow"><?= e(t('about.journey')) ?></span>
        <h2 class="section-title"><?= e(pick($sejarah, 'title')) ?></h2>
        <div style="color:var(--muted)"><?= nl2br(pick($sejarah, 'content')) ?></div>
      </div>
      <div class="col-lg-6 order-lg-1" data-aos="fade-right">
        <div class="row g-3">
          <?php foreach ($facilities as $i => $fac): ?>
          <div class="col-md-6">
            <a href="<?= e(base_url('detail.php?type=facility&id=' . $fac['id'])) ?>" class="d-flex align-items-center gap-2 p-3 rounded-4 border surface-card h-100 text-decoration-none" style="border-color:var(--border)!important;color:inherit">
              <span class="icon-wrap" style="width:44px;height:44px;font-size:1.2rem"><i class="<?= e($fac['icon'] ?: 'bi bi-check-circle-fill') ?>"></i></span>
              <span class="fw-bold"><?= e(pick($fac, 'title')) ?></span>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($doctors): ?>
<!-- Dokter -->
<section class="section section-soft">
  <div class="container">
    <div class="text-center" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('about.team')) ?></span>
      <h2 class="section-title"><?= e(t('about.team.sub')) ?></h2>
    </div>
    <div class="doc-strip" data-aos="fade-up">
      <div class="doc-track">
        <?php for ($r = 0; $r < 2; $r++): ?>
          <?php foreach ($doctors as $doc): ?>
          <?php $photo = $doc['photo'] ? img_url('doctors', $doc['photo']) : base_url('assets/img/doctor-placeholder.svg'); ?>
          <div class="doc-card doc-card-item">
            <div class="doc-photo-wrap"><img class="doc-photo" src="<?= e($photo) ?>" alt="<?= e($doc['name']) ?>"></div>
            <div class="doc-body">
              <h5 class="doc-name"><?= e($doc['name']) ?></h5>
              <div class="doc-spec"><?= e(pick($doc, 'specialist')) ?></div>
              <a class="read-more" href="<?= e(base_url('doctor-detail.php?id=' . $doc['id'])) ?>"><?= e(t('about.fullprofile')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($gallery): ?>
<!-- Galeri -->
<section class="section">
  <div class="container">
    <div class="text-center" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('about.gallery')) ?></span>
      <h2 class="section-title"><?= e(t('about.gallery.sub')) ?></h2>
    </div>
    <div class="gal-strip" data-aos="fade-up">
      <div class="gal-track">
        <?php for ($r = 0; $r < 2; $r++): ?>
          <?php foreach ($gallery as $gi): ?>
          <a class="gallery-item gal-item" href="<?= e(img_url('gallery', $gi['image'])) ?>" data-caption="<?= e(pick($gi, 'title') ?: '') ?>" aria-label="<?= e(pick($gi, 'title') ?: 'Galeri') ?>">
            <img src="<?= e(img_url('gallery', $gi['image'])) ?>" alt="<?= e(pick($gi, 'title') ?: 'Galeri') ?>">
            <?php if ($gi['title']): ?><span class="gallery-caption"><?= e(pick($gi, 'title')) ?></span><?php endif; ?>
          </a>
          <?php endforeach; ?>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
