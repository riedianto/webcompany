<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$type = (string)($_GET['type'] ?? '');
$id = (int)($_GET['id'] ?? 0);

if (!in_array($type, ['service', 'facility'], true) || $id < 1) {
    redirect(base_url('services.php'));
}

$table  = $type === 'service' ? 'services' : 'facilities';
$subdir = $type === 'service' ? 'services' : 'facilities';
$item = db_one("SELECT * FROM {$table} WHERE id = ? AND active = 1", [$id]);

if (!$item) {
    redirect(base_url('services.php'));
}

$pageTitle = $item['title'];
$active = 'services';

include __DIR__ . '/includes/header.php';

$bannerTitle = $item['title'];
$bannerBg = $item['image'] ? img_url($subdir, $item['image']) : '';
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('nav.services'), 'url' => base_url('services.php')],
    ['label' => $item['title'], 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';

$related = db_all("SELECT id, title, icon, description, image FROM {$table} WHERE active = 1 AND id <> ? ORDER BY sort ASC, id ASC LIMIT 4", [$id]);
$itemImg = $item['image'] ? img_url($subdir, $item['image']) : '';
$icon = $item['icon'] ?: ($type === 'service' ? 'bi bi-heart-pulse-fill' : 'bi bi-building');

$slides = [];
if ($type === 'facility') {
    if ($item['image']) {
        $slides[] = $item['image'];
    }
    foreach (db_all('SELECT image FROM facility_images WHERE facility_id = ? ORDER BY sort ASC, id ASC', [$id]) as $fi) {
        if (!empty($fi['image'])) {
            $slides[] = $fi['image'];
        }
    }
    $slides = array_values(array_unique($slides));
}

$serviceDoctors = $type === 'service'
    ? db_all('SELECT * FROM doctors WHERE active = 1 AND service_id = ? ORDER BY sort ASC, id ASC', [$id])
    : [];
?>

<section class="section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-7" data-aos="fade-right">
        <?php if ($type === 'facility' && count($slides) > 1): ?>
        <div id="facilityCarousel" class="carousel slide rounded-4 overflow-hidden shadow" style="border-radius:var(--radius-lg)" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <?php foreach ($slides as $si => $slide): ?>
            <button type="button" data-bs-target="#facilityCarousel" data-bs-slide-to="<?= $si ?>" class="<?= $si === 0 ? 'active' : '' ?>" aria-label="Slide <?= $si + 1 ?>"></button>
            <?php endforeach; ?>
          </div>
          <div class="carousel-inner">
            <?php foreach ($slides as $si => $slide): ?>
            <div class="carousel-item <?= $si === 0 ? 'active' : '' ?>">
              <img src="<?= e(img_url('facilities', $slide)) ?>" alt="<?= e($item['title']) ?>" style="width:100%;max-height:420px;object-fit:cover;display:block">
            </div>
            <?php endforeach; ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#facilityCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?= e(t('hero.prev')) ?></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#facilityCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?= e(t('hero.next')) ?></span>
          </button>
        </div>
        <?php elseif ($itemImg): ?>
        <div class="rounded-4 overflow-hidden shadow" style="border-radius:var(--radius-lg)">
          <img src="<?= e($itemImg) ?>" alt="<?= e($item['title']) ?>" style="width:100%;max-height:420px;object-fit:cover;display:block">
        </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-5" data-aos="fade-left" data-aos-delay="120">
        <span class="icon-wrap" style="width:64px;height:64px;font-size:1.8rem"><i class="<?= e($icon) ?>"></i></span>
        <h1 class="section-title mt-3 mb-3"><?= e($item['title']) ?></h1>
        <div style="color:var(--muted)"><?= nl2br_e($item['description']) ?></div>
        <div class="mt-4">
          <a href="<?= e(base_url('services.php')) ?>" class="btn btn-outline-primary-round"><i class="bi bi-arrow-left me-1"></i><?= e(t('detail.back')) ?></a>
          <a href="<?= e(base_url('contact.php')) ?>" class="btn btn-grad ms-2"><i class="bi bi-envelope me-1"></i><?= e(t('detail.contact')) ?></a>
        </div>
      </div>
    </div>

    <?php if ($type === 'service' && $serviceDoctors): ?>
    <div class="mt-5 pt-4">
      <div class="text-center mb-4" data-aos="fade-up">
        <span class="section-eyebrow"><?= e(t('detail.service_doctors')) ?></span>
        <h2 class="section-title"><?= e(t('detail.service_doctors.sub')) ?></h2>
      </div>
      <div class="row g-4">
        <?php foreach ($serviceDoctors as $doc): ?>
        <?php $dPhoto = $doc['photo'] ? img_url('doctors', $doc['photo']) : base_url('assets/img/doctor-placeholder.svg'); ?>
        <?php $dSched = schedule_compact(get_doctor_schedules((int)$doc['id'])); ?>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ((int)$doc['sort'] % 4) * 80 ?>">
          <div class="doc-card">
            <a href="<?= e(base_url('doctor-detail.php?id=' . (int)$doc['id'])) ?>">
              <div class="doc-photo-wrap"><img class="doc-photo" src="<?= e($dPhoto) ?>" alt="<?= e($doc['name']) ?>"></div>
            </a>
            <div class="doc-body">
              <h5 class="doc-name"><?= e($doc['name']) ?></h5>
              <div class="doc-spec"><?= e($doc['specialist']) ?></div>
              <?php if ($dSched): ?><div class="doc-sched"><i class="bi bi-clock"></i><?= e($dSched) ?></div><?php endif; ?>
              <a class="read-more" href="<?= e(base_url('doctor-detail.php?id=' . (int)$doc['id'])) ?>"><?= e(t('about.fullprofile')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($related): ?>
    <div class="mt-5 pt-4">
      <div class="text-center mb-4" data-aos="fade-up">
        <span class="section-eyebrow"><?= e(t('detail.related')) ?></span>
        <h2 class="section-title"><?= e($type === 'service' ? t('detail.related_services') : t('detail.related_facilities')) ?></h2>
      </div>
      <div class="row g-4">
        <?php foreach ($related as $r): ?>
        <?php $rImg = $r['image'] ? img_url($subdir, $r['image']) : ''; ?>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ((int)$r['id'] % 4) * 80 ?>">
          <a class="icon-card icon-card-link" href="<?= e(base_url('detail.php?type=' . $type . '&id=' . (int)$r['id'])) ?>">
            <?php if ($rImg): ?><img src="<?= e($rImg) ?>" alt="<?= e($r['title']) ?>" class="rounded-4 mb-3" style="width:100%;height:120px;object-fit:cover"><?php endif; ?>
            <span class="icon-wrap"><i class="<?= e($r['icon'] ?: $icon) ?>"></i></span>
            <h5><?= e($r['title']) ?></h5>
            <p><?= e(truncate($r['description'], 90)) ?></p>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
