<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.news');
$active = 'news';

$catId = (int)($_GET['category'] ?? 0);
$search = trim($_GET['q'] ?? '');

$params = [];
$where = "WHERE p.status = 'published' AND p.published_at <= NOW()";
if ($catId > 0) {
    $where .= ' AND p.category_id = ?';
    $params[] = $catId;
}
if ($search !== '') {
    $where .= ' AND (p.title LIKE ? OR p.excerpt LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$result = paginate(
    'SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM posts p LEFT JOIN categories c ON c.id = p.category_id ' . $where . ' ORDER BY p.published_at DESC',
    $params,
    6
);
$posts = $result['items'];
$categories = db_all('SELECT * FROM categories ORDER BY name ASC');

include __DIR__ . '/includes/header.php';

$bannerTitle = t('news.banner');
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('news.banner'), 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';
?>

<section class="section">
  <div class="container">
    <form method="get" action="<?= e(base_url('news.php')) ?>" class="row justify-content-center mb-4 g-2" data-aos="fade-up">
      <div class="col-md-6 col-lg-5">
        <input type="text" name="q" class="form-control" placeholder="<?= e(t('news.search_placeholder')) ?>" value="<?= e($search) ?>">
      </div>
      <div class="col-auto">
        <button class="btn btn-grad" type="submit"><i class="bi bi-search me-1"></i><?= e(t('news.search')) ?></button>
      </div>
    </form>

    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" data-aos="fade-up">
      <a href="<?= e(base_url('news.php')) ?>" class="btn btn-sm <?= $catId === 0 ? 'btn-grad' : 'btn-outline-primary-round' ?>"><?= e(t('news.all')) ?></a>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= e(base_url('news.php?category=' . $cat['id'])) ?>" class="btn btn-sm <?= $catId === (int)$cat['id'] ? 'btn-grad' : 'btn-outline-primary-round' ?>"><?= e($cat['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="row g-4">
      <?php if ($posts): ?>
        <?php foreach ($posts as $post): ?>
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
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <i class="bi bi-newspaper fs-1 d-block mb-3" style="color:var(--primary)"></i>
          <h4><?= e(t('news.empty.title')) ?></h4>
          <p class="text-muted"><?= e(t('news.empty.sub')) ?></p>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($result['total_pages'] > 1): ?>
    <nav class="mt-5" aria-label="<?= e(t('news.pagination')) ?>">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
        <li class="page-item <?= $i === $result['page'] ? 'active' : '' ?>">
          <a class="page-link" href="<?= e(base_url('news.php?page=' . $i . ($catId ? '&category=' . $catId : '') . ($search ? '&q=' . urlencode($search) : ''))) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
      </ul>
    </nav>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
