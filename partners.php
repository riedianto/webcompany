<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.partners');
$active = 'partners';

$partners = db_all('SELECT * FROM partners WHERE active = 1 ORDER BY sort ASC');

include __DIR__ . '/includes/header.php';

$bannerTitle = t('partners.banner');
$bannerBg = setting('banner_partners') ? img_url('general', setting('banner_partners')) : '';
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('nav.information'), 'url' => ''],
    ['label' => t('partners.banner'), 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';
?>

<section class="section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('partners.eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('partners.title')) ?></h2>
      <p class="section-sub mx-auto"><?= e(t('partners.sub')) ?></p>
    </div>

    <div class="row g-4">
      <?php if ($partners): ?>
        <?php foreach ($partners as $pt): ?>
        <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ((int)$pt['sort'] % 4) * 80 ?>">
          <a href="<?= e($pt['website'] ?: '#') ?>" target="_blank" rel="noopener" class="partner-logo d-block w-100">
            <?php if ($pt['logo']): ?>
              <img src="<?= e(img_url('partners', $pt['logo'])) ?>" alt="<?= e($pt['name']) ?>">
            <?php else: ?>
              <?= e($pt['name']) ?>
            <?php endif; ?>
          </a>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <i class="bi bi-people fs-1 d-block mb-3" style="color:var(--primary)"></i>
          <h4><?= e(t('partners.empty')) ?></h4>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
