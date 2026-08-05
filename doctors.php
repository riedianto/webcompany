<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.doctors');
$active = 'doctors';

$services = db_all('SELECT id, title FROM services WHERE active = 1 ORDER BY sort ASC');

$search = trim($_GET['q'] ?? '');
$poli = (int)($_GET['poli'] ?? 0);
$params = [];
$where = 'WHERE active = 1';
if ($poli > 0) {
    $where .= ' AND service_id = ?';
    $params[] = $poli;
}
if ($search !== '') {
    $where .= ' AND (name LIKE ? OR specialist LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$result = paginate(
    'SELECT * FROM doctors ' . $where . ' ORDER BY sort ASC',
    $params,
    8
);
$doctors = $result['items'];
$docSchedules = get_schedules_map(array_column($doctors, 'id'));

include __DIR__ . '/includes/header.php';

$bannerTitle = t('doctors.banner');
$bannerBg = setting('banner_doctors') ? img_url('general', setting('banner_doctors')) : '';
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('nav.doctors'), 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';
?>

<section class="section">
  <div class="container">
    <div class="text-center mb-4" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('doctors.eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('doctors.title')) ?></h2>
      <p class="section-sub mx-auto"><?= e(t('doctors.sub')) ?></p>
    </div>

    <form method="get" action="<?= e(base_url('doctors.php')) ?>" class="row justify-content-center mb-5 g-2" data-aos="fade-up">
      <div class="col-md-6 col-lg-4">
        <input type="text" name="q" class="form-control" placeholder="<?= e(t('doctors.search_placeholder')) ?>" value="<?= e($search) ?>">
      </div>
      <div class="col-md-4 col-lg-3">
        <select name="poli" class="form-control">
          <option value="0" <?= selected($poli === 0) ?>><?= e(t('doctors.filter_all')) ?></option>
          <?php foreach ($services as $svc): ?>
          <option value="<?= (int)$svc['id'] ?>" <?= selected($poli === (int)$svc['id']) ?>><?= e($svc['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto">
        <button class="btn btn-grad" type="submit"><i class="bi bi-search me-1"></i><?= e(t('doctors.search')) ?></button>
      </div>
    </form>

    <div class="row g-4">
      <?php if ($doctors): ?>
        <?php foreach ($doctors as $doc): ?>
        <?php $photo = $doc['photo'] ? img_url('doctors', $doc['photo']) : base_url('assets/img/doctor-placeholder.svg'); ?>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ((int)$doc['sort'] % 4) * 80 ?>">
          <div class="doc-card">
            <div class="doc-photo-wrap"><img class="doc-photo" src="<?= e($photo) ?>" alt="<?= e($doc['name']) ?>"></div>
            <div class="doc-body">
              <h5 class="doc-name"><?= e($doc['name']) ?></h5>
              <div class="doc-spec"><?= e($doc['specialist']) ?></div>
              <?php $dComp = schedule_compact($docSchedules[$doc['id']] ?? []); ?>
              <?php if ($dComp): ?><div class="doc-sched mb-3"><i class="bi bi-clock"></i><?= e($dComp) ?></div><?php endif; ?>
              <a class="btn btn-outline-primary-round btn-sm w-100" href="<?= e(base_url('doctor-detail.php?id=' . $doc['id'])) ?>"><?= e(t('doctors.viewprofile')) ?></a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <i class="bi bi-person-x fs-1 d-block mb-3" style="color:var(--primary)"></i>
          <h4><?= e(t('doctors.empty.title')) ?></h4>
          <p class="text-muted"><?= e(t('doctors.empty.sub')) ?></p>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($result['total_pages'] > 1): ?>
    <nav class="mt-5" aria-label="<?= e(t('doctors.pagination')) ?>">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
        <?php $pageQuery = 'page=' . $i . ($search !== '' ? '&q=' . urlencode($search) : '') . ($poli > 0 ? '&poli=' . $poli : ''); ?>
        <li class="page-item <?= $i === $result['page'] ? 'active' : '' ?>">
          <a class="page-link" href="<?= e(base_url('doctors.php?' . $pageQuery)) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
      </ul>
    </nav>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
