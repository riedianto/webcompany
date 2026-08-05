<?php
declare(strict_types=1);
?>
<footer class="site-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="d-flex align-items-center mb-3">
          <img src="<?= e(setting('site_logo') ? img_url('logos', setting('site_logo')) : base_url('assets/img/logo.svg')) ?>" alt="<?= e(setting('site_name')) ?>" class="brand-logo surface-card" style="padding:4px">
          <span class="footer-brand-name ms-2"><?= e(setting('site_name')) ?></span>
        </div>
        <p class="small"><?= e(setting('site_description')) ?></p>
        <div class="footer-social mt-3">
          <?php if (setting('site_instagram')): ?><a href="<?= e(setting('site_instagram')) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a><?php endif; ?>
          <?php if (setting('site_facebook')): ?><a href="<?= e(setting('site_facebook')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a><?php endif; ?>
          <?php if (setting('site_youtube')): ?><a href="<?= e(setting('site_youtube')) ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a><?php endif; ?>
          <?php if (setting('site_whatsapp')): ?><a href="https://wa.me/<?= e(setting('site_whatsapp')) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a><?php endif; ?>
        </div>
      </div>
      <div class="col-lg-2 col-6">
        <h6><?= e(t('footer.heading.menu')) ?></h6>
        <a class="footer-link" href="<?= e(base_url('index.php')) ?>"><?= e(t('nav.home')) ?></a>
        <a class="footer-link" href="<?= e(base_url('about.php')) ?>"><?= e(t('nav.about')) ?></a>
        <a class="footer-link" href="<?= e(base_url('services.php')) ?>"><?= e(t('nav.services')) ?></a>
        <a class="footer-link" href="<?= e(base_url('doctors.php')) ?>"><?= e(t('nav.doctors')) ?></a>
        <a class="footer-link" href="<?= e(base_url('contact.php')) ?>"><?= e(t('nav.contact')) ?></a>
      </div>
      <div class="col-lg-3 col-6">
        <h6><?= e(t('footer.heading.info')) ?></h6>
        <a class="footer-link" href="<?= e(base_url('news.php')) ?>"><?= e(t('nav.news')) ?></a>
        <a class="footer-link" href="<?= e(base_url('careers.php')) ?>"><?= e(t('nav.careers')) ?></a>
        <a class="footer-link" href="<?= e(base_url('partners.php')) ?>"><?= e(t('nav.partners')) ?></a>
      </div>
      <div class="col-lg-3">
        <h6><?= e(t('footer.heading.contact')) ?></h6>
        <p class="small mb-2"><i class="bi bi-geo-alt-fill me-2" style="color:var(--secondary)"></i><?= e(setting('site_address')) ?></p>
        <p class="small mb-2"><i class="bi bi-telephone-fill me-2" style="color:var(--secondary)"></i><?= e(setting('site_phone')) ?></p>
        <p class="small mb-2"><i class="bi bi-envelope-fill me-2" style="color:var(--secondary)"></i><?= e(setting('site_email')) ?></p>
        <p class="small mb-0"><i class="bi bi-clock-fill me-2" style="color:var(--secondary)"></i><?= e(setting('site_hours')) ?></p>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container d-flex flex-wrap justify-content-center gap-2">
      <span>&copy; <?= date('Y') ?> <?= e(setting('site_name')) ?>. <?= e(t('footer.copyright')) ?></span>
    </div>
  </div>
</footer>

<?php if (setting('site_whatsapp')): ?>
<a id="waFloat" href="https://wa.me/<?= e(setting('site_whatsapp')) ?>?text=<?= e(rawurlencode(t('wa.message'))) ?>" target="_blank" rel="noopener" aria-label="<?= e(t('wa.title')) ?>" title="<?= e(t('wa.title')) ?>">
  <i class="bi bi-whatsapp"></i>
</a>
<?php endif; ?>

<button id="backToTop" aria-label="<?= e(t('footer.backtotop')) ?>"><i class="bi bi-arrow-up"></i></button>

<div class="modal fade gallery-lightbox" id="galleryLightbox" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="galleryLbTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0 position-relative">
        <img id="galleryLbImage" src="" alt="">
        <div class="gallery-lb-controls">
          <button type="button" class="btn btn-light" id="galleryLbPrev" aria-label="<?= e(t('hero.prev')) ?>"><i class="bi bi-chevron-left"></i></button>
          <button type="button" class="btn btn-light" id="galleryLbNext" aria-label="<?= e(t('hero.next')) ?>"><i class="bi bi-chevron-right"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= e(base_url('assets/vendor/aos/js/aos.js')) ?>"></script>
<script src="<?= e(base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')) ?>"></script>
<script src="<?= e(base_url('assets/js/main.js')) ?>"></script>
</body>
</html>
