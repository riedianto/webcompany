<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$id = (int)($_GET['id'] ?? 0);
$post = db_one("SELECT p.*, c.name AS category_name, c.id AS category_id FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE p.id = ? AND p.status = 'published' AND p.published_at <= NOW()", [$id]);

if (!$post) {
    redirect(base_url('news.php'));
}

$pageTitle = $post['title'];
$active = 'news';

$recent = db_all("SELECT id, title, published_at FROM posts WHERE status = 'published' AND published_at <= NOW() AND id <> ? ORDER BY published_at DESC LIMIT 5", [$id]);
$categories = db_all('SELECT * FROM categories ORDER BY name ASC');

include __DIR__ . '/includes/header.php';

$pImg = $post['image'] ? img_url('posts', $post['image']) : base_url('assets/img/post-placeholder.svg');

$bannerTitle = t('newsdetail.banner');
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('news.banner'), 'url' => base_url('news.php')],
    ['label' => t('newsdetail.detail'), 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';
?>

<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8" data-aos="fade-up">
        <div class="news-meta mb-3">
          <?php if ($post['category_name']): ?><span class="chip"><?= e($post['category_name']) ?></span><?php endif; ?>
          <span class="ms-2 text-muted small"><i class="bi bi-calendar3 me-1"></i><?= e(format_date($post['published_at'])) ?></span>
          <?php if ($post['author']): ?>
          <span class="ms-3 text-muted small"><i class="bi bi-person me-1"></i><?= e($post['author']) ?></span>
          <?php endif; ?>
        </div>
        <h1 class="mb-4" style="font-size:clamp(1.6rem,3vw,2.3rem)"><?= e($post['title']) ?></h1>
        <div class="rounded-4 overflow-hidden mb-4">
          <img src="<?= e($pImg) ?>" alt="<?= e($post['title']) ?>" style="width:100%;max-height:420px;object-fit:cover">
        </div>
        <?php if ($post['excerpt']): ?>
        <div class="lead fw-semibold mb-4" style="color:var(--primary)"><?= e($post['excerpt']) ?></div>
        <?php endif; ?>
        <div class="article-content" style="color:var(--body-text);font-size:1.02rem;line-height:1.9">
          <?= $post['content'] ?>
        </div>

        <div class="d-flex align-items-center gap-3 p-4 rounded-4 mt-5" style="background:var(--grad-soft)">
          <div>
            <h6 class="mb-1"><?= e(t('newsdetail.cta.title')) ?></h6>
            <p class="mb-0 small" style="color:var(--muted)"><?= e(t('newsdetail.cta.sub')) ?></p>
          </div>
          <a href="<?= e(base_url('contact.php')) ?>" class="btn btn-grad ms-auto"><?= e(t('newsdetail.contact')) ?></a>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="sticky-top" style="top:120px" data-aos="fade-left">
          <div class="p-4 rounded-4 border surface-card mb-4" style="border-color:var(--border)!important">
            <h5 class="mb-3"><i class="bi bi-newspaper me-2" style="color:var(--primary)"></i><?= e(t('newsdetail.recent')) ?></h5>
            <?php if ($recent): ?>
              <?php foreach ($recent as $r): ?>
              <a href="<?= e(base_url('news-detail.php?id=' . $r['id'])) ?>" class="d-block py-2 border-bottom" style="border-color:var(--border-soft)!important">
                <span class="fw-semibold d-block" style="color:var(--ink)"><?= e($r['title']) ?></span>
                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?= e(format_date($r['published_at'])) ?></small>
              </a>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted small mb-0"><?= e(t('newsdetail.none')) ?></p>
            <?php endif; ?>
          </div>
          <div class="p-4 rounded-4 border surface-card" style="border-color:var(--border)!important">
            <h5 class="mb-3"><i class="bi bi-tags me-2" style="color:var(--primary)"></i><?= e(t('newsdetail.categories')) ?></h5>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= e(base_url('news.php?category=' . $cat['id'])) ?>" class="chip text-decoration-none mb-2 me-1 d-inline-block"><?= e($cat['name']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
