<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = t('nav.contact');
$active = 'contact';

$form = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $form['name']    = trim((string)($_POST['name'] ?? ''));
    $form['email']   = trim((string)($_POST['email'] ?? ''));
    $form['phone']   = trim((string)($_POST['phone'] ?? ''));
    $form['subject'] = trim((string)($_POST['subject'] ?? ''));
    $form['message'] = trim((string)($_POST['message'] ?? ''));

    if ($form['name'] === '') {
        $errors[] = t('contact.err.name');
    }
    if ($form['phone'] === '') {
        $errors[] = t('contact.err.phone');
    } elseif (strlen((string)preg_replace('/\D+/', '', $form['phone'])) < 8) {
        $errors[] = t('contact.err.phone.format');
    }
    if ($form['subject'] === '' || !in_array($form['subject'], contact_subjects(), true)) {
        $errors[] = t('contact.err.subject');
    }
    if ($form['email'] !== '' && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = t('contact.err.email');
    }
    if ($form['message'] === '') {
        $errors[] = t('contact.err.message');
    }

    if (!$errors) {
        db_exec(
            'INSERT INTO messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)',
            [$form['name'], $form['email'], $form['phone'], $form['subject'], $form['message']]
        );
        flash('success', t('contact.success'));
        redirect(base_url('contact.php'));
    } else {
        flash('error', implode(' ', $errors));
    }
}

include __DIR__ . '/includes/header.php';

$bannerTitle = t('contact.banner');
$bannerBg = setting('banner_contact') ? img_url('general', setting('banner_contact')) : '';
$crumbs = [
    ['label' => t('banner.home'), 'url' => base_url('index.php')],
    ['label' => t('nav.contact'), 'url' => ''],
];
include __DIR__ . '/includes/sections/page_banner.php';

$flashMsg = flash_get();
$subjects = contact_subjects();
?>

<section class="section">
  <div class="container">
    <?php if ($flashMsg): ?>
    <div class="alert alert-<?= $flashMsg['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show shadow" role="alert">
      <?= e($flashMsg['msg']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= e(t('contact.close')) ?>"></button>
    </div>
    <?php endif; ?>

    <div class="row g-4 mb-5">
      <div class="col-md-6 col-lg-3" data-aos="fade-up">
        <div class="contact-card">
          <span class="cc-icon"><i class="bi bi-geo-alt-fill"></i></span>
          <div>
            <h6 class="mb-1"><?= e(t('contact.address')) ?></h6>
            <p class="mb-0 small" style="color:var(--muted)"><?= e(setting_en('site_address', setting('site_address'))) ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
        <div class="contact-card">
          <span class="cc-icon"><i class="bi bi-telephone-fill"></i></span>
          <div>
            <h6 class="mb-1"><?= e(t('contact.phone')) ?></h6>
            <p class="mb-0 small" style="color:var(--muted)"><?= e(setting('site_phone')) ?><br><?= setting('site_whatsapp') ? 'WA: +' . e(setting('site_whatsapp')) : '' ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
        <div class="contact-card">
          <span class="cc-icon"><i class="bi bi-envelope-fill"></i></span>
          <div>
            <h6 class="mb-1"><?= e(t('contact.email')) ?></h6>
            <p class="mb-0 small" style="color:var(--muted)"><?= e(setting('site_email')) ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
        <div class="contact-card">
          <span class="cc-icon"><i class="bi bi-clock-fill"></i></span>
          <div>
            <h6 class="mb-1"><?= e(t('contact.hours')) ?></h6>
            <p class="mb-0 small" style="color:var(--muted)"><?= e(setting_en('site_hours')) ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-lg-6" data-aos="fade-right">
        <span class="section-eyebrow"><?= e(t('contact.send.eyebrow')) ?></span>
        <h2 class="section-title mb-4"><?= e(t('contact.send.title')) ?></h2>
        <form method="post" action="<?= e(base_url('contact.php')) ?>" novalidate>
          <?= csrf_field() ?>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold"><?= e(t('contact.form.name')) ?> <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" value="<?= e($form['name']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><?= e(t('contact.form.email')) ?></label>
              <input type="email" name="email" class="form-control" value="<?= e($form['email']) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><?= e(t('contact.form.phone')) ?> <span class="text-danger">*</span></label>
              <input type="tel" name="phone" class="form-control" inputmode="tel" placeholder="<?= e(t('contact.form.phone_placeholder')) ?>" value="<?= e($form['phone']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><?= e(t('contact.form.subject')) ?> <span class="text-danger">*</span></label>
              <select name="subject" class="form-select" required>
                <option value="" <?= $form['subject'] === '' ? 'selected' : '' ?>><?= e(t('contact.form.subject_placeholder')) ?></option>
                <?php foreach ($subjects as $s): ?>
                <option value="<?= e($s) ?>" <?= $form['subject'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold"><?= e(t('contact.form.message')) ?> <span class="text-danger">*</span></label>
              <textarea name="message" class="form-control" rows="5" required><?= e($form['message']) ?></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-grad"><i class="bi bi-send me-1"></i><?= e(t('contact.send_btn')) ?></button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
        <?php if (setting('site_maps')): ?>
        <iframe class="map-frame" src="<?= e(setting('site_maps')) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?= e(t('contact.map_title')) ?>"></iframe>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
