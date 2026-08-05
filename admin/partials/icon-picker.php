<?php
declare(strict_types=1);
$biIcons = require __DIR__ . '/bootstrap-icons.php';
?>
<div class="modal fade" id="biIconModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-icons mr-2"></i>Pilih Ikon Bootstrap</h5>
        <div class="ml-3 flex-grow-1">
          <div class="input-group input-group-sm">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
            <input type="text" id="biIconSearch" class="form-control" placeholder="Cari ikon... (contoh: heart, hospital, droplet)">
            <div class="input-group-append"><span class="input-group-text" id="biIconCount"></span></div>
          </div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="biIconGrid" class="bi-icon-grid"></div>
        <div id="biIconEmpty" class="text-center text-muted py-5 d-none">Tidak ada ikon yang cocok.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<script>
window.BI_ICONS = <?= json_encode($biIcons, JSON_UNESCAPED_SLASHES) ?>;
</script>
