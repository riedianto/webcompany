<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Pesan Masuk';
$section = 'messages';
$breadcrumbs = [['label' => 'Pesan Masuk', 'url' => '']];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? '');
    $mid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete' && $mid > 0) {
        db_exec('DELETE FROM messages WHERE id = ?', [$mid]);
        flash('success', 'Pesan berhasil dihapus.');
    } elseif ($action === 'read_all') {
        db_exec('UPDATE messages SET is_read = 1 WHERE is_read = 0');
        flash('success', 'Semua pesan ditandai sudah dibaca.');
    }
    redirect(base_url('admin/messages.php'));
}

$viewId = (int)($_GET['view'] ?? 0);
$view = null;
if ($viewId > 0) {
    $view = db_one('SELECT * FROM messages WHERE id = ?', [$viewId]);
    if ($view && !$view['is_read']) {
        db_exec('UPDATE messages SET is_read = 1 WHERE id = ?', [$viewId]);
        $view['is_read'] = 1;
    }
}

$rows = db_all('SELECT * FROM messages ORDER BY is_read ASC, created_at DESC');
$unread = (int)db_one('SELECT COUNT(*) AS c FROM messages WHERE is_read = 0')['c'];

include __DIR__ . '/layout/header.php';
?>

<?php if ($view): ?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-envelope-open-text mr-2 text-primary"></i><?= e($view['subject'] ?: 'Pesan') ?></h3>
    <div class="card-tools">
      <a href="messages.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-3"><strong>Nama</strong><br><?= e($view['name']) ?></div>
      <div class="col-md-3"><strong>Email</strong><br><?= $view['email'] ? '<a href="mailto:' . e($view['email']) . '">' . e($view['email']) . '</a>' : '-' ?></div>
      <div class="col-md-3"><strong>Telepon</strong><br><?= e($view['phone'] ?: '-') ?></div>
      <div class="col-md-3"><strong>Tanggal</strong><br><?= e(format_date_id($view['created_at'])) ?></div>
    </div>
    <hr class="sep">
    <div style="white-space:pre-wrap;line-height:1.7"><?= e($view['message']) ?></div>
    <div class="mt-4">
      <a href="mailto:<?= e($view['email']) ?>" class="btn btn-accent btn-sm"><i class="fas fa-reply mr-1"></i>Balas via Email</a>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Semua Pesan</h3>
    <div class="card-tools">
      <?php if ($unread > 0): ?>
      <form method="post" class="d-inline mr-2">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="read_all">
        <button type="submit" class="btn btn-sm btn-outline-accent"><i class="fas fa-check-double mr-1"></i>Tandai Semua Dibaca</button>
      </form>
      <?php endif; ?>
      <span class="badge badge-soft-info"><?= count($rows) ?> pesan · <?= $unread ?> belum dibaca</span>
    </div>
  </div>
  <div class="card-body p-0">
    <?php if ($rows): ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Nama</th><th>Subjek</th><th>Kontak</th><th>Tanggal</th><th class="text-right">Aksi</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $m): ?>
          <tr style="<?= $m['is_read'] ? '' : 'background:#f0fafc' ?>">
            <td class="font-weight-bold"><?= e($m['name']) ?><?= $m['is_read'] ? '' : ' <span class="badge badge-danger">Baru</span>' ?></td>
            <td><?= e($m['subject'] ?: '-') ?></td>
            <td class="small text-muted"><?= e($m['email'] ?: $m['phone']) ?></td>
            <td class="small text-muted"><?= e(format_date_id($m['created_at'])) ?></td>
            <td class="text-right">
              <a href="messages.php?view=<?= $m['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
              <form method="post" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus pesan ini?"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="text-center text-muted py-5">
      <i class="fas fa-envelope-open fs-1 d-block mb-3"></i>
      Belum ada pesan masuk.
    </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
