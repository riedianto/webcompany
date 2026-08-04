<?php
$bannerTitle = $bannerTitle ?? t('banner.default');
$crumbs = $crumbs ?? [['label' => t('banner.home'), 'url' => base_url('index.php')]];
$bannerBg = setting('banner_default_image') ? img_url('general', setting('banner_default_image')) : '';
?>
<div class="page-banner" <?= $bannerBg ? 'style="background-image:url(\'' . e($bannerBg) . '\')"' : '' ?>>
  <div class="container position-relative">
    <h1 data-aos="fade-up"><?= e($bannerTitle) ?></h1>
    <nav aria-label="<?= e(t('banner.default')) ?>" data-aos="fade-up" data-aos-delay="120">
      <ol class="breadcrumb">
        <?php foreach ($crumbs as $c): ?>
          <?php if (!empty($c['url'])): ?>
            <li class="breadcrumb-item"><a href="<?= e($c['url']) ?>"><?= e($c['label']) ?></a></li>
          <?php else: ?>
            <li class="breadcrumb-item active" aria-current="page"><?= e($c['label']) ?></li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ol>
    </nav>
  </div>
</div>
