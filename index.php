<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.home');
$active = 'home';

$slides = db_all('SELECT * FROM hero_slides WHERE active = 1 ORDER BY sort ASC');
$services = db_all('SELECT * FROM services WHERE active = 1 ORDER BY sort ASC LIMIT 8');
$facilities = db_all('SELECT * FROM facilities WHERE active = 1 ORDER BY sort ASC LIMIT 8');
$doctors = db_all('SELECT * FROM doctors WHERE active = 1 ORDER BY sort ASC LIMIT 4');
$docSchedules = get_schedules_map(array_column($doctors, 'id'));
$news = db_all("SELECT p.*, c.name AS category_name FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE p.status = 'published' AND p.published_at <= NOW() ORDER BY p.published_at DESC LIMIT 3");
$partners = db_all('SELECT * FROM partners WHERE active = 1 ORDER BY sort ASC');

include __DIR__ . '/includes/header.php';
?>

<?php if ($slides): ?>
<section class="hero-carousel" id="home">
  <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
    <div class="carousel-indicators">
      <?php foreach ($slides as $i => $s): ?>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-label="<?= e(t('hero.slide', [$i + 1])) ?>"></button>
      <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
      <?php foreach ($slides as $i => $s): ?>
        <?php $bg = $s['image'] ? img_url('heroes', $s['image']) : ''; ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>" <?= $bg ? 'style="background-image:url(\'' . e($bg) . '\')"' : '' ?>>
          <div class="hero-shape" style="width:140px;height:140px;top:18%;left:8%;animation-delay:0s"></div>
          <div class="hero-shape" style="width:90px;height:90px;bottom:22%;left:44%;animation-delay:2s"></div>
          <div class="hero-shape" style="width:60px;height:60px;top:28%;right:14%;animation-delay:4s"></div>
          <div class="container">
            <div class="hero-caption col-lg-8">
              <span class="hero-tag"><i class="bi bi-heart-pulse-fill"></i><?= e(setting('site_name')) ?></span>
              <h1><?= e($s['title']) ?></h1>
              <?php if ($s['subtitle']): ?><p><?= e($s['subtitle']) ?></p><?php endif; ?>
              <?php if ($s['btn_text'] && $s['btn_link']): ?>
                <a class="btn btn-grad" href="<?= e($s['btn_link']) ?>"><?= e($s['btn_text']) ?> <i class="bi bi-arrow-right ms-1"></i></a>
              <?php else: ?>
                <a class="btn btn-grad" href="<?= e(base_url('contact.php')) ?>"><?= e(t('hero.contact')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden"><?= e(t('hero.prev')) ?></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden"><?= e(t('hero.next')) ?></span>
    </button>
  </div>
</section>
<?php endif; ?>

<!-- Welcome / About -->
<section class="section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="about-img-wrap position-relative">
          <?php $aboutImg = setting('home_about_image') ? img_url('general', setting('home_about_image')) : base_url('assets/img/post-placeholder.svg'); ?>
          <img src="<?= e($aboutImg) ?>" alt="<?= e(setting('home_about_title')) ?>" style="min-height:420px">
          <div class="experience-badge">
            <div class="num"><?= e(setting('stat_years', '25')) ?>+</div>
            <div class="lbl"><?= e(t('home.about.serving')) ?></div>
          </div>
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
        <span class="section-eyebrow"><?= e(t('home.about.eyebrow')) ?></span>
        <h2 class="section-title"><?= e(setting('home_about_title')) ?></h2>
        <p class="section-sub"><?= nl2br_e(setting('home_about_text')) ?></p>
        <div class="row g-3 mt-3">
          <div class="col-sm-6">
            <div class="d-flex align-items-center gap-3 p-3 rounded-4" style="background:var(--grad-soft)">
              <div class="icon-wrap-sm" style="color:var(--primary)"><i class="bi bi-heart-pulse-fill fs-4"></i></div>
              <div>
                <div class="fw-bold fs-4 lh-1"><?= e(setting('stat_patients', '0')) ?>+</div>
                <small class="text-muted"><?= e(t('home.about.patients')) ?></small>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="d-flex align-items-center gap-3 p-3 rounded-4" style="background:var(--grad-soft)">
              <div style="color:var(--primary)"><i class="bi bi-person-badge-fill fs-4"></i></div>
              <div>
                <div class="fw-bold fs-4 lh-1"><?= e(setting('stat_doctors', '0')) ?>+</div>
                <small class="text-muted"><?= e(t('home.about.doctors')) ?></small>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="d-flex align-items-center gap-3 p-3 rounded-4" style="background:var(--grad-soft)">
              <div style="color:var(--primary)"><i class="bi bi-buildings-fill fs-4"></i></div>
              <div>
                <div class="fw-bold fs-4 lh-1"><?= e(setting('stat_departments', '0')) ?>+</div>
                <small class="text-muted"><?= e(t('home.about.departments')) ?></small>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="d-flex align-items-center gap-3 p-3 rounded-4" style="background:var(--grad-soft)">
              <div style="color:var(--primary)"><i class="bi bi-award-fill fs-4"></i></div>
              <div>
                <div class="fw-bold fs-4 lh-1"><?= e(t('home.about.accredited')) ?></div>
                <small class="text-muted"><?= e(t('home.about.accredited_sub')) ?></small>
              </div>
            </div>
          </div>
        </div>
        <a href="<?= e(base_url('about.php')) ?>" class="btn btn-grad mt-4"><?= e(t('home.about.readmore')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- Services -->
<section class="section section-soft">
  <div class="container">
    <div class="text-center" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('home.services.eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('home.services.title')) ?></h2>
      <p class="section-sub mx-auto"><?= e(t('home.services.sub')) ?></p>
    </div>
    <div class="row g-4 mt-3">
      <?php foreach ($services as $svc): ?>
      <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="<?= ((int)$svc['sort'] % 4) * 80 ?>">
        <a class="icon-card icon-card-link" href="<?= e(base_url('detail.php?type=service&id=' . $svc['id'])) ?>">
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
<section class="section">
  <div class="container">
    <div class="text-center" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('home.facilities.eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('home.facilities.title')) ?></h2>
      <p class="section-sub mx-auto"><?= e(t('home.facilities.sub')) ?></p>
    </div>
    <div class="row g-4 mt-3">
      <?php foreach ($facilities as $fac): ?>
      <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="<?= ((int)$fac['sort'] % 4) * 80 ?>">
        <a class="icon-card icon-card-link" href="<?= e(base_url('detail.php?type=facility&id=' . $fac['id'])) ?>">
          <span class="icon-wrap"><i class="<?= e($fac['icon'] ?: 'bi bi-building') ?>"></i></span>
          <h5><?= e($fac['title']) ?></h5>
          <p><?= e($fac['description']) ?></p>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Stats -->
<section class="section pt-0">
  <div class="container" data-aos="fade-up">
    <div class="stats-section">
      <div class="row g-4">
        <div class="col-6 col-lg-3">
          <div class="stat-item">
            <div class="stat-num" data-counter="<?= (int)setting('stat_patients', '0') ?>">0</div>
            <div class="stat-label"><?= e(t('home.stats.patients')) ?></div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-item">
            <div class="stat-num" data-counter="<?= (int)setting('stat_doctors', '0') ?>">0</div>
            <div class="stat-label"><?= e(t('home.stats.doctors')) ?></div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-item">
            <div class="stat-num" data-counter="<?= (int)setting('stat_departments', '0') ?>">0</div>
            <div class="stat-label"><?= e(t('home.stats.departments')) ?></div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-item">
            <div class="stat-num" data-counter="<?= (int)setting('stat_years', '0') ?>" data-suffix="+">0</div>
            <div class="stat-label"><?= e(t('home.stats.years')) ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Doctors -->
<section class="section section-light">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4" data-aos="fade-up">
      <div>
        <span class="section-eyebrow"><?= e(t('home.doctors.eyebrow')) ?></span>
        <h2 class="section-title mb-0"><?= e(t('home.doctors.title')) ?></h2>
      </div>
      <a href="<?= e(base_url('doctors.php')) ?>" class="btn btn-outline-primary-round btn-sm"><?= e(t('home.doctors.all')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4">
      <?php foreach ($doctors as $doc): ?>
      <?php $photo = $doc['photo'] ? img_url('doctors', $doc['photo']) : base_url('assets/img/doctor-placeholder.svg'); ?>
      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ((int)$doc['sort'] % 4) * 80 ?>">
        <div class="doc-card">
          <div class="doc-photo-wrap"><img class="doc-photo" src="<?= e($photo) ?>" alt="<?= e($doc['name']) ?>"></div>
          <div class="doc-body">
            <h5 class="doc-name"><?= e($doc['name']) ?></h5>
            <div class="doc-spec"><?= e($doc['specialist']) ?></div>
            <?php $dComp = schedule_compact($docSchedules[$doc['id']] ?? []); ?>
            <?php if ($dComp): ?>
            <div class="doc-sched"><i class="bi bi-clock"></i><?= e($dComp) ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- News -->
<section class="section">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4" data-aos="fade-up">
      <div>
        <span class="section-eyebrow"><?= e(t('home.news.eyebrow')) ?></span>
        <h2 class="section-title mb-0"><?= e(t('home.news.title')) ?></h2>
      </div>
      <a href="<?= e(base_url('news.php')) ?>" class="btn btn-outline-primary-round btn-sm"><?= e(t('home.news.all')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4">
      <?php foreach ($news as $post): ?>
      <?php $pImg = $post['image'] ? img_url('posts', $post['image']) : base_url('assets/img/post-placeholder.svg'); ?>
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $post['id'] % 3 * 100 ?>">
        <div class="news-card">
          <div class="news-img-wrap"><img class="news-img" src="<?= e($pImg) ?>" alt="<?= e($post['title']) ?>"></div>
          <div class="news-body">
            <div class="news-meta">
              <?php if ($post['category_name']): ?><span class="chip"><?= e($post['category_name']) ?></span><?php endif; ?>
              <span><i class="bi bi-calendar3"></i><?= e(format_date($post['published_at'])) ?></span>
            </div>
            <h5><a href="<?= e(base_url('news-detail.php?id=' . $post['id'])) ?>"><?= e($post['title']) ?></a></h5>
            <p><?= e(truncate($post['excerpt'] ?: $post['content'], 110)) ?></p>
            <a class="read-more" href="<?= e(base_url('news-detail.php?id=' . $post['id'])) ?>"><?= e(t('news.readmore')) ?> <i class="bi bi-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Partners -->
<section class="section section-light pb-0">
  <div class="container">
    <div class="text-center mb-4" data-aos="fade-up">
      <span class="section-eyebrow"><?= e(t('home.partners.eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('home.partners.title')) ?></h2>
    </div>
  </div>
  <?php if ($partners): ?>
  <div class="partners-strip" data-aos="fade-up">
    <div class="partners-track">
      <?php for ($r = 0; $r < 2; $r++): ?>
        <?php foreach ($partners as $pt): ?>
          <a class="partner-logo" href="<?= e($pt['website'] ?: '#') ?>" target="_blank" rel="noopener">
            <?php if ($pt['logo']): ?><img src="<?= e(img_url('partners', $pt['logo'])) ?>" alt="<?= e($pt['name']) ?>"><?php else: ?><?= e($pt['name']) ?><?php endif; ?>
          </a>
        <?php endforeach; ?>
      <?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>
</section>

<!-- CTA -->
<section class="section">
  <div class="container" data-aos="zoom-in-up">
    <div class="cta-banner">
      <div class="row align-items-center g-4 position-relative">
        <div class="col-lg-8">
          <h2><?= e(t('home.cta.title')) ?></h2>
          <p class="mb-0"><?= e(t('home.cta.sub')) ?></p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <?php if (register_url()): ?>
          <a href="<?= e(register_url()) ?>" target="_blank" rel="noopener" class="btn btn-light btn-lg rounded-pill fw-bold px-4" style="color:var(--primary)"><i class="bi bi-calendar-check me-2"></i><?= e(t('home.cta.register')) ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
