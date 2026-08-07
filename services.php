<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.services');
$active = 'services';

$services = db_all('SELECT * FROM services WHERE active = 1 ORDER BY sort ASC');
$facilities = db_all('SELECT * FROM facilities WHERE active = 1 ORDER BY sort ASC');

$facImagesByFac = [];
$facIds = array_column($facilities, 'id');
if ($facIds) {
    $ph = implode(',', array_fill(0, count($facIds), '?'));
    foreach (db_all("SELECT facility_id, image FROM facility_images WHERE facility_id IN ($ph) ORDER BY sort ASC, id ASC", $facIds) as $fi) {
        if (!empty($fi['image'])) {
            $facImagesByFac[(int)$fi['facility_id']][] = $fi['image'];
        }
    }
}

include __DIR__ . '/includes/header.php';

$bannerTitle = t('nav.services');
$bannerBg = setting('banner_services') ? img_url('general', setting('banner_services')) : '';
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
      <?php $svcUrl = e(base_url('detail.php?type=service&id=' . $svc['id'])); ?>
      <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="<?= ((int)$svc['sort'] % 4) * 80 ?>">
        <a class="icon-card icon-card-link" href="<?= $svcUrl ?>">
          <?php if ($sImg): ?><img src="<?= e($sImg) ?>" alt="<?= e($svc['title']) ?>" class="icon-card-img rounded-4 mb-3"><?php else: ?><div class="icon-card-img icon-card-placeholder rounded-4 mb-3"><i class="<?= e($svc['icon'] ?: 'bi bi-heart-pulse-fill') ?>"></i></div><?php endif; ?>
          <span class="icon-wrap"><i class="<?= e($svc['icon'] ?: 'bi bi-heart-pulse-fill') ?>"></i></span>
          <h5><?= e($svc['title']) ?></h5>
          <p><?= e($svc['description']) ?></p>
        </a>
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
      <?php $facUrl = e(base_url('detail.php?type=facility&id=' . $fac['id'])); ?>
      <?php
        $slides = [];
        if ($fac['image']) {
            $slides[] = $fac['image'];
        }
        foreach (($facImagesByFac[(int)$fac['id']] ?? []) as $fim) {
            if (!in_array($fim, $slides, true)) {
                $slides[] = $fim;
            }
        }
      ?>
      <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="<?= ((int)$fac['sort'] % 4) * 80 ?>">
        <div class="icon-card icon-card-link">
          <?php if (count($slides) > 1): ?>
          <div id="facCarousel-<?= (int)$fac['id'] ?>" class="carousel slide fac-carousel rounded-4 mb-3" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
              <?php foreach ($slides as $si => $slide): ?>
              <div class="carousel-item <?= $si === 0 ? 'active' : '' ?>">
                <a href="<?= $facUrl ?>"><img src="<?= e(img_url('facilities', $slide)) ?>" alt="<?= e($fac['title']) ?>" class="fac-carousel-img"></a>
              </div>
              <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#facCarousel-<?= (int)$fac['id'] ?>" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden"><?= e(t('hero.prev')) ?></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#facCarousel-<?= (int)$fac['id'] ?>" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden"><?= e(t('hero.next')) ?></span>
            </button>
          </div>
          <?php elseif (count($slides) === 1): ?>
          <a class="icon-card-top" href="<?= $facUrl ?>"><img src="<?= e(img_url('facilities', $slides[0])) ?>" alt="<?= e($fac['title']) ?>" class="icon-card-img rounded-4 mb-3"></a>
          <?php else: ?>
          <div class="icon-card-img icon-card-placeholder rounded-4 mb-3"><i class="<?= e($fac['icon'] ?: 'bi bi-building') ?>"></i></div>
          <?php endif; ?>
          <a class="icon-card-top" href="<?= $facUrl ?>">
            <span class="icon-wrap"><i class="<?= e($fac['icon'] ?: 'bi bi-building') ?>"></i></span>
            <h5><?= e($fac['title']) ?></h5>
          </a>
          <p><?= e($fac['description']) ?></p>
          <a class="read-more" href="<?= $facUrl ?>"><?= e(t('services.readmore')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
