<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.services');
$active = 'services';

$services = db_all('SELECT * FROM services WHERE active = 1 ORDER BY sort ASC');
$facilities = db_all('SELECT * FROM facilities WHERE active = 1 ORDER BY sort ASC');

include __DIR__ . '/includes/header.php';

$bannerTitle = t('nav.services');
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('nav.services'), 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';
?>

<!-- Services -->
<section class="section">
  <div class="container">
    <div class="text-center" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('services.services.eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('services.services.title')) ?></h2>
      <p class="section-sub mx-auto"><?= e(t('services.services.sub')) ?></p>
    </div>
    <div class="row g-4 mt-3">
      <?php foreach ($services as $svc): ?>
      <?php $sImg = $svc['image'] ? img_url('services', $svc['image']) : ''; ?>
      <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="<?= ((int)$svc['sort'] % 4) * 80 ?>">
        <div class="icon-card">
          <?php if ($sImg): ?><img src="<?= e($sImg) ?>" alt="<?= e($svc['title']) ?>" class="rounded-4 mb-3" style="width:100%;height:120px;object-fit:cover"><?php endif; ?>
          <span class="icon-wrap"><i class="<?= e($svc['icon'] ?: 'bi bi-heart-pulse-fill') ?>"></i></span>
          <h5><?= e($svc['title']) ?></h5>
          <p><?= e($svc['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Facilities -->
<section class="section section-soft">
  <div class="container">
    <div class="text-center" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('services.facilities.eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('services.facilities.title')) ?></h2>
      <p class="section-sub mx-auto"><?= e(t('services.facilities.sub')) ?></p>
    </div>
    <div class="row g-4 mt-3">
      <?php foreach ($facilities as $fac): ?>
      <?php $fImg = $fac['image'] ? img_url('facilities', $fac['image']) : ''; ?>
      <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="<?= ((int)$fac['sort'] % 4) * 80 ?>">
        <div class="icon-card">
          <?php if ($fImg): ?><img src="<?= e($fImg) ?>" alt="<?= e($fac['title']) ?>" class="rounded-4 mb-3" style="width:100%;height:120px;object-fit:cover"><?php endif; ?>
          <span class="icon-wrap"><i class="<?= e($fac['icon'] ?: 'bi bi-building') ?>"></i></span>
          <h5><?= e($fac['title']) ?></h5>
          <p><?= e($fac['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
