<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Dashboard';
$section = 'dashboard';

$stats = [
    'posts'      => (int)db_one("SELECT COUNT(*) AS c FROM posts")['c'],
    'doctors'    => (int)db_one("SELECT COUNT(*) AS c FROM doctors")['c'],
    'services'   => (int)db_one("SELECT COUNT(*) AS c FROM services")['c'],
    'facilities' => (int)db_one("SELECT COUNT(*) AS c FROM facilities")['c'],
    'careers'    => (int)db_one("SELECT COUNT(*) AS c FROM careers WHERE status = 'open'")['c'],
    'partners'   => (int)db_one("SELECT COUNT(*) AS c FROM partners")['c'],
    'messages'   => (int)db_one("SELECT COUNT(*) AS c FROM messages")['c'],
    'unread'     => (int)db_one("SELECT COUNT(*) AS c FROM messages WHERE is_read = 0")['c'],
];

$recentMessages = db_all('SELECT * FROM messages ORDER BY created_at DESC LIMIT 5');
$recentPosts = db_all("SELECT id, title, published_at, status FROM posts ORDER BY created_at DESC LIMIT 5");

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3><?= $stats['posts'] ?></h3>
        <p>Artikel &amp; Berita</p>
      </div>
      <div class="icon"><i class="fas fa-newspaper"></i></div>
      <a href="posts.php" class="small-box-footer">Kelola <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-teal">
      <div class="inner">
        <h3><?= $stats['doctors'] ?></h3>
        <p>Dokter</p>
      </div>
      <div class="icon"><i class="fas fa-user-md"></i></div>
      <a href="doctors.php" class="small-box-footer">Kelola <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3><?= $stats['services'] ?></h3>
        <p>Layanan</p>
      </div>
      <div class="icon"><i class="fas fa-stethoscope"></i></div>
      <a href="services.php" class="small-box-footer">Kelola <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3><?= $stats['facilities'] ?></h3>
        <p>Fasilitas</p>
      </div>
      <div class="icon"><i class="fas fa-hospital"></i></div>
      <a href="facilities.php" class="small-box-footer">Kelola <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3><?= $stats['careers'] ?></h3>
        <p>Lowongan Terbuka</p>
      </div>
      <div class="icon"><i class="fas fa-briefcase"></i></div>
      <a href="careers.php" class="small-box-footer">Kelola <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-secondary">
      <div class="inner">
        <h3><?= $stats['partners'] ?></h3>
        <p>Rekanan</p>
      </div>
      <div class="icon"><i class="fas fa-handshake"></i></div>
      <a href="partners.php" class="small-box-footer">Kelola <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3><?= $stats['messages'] ?></h3>
        <p>Pesan Masuk</p>
      </div>
      <div class="icon"><i class="fas fa-envelope"></i></div>
      <a href="messages.php" class="small-box-footer">Lihat <?= $stats['unread'] > 0 ? '(' . $stats['unread'] . ' belum dibaca)' : '' ?> <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box" style="background:linear-gradient(135deg,#0a7d8c,#f4a261);color:#fff">
      <div class="inner">
        <h3>Open</h3>
        <p>Lihat Website</p>
      </div>
      <div class="icon"><i class="fas fa-globe"></i></div>
      <a href="<?= e(base_url('index.php')) ?>" target="_blank" class="small-box-footer" style="background:rgba(0,0,0,.2);color:#fff">Buka <i class="fas fa-external-link-alt"></i></a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-envelope-open-text mr-2 text-primary"></i>Pesan Masuk Terbaru</h3>
        <div class="card-tools"><a href="messages.php" class="btn btn-tool"><i class="fas fa-arrow-right"></i></a></div>
      </div>
      <div class="card-body p-0">
        <?php if ($recentMessages): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr><th>Nama</th><th>Subjek</th><th>Tanggal</th><th></th></tr>
            </thead>
            <tbody>
              <?php foreach ($recentMessages as $m): ?>
              <tr>
                <td class="font-weight-bold"><?= e($m['name']) ?><?= $m['is_read'] ? '' : ' <span class="badge badge-danger">Baru</span>' ?></td>
                <td><?= e($m['subject'] ?: '-') ?></td>
                <td class="text-muted small"><?= e(format_date_id($m['created_at'])) ?></td>
                <td class="text-right">
                  <a href="messages.php?view=<?= $m['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada pesan masuk.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-newspaper mr-2 text-primary"></i>Artikel Terbaru</h3>
        <div class="card-tools"><a href="posts.php" class="btn btn-tool"><i class="fas fa-arrow-right"></i></a></div>
      </div>
      <div class="card-body p-0">
        <?php if ($recentPosts): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr><th>Judul</th><th>Status</th><th>Tanggal</th></tr>
            </thead>
            <tbody>
              <?php foreach ($recentPosts as $p): ?>
              <tr>
                <td class="font-weight-bold"><?= e(truncate($p['title'], 40)) ?></td>
                <td>
                  <?php if ($p['status'] === 'published'): ?>
                    <span class="badge badge-soft-success">Terbit</span>
                  <?php else: ?>
                    <span class="badge badge-soft-danger">Draf</span>
                  <?php endif; ?>
                </td>
                <td class="text-muted small"><?= e(format_date_id($p['published_at'])) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada artikel.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
