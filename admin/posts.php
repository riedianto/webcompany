<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
auth_check();

$pageTitle = 'Artikel & Berita';
$section = 'posts';
$breadcrumbs = [['label' => 'Artikel & Berita', 'url' => '']];

$id = (int)($_GET['id'] ?? 0);
$edit = null;
if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $edit = db_one('SELECT * FROM posts WHERE id = ?', [$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? 'save');
    $rid = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $row = db_one('SELECT * FROM posts WHERE id = ?', [$rid]);
        if ($row) {
            delete_uploaded('posts', $row['image']);
            db_exec('DELETE FROM posts WHERE id = ?', [$rid]);
            flash('success', 'Artikel berhasil dihapus.');
        }
        redirect(base_url('admin/posts.php'));
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    $categoryId = (int)($_POST['category_id'] ?? 0) ?: null;
    $excerpt = trim((string)($_POST['excerpt'] ?? ''));
    $content = (string)($_POST['content'] ?? '');
    $author = trim((string)($_POST['author'] ?? ''));
    $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

    $pubInput = trim((string)($_POST['published_at'] ?? ''));
    if ($pubInput !== '') {
        $publishedAt = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $pubInput)));
    } else {
        $publishedAt = date('Y-m-d H:i:s');
    }

    if ($title === '') {
        flash('error', 'Judul artikel wajib diisi.');
    } else {
        $slug = $slug !== '' ? slugify($slug) : slugify($title);
        if ($slug === '') {
            $slug = 'post-' . date('YmdHis');
        }
        try {
            $slug = unique_slug($slug, 'posts', $rid > 0 ? $rid : null);
        } catch (Throwable $e) {
            $slug = slugify($title) . '-' . date('YmdHis');
        }

        $oldImage = $edit['image'] ?? null;
        $up = upload_image('image', 'posts', $oldImage);
        if (!$up['success']) {
            flash('error', $up['error']);
        } else {
            if ($rid > 0) {
                db_exec(
                    'UPDATE posts SET category_id = ?, title = ?, slug = ?, image = ?, excerpt = ?, content = ?, author = ?, published_at = ?, status = ? WHERE id = ?',
                    [$categoryId, $title, $slug, $up['file'], $excerpt, $content, $author, $publishedAt, $status, $rid]
                );
                flash('success', 'Artikel berhasil diperbarui.');
            } else {
                db_exec(
                    'INSERT INTO posts (category_id, title, slug, image, excerpt, content, author, published_at, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [$categoryId, $title, $slug, $up['file'], $excerpt, $content, $author, $publishedAt, $status]
                );
                flash('success', 'Artikel berhasil ditambahkan.');
            }
            redirect(base_url('admin/posts.php'));
        }
    }
}

$rows = db_all('SELECT p.*, c.name AS category_name FROM posts p LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC');
$categories = db_all('SELECT * FROM categories ORDER BY name ASC');

include __DIR__ . '/layout/header.php';
?>

<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><?= $edit ? 'Edit Artikel' : 'Tulis Artikel' ?></h3></div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
          <div class="form-group">
            <label>Judul <span class="text-danger">*</span></label>
            <input type="text" name="title" id="titleField" class="form-control" required value="<?= e($edit['title'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Slug (URL)</label>
            <div class="input-group">
              <input type="text" name="slug" id="slugField" class="form-control" value="<?= e($edit['slug'] ?? '') ?>" data-locked="0">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary" id="lockSlug"><i class="fas fa-unlock"></i></button>
              </div>
            </div>
            <small class="icon-helper">Otomatis dibuat dari judul. Klik gembok untuk mengunci.</small>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control">
                  <option value="">Tanpa Kategori</option>
                  <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>" <?= selected((int)($edit['category_id'] ?? 0) === (int)$cat['id']) ?>><?= e($cat['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="author" class="form-control" value="<?= e($edit['author'] ?? '') ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Terbit</label>
                <input type="datetime-local" name="published_at" class="form-control" value="<?= $edit && $edit['published_at'] ? e(date('Y-m-d\TH:i', strtotime($edit['published_at']))) : e(date('Y-m-d\TH:i')) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                  <option value="published" <?= selected(($edit['status'] ?? 'published') === 'published') ?>>Terbit</option>
                  <option value="draft" <?= selected(($edit['status'] ?? 'published') === 'draft') ?>>Draf</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Ringkasan (excerpt)</label>
            <textarea name="excerpt" class="form-control" rows="2"><?= e($edit['excerpt'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Gambar Sampul</label>
            <input type="file" name="image" class="form-control-file" data-preview="#previewImg" accept="image/*">
            <?php $img = $edit['image'] ?? ''; ?>
            <img id="previewImg" class="img-preview mt-2 <?= $img ? '' : 'd-none' ?>" src="<?= $img ? e(img_url('posts', $img)) : '' ?>" alt="Preview">
          </div>
          <div class="form-group">
            <label>Isi Konten</label>
            <textarea name="content" class="form-control" rows="12" placeholder="Tulis isi artikel di sini... (boleh menggunakan HTML sederhana: &lt;p&gt;, &lt;b&gt;, &lt;ul&gt; dsb.)"><?= e($edit['content'] ?? '') ?></textarea>
          </div>
          <button type="submit" class="btn btn-accent"><i class="fas fa-save mr-2"></i><?= $edit ? 'Simpan Perubahan' : 'Terbitkan' ?></button>
          <?php if ($edit): ?><a href="posts.php" class="btn btn-outline-secondary">Batal</a><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Artikel</h3>
        <div class="card-tools"><span class="badge badge-soft-info"><?= count($rows) ?> data</span></div>
      </div>
      <div class="card-body p-0">
        <?php if ($rows): ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Terbit</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
              <tr>
                <td class="font-weight-bold" style="max-width:280px"><?= e(truncate($r['title'], 45)) ?></td>
                <td><?= e($r['category_name'] ?? '-') ?></td>
                <td>
                  <?php if ($r['status'] === 'published'): ?><span class="badge badge-soft-success">Terbit</span>
                  <?php else: ?><span class="badge badge-soft-danger">Draf</span><?php endif; ?>
                </td>
                <td class="text-muted small"><?= e(format_date_id($r['published_at'])) ?></td>
                <td class="text-right">
                  <a href="<?= e(base_url('news-detail.php?id=' . $r['id'])) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat"><i class="fas fa-eye"></i></a>
                  <a href="posts.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></a>
                  <form method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger js-confirm" data-confirm="Hapus artikel ini?"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
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

<script>
$(function () {
  var locked = false;
  $('#lockSlug').on('click', function () {
    locked = !locked;
    $('#slugField').data('locked', locked ? '1' : '0');
    $(this).find('i').toggleClass('fa-unlock fa-lock');
  });
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
