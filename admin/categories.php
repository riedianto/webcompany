<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Kategori';
$section = 'categories';
$breadcrumbs = [['label' => 'Kategori', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM categories WHERE id = ?', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        db_exec('DELETE FROM categories WHERE id = ?', [$rid]);
        flash('success', 'Kategori berhasil dihapus.');
        redirect(base_url('admin/categories.php'));
    }

    $name = trim((string)($_POST['name'] ?? ''));
    $nameEn = trim((string)($_POST['name_en'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));

    if ($name === '') {
        flash('error', 'Nama kategori wajib diisi.');
    } else {
        $slug = $slug !== '' ? slugify($slug) : slugify($name);
        if ($slug === '') {
            $slug = 'kategori-' . date('YmdHis');
        }
        $existing = null;
        if ($rid > 0) {
            $existing = db_one('SELECT id FROM categories WHERE slug = ? AND id <> ?', [$slug, $rid]);
        } else {
            $existing = db_one('SELECT id FROM categories WHERE slug = ?', [$slug]);
        }
        if ($existing) {
            flash('error', 'Slug sudah digunakan. Gunakan nama yang berbeda.');
        } else {
            if ($rid > 0) {
                db_exec('UPDATE categories SET name = ?, name_en = ?, slug = ? WHERE id = ?', [$name, $nameEn, $slug, $rid]);
                flash('success', 'Kategori berhasil diperbarui.');
            } else {
                db_exec('INSERT INTO categories (name, name_en, slug) VALUES (?, ?, ?)', [$name, $nameEn, $slug]);
                flash('success', 'Kategori berhasil ditambahkan.');
            }
            redirect(base_url('admin/categories.php'));
        }
    }
}

$rows = db_all('SELECT c.*, (SELECT COUNT(*) FROM posts p WHERE p.category_id = c.id) AS total_posts FROM categories c ORDER BY name ASC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h3></div>
      <div class="card-body">
        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="name" id="catName" class="form-control" required value="<?= e($edit['name'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Nama Kategori (EN - terjemahan Inggris)</label>
            <div class="input-group">
              <input type="text" name="name_en" id="catNameEn" class="form-control" value="<?= e($edit['name_en'] ?? '') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-accent js-auto-translate" data-src="#catName" data-target="#catNameEn" data-lang="en" title="Terjemahkan otomatis ke Inggris"><i class="fas fa-language mr-1"></i>EN</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Slug (URL)</label>
            <input type="text" name="slug" id="catSlug" class="form-control" value="<?= e($edit['slug'] ?? '') ?>">
            <small class="icon-helper">Otomatis dibuat dari nama.</small>
          </div>
          <button type="submit" class="btn btn-accent"><i class="fas fa-save mr-2"></i><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
          <?php if ($edit): ?><a href="categories.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Kategori</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($rows) ?> data</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($rows): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Nama</th><th>Slug</th><th>Jumlah Artikel</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
              <tr>
                <td class="font-weight-bold"><?= e($r['name']) ?><?php if (empty($r['name_en'])): ?> <span class="badge badge-soft-warning" title="Belum diterjemahkan ke Inggris">EN-</span><?php endif; ?></td>
                <td class="text-muted"><?= e($r['slug']) ?></td>
                <td><span class="badge badge-soft-info"><?= (int)$r['total_posts'] ?></span></td>
                <td class="text-right">
                  <a href="categories.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus kategori ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">Belum ada kategori.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
$(function () {
  var locked = false;
  $('#catSlug').on('keydown', function () { locked = true; });
  $('#catName').on('keyup blur', function () {
    if (!locked) {
      var t = $(this).val().toLowerCase().trim()
        .replace(/&/g, ' dan ').replace(/[^a-z0-9\s-]/g, '').replace(/[\s_]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
      $('#catSlug').val(t);
    }
  });
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
