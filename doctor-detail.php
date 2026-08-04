<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$id = (int)($_GET['id'] ?? 0);
$doc = db_one('SELECT * FROM doctors WHERE id = ? AND active = 1', [$id]);

if (!$doc) {
    redirect(base_url('doctors.php'));
}

$pageTitle = $doc['name'];
$active = 'doctors';

$others = db_all('SELECT * FROM doctors WHERE active = 1 AND id <> ? ORDER BY sort ASC LIMIT 4', [$id]);

$schedules = get_doctor_schedules($id);
$schedByDay = [];
foreach ($schedules as $s) {
    $schedByDay[(int)$s['day']] = $s;
}
$schedComp = schedule_compact($schedules);

include __DIR__ . '/includes/header.php';

$photo = $doc['photo'] ? img_url('doctors', $doc['photo']) : base_url('assets/img/doctor-placeholder.svg');
$bannerBg = $doc['photo'] ? img_url('doctors', $doc['photo']) : '';

$bannerTitle = t('doctor.banner');
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('nav.doctors'), 'url' => base_url('doctors.php')],
    ['label' => $doc['name'], 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';
?>

<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4" data-aos="fade-right">
        <div class="doc-card">
          <div class="doc-photo-wrap" style="height:340px"><img class="doc-photo" src="<?= e($photo) ?>" alt="<?= e($doc['name']) ?>"></div>
          <div class="doc-body text-center">
            <h4 class="doc-name"><?= e($doc['name']) ?></h4>
            <div class="doc-spec mb-3"><?= e($doc['specialist']) ?></div>
            <?php if ($doc['phone']): ?><div class="doc-sched mb-2 justify-content-center"><i class="bi bi-telephone"></i><?= e($doc['phone']) ?></div><?php endif; ?>
            <?php if ($doc['email']): ?><div class="doc-sched mb-2 justify-content-center"><i class="bi bi-envelope"></i><?= e($doc['email']) ?></div><?php endif; ?>
            <?php if ($schedComp): ?><div class="doc-sched justify-content-center"><i class="bi bi-clock"></i><?= e($schedComp) ?></div><?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-8" data-aos="fade-left" data-aos-delay="120">
        <span class="section-eyebrow"><?= e(t('doctor.bio')) ?></span>
        <h2 class="section-title mb-4"><?= e($doc['name']) ?></h2>
        <div class="lead" style="color:var(--muted)">
          <?= $doc['bio'] ? nl2br($doc['bio']) : '<p>' . e(t('doctor.nobio')) . '</p>' ?>
        </div>
        <div class="row g-3 mt-3">
          <div class="col-md-6">
            <div class="p-4 rounded-4 border surface-card h-100" style="border-color:var(--border)!important">
              <div class="d-flex align-items-center gap-3 mb-3">
                <span class="icon-wrap" style="width:46px;height:46px"><i class="bi bi-clock-history"></i></span>
                <h6 class="mb-0"><?= e(t('doctor.schedule')) ?></h6>
              </div>
              <?php if ($schedules): ?>
              <table class="table table-sm mb-0">
                <tbody>
                  <?php foreach (doctor_days() as $day => $dayName): ?>
                  <?php $s = $schedByDay[$day] ?? null; ?>
                  <tr>
                    <td class="ps-0"><?= $dayName ?></td>
                    <td class="text-end fw-semibold"><?= $s && $s['start_time'] && $s['end_time'] ? e(format_time($s['start_time']) . ' – ' . format_time($s['end_time'])) : '<span style="color:var(--muted)">' . e(t('doctor.off')) . '</span>' ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <?php else: ?>
              <p class="mb-0" style="color:var(--muted)">-</p>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-4 rounded-4 border surface-card h-100" style="border-color:var(--border)!important">
              <div class="d-flex align-items-center gap-3 mb-2">
                <span class="icon-wrap" style="width:46px;height:46px"><i class="bi bi-calendar-check"></i></span>
                <h6 class="mb-0"><?= e(t('doctor.appointment.title')) ?></h6>
              </div>
              <p class="mb-2" style="color:var(--muted)"><?= e(t('doctor.appointment.sub')) ?></p>
              <?php if (register_url()): ?>
              <a class="btn btn-grad btn-sm" href="<?= e(register_url()) ?>" target="_blank" rel="noopener"><?= e(t('doctor.register')) ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($others): ?>
<section class="section pt-0">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4" data-aos="fade-up">
      <div>
        <span class="section-eyebrow"><?= e(t('doctor.others')) ?></span>
        <h2 class="section-title mb-0"><?= e(t('doctor.others.title')) ?></h2>
      </div>
      <a href="<?= e(base_url('doctors.php')) ?>" class="btn btn-outline-primary-round btn-sm"><?= e(t('doctor.all')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4">
      <?php foreach ($others as $other): ?>
      <?php $oPhoto = $other['photo'] ? img_url('doctors', $other['photo']) : base_url('assets/img/doctor-placeholder.svg'); ?>
      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ((int)$other['sort'] % 4) * 80 ?>">
        <div class="doc-card">
          <div class="doc-photo-wrap"><img class="doc-photo" src="<?= e($oPhoto) ?>" alt="<?= e($other['name']) ?>"></div>
          <div class="doc-body">
            <h5 class="doc-name"><?= e($other['name']) ?></h5>
            <div class="doc-spec"><?= e($other['specialist']) ?></div>
            <a class="read-more" href="<?= e(base_url('doctor-detail.php?id=' . $other['id'])) ?>"><?= e(t('doctor.fullprofile')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
