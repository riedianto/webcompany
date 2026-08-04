<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.careers');
$active = 'careers';

$openJobs = db_all("SELECT * FROM careers WHERE status = 'open' ORDER BY created_at DESC");
$closedJobs = db_all("SELECT * FROM careers WHERE status = 'closed' ORDER BY created_at DESC");

include __DIR__ . '/includes/header.php';

$bannerTitle = t('careers.banner');
$bannerBg = setting('banner_careers') ? img_url('general', setting('banner_careers')) : '';
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('nav.information'), 'url' => ''],
    ['label' => t('careers.banner'), 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';
?>

<section class="section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('careers.eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('careers.title')) ?></h2>
      <p class="section-sub mx-auto"><?= e(t('careers.sub')) ?></p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-10">
        <?php if ($openJobs): ?>
          <div class="accordion" id="careerAccordion">
            <?php foreach ($openJobs as $i => $job): ?>
            <div class="accordion-item mb-3 rounded-4 overflow-hidden border-0 shadow-sm">
              <h2 class="accordion-header" id="heading-<?= $job['id'] ?>">
                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $job['id'] ?>" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="collapse-<?= $job['id'] ?>">
                  <div class="d-flex flex-wrap align-items-center justify-content-between w-100 pe-3">
                    <div>
                      <span class="fw-bold d-block" style="color:var(--ink)"><?= e($job['position']) ?></span>
                      <small class="text-muted"><i class="bi bi-building me-1"></i><?= e($job['department']) ?></small>
                    </div>
                    <span class="chip ms-2"><i class="bi bi-briefcase me-1"></i><?= e(t('careers.open')) ?></span>
                  </div>
                </button>
              </h2>
              <div id="collapse-<?= $job['id'] ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" aria-labelledby="heading-<?= $job['id'] ?>" data-bs-parent="#careerAccordion">
                <div class="accordion-body border-top" style="border-color:var(--border-soft)!important">
                  <h6 class="fw-bold" style="color:var(--primary)"><i class="bi bi-card-list me-2"></i><?= e(t('careers.desc')) ?></h6>
                  <div class="mb-3" style="color:var(--muted)"><?= nl2br(e($job['description'])) ?></div>
                  <h6 class="fw-bold" style="color:var(--primary)"><i class="bi bi-check2-circle me-2"></i><?= e(t('careers.qualification')) ?></h6>
                  <div class="mb-3" style="color:var(--muted)"><?= nl2br(e($job['requirements'])) ?></div>
                  <div class="d-flex flex-wrap align-items-center gap-3">
                    <?php if ($job['deadline']): ?>
                    <span class="small text-muted"><i class="bi bi-clock me-1"></i><?= e(t('careers.deadline', [format_date($job['deadline'])])) ?></span>
                    <?php endif; ?>
                    <?php if ($job['apply_link']): ?>
                    <a class="btn btn-grad btn-sm ms-auto" href="<?= e($job['apply_link']) ?>" target="_blank" rel="noopener"><i class="bi bi-send me-1"></i><?= e(t('careers.apply')) ?></a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <i class="bi bi-briefcase fs-1 d-block mb-3" style="color:var(--primary)"></i>
            <h4><?= e(t('careers.empty.title')) ?></h4>
            <p class="text-muted"><?= e(t('careers.empty.sub')) ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($closedJobs): ?>
    <div class="row justify-content-center mt-5">
      <div class="col-lg-10">
        <h5 class="mb-3"><i class="bi bi-clock-history me-2" style="color:var(--secondary)"></i><?= e(t('careers.closed')) ?></h5>
        <div class="row g-3">
          <?php foreach ($closedJobs as $job): ?>
          <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center p-3 rounded-4 border surface-card" style="border-color:var(--border)!important">
              <div>
                <span class="fw-semibold d-block"><?= e($job['position']) ?></span>
                <small class="text-muted"><?= e($job['department']) ?></small>
              </div>
              <span class="badge rounded-pill text-bg-secondary"><?= e(t('careers.closed_badge')) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
