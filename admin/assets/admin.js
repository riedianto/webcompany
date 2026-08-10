$(function () {

  // Auto-hide flash alert
  setTimeout(function () {
    $('#adminFlash').fadeOut(400, function () { $(this).remove(); });
  }, 5000);

  // Confirm before delete
  $('.js-confirm').on('click', function (e) {
    if (!confirm($(this).data('confirm') || 'Yakin ingin menghapus? Tindakan ini tidak dapat dibatalkan.')) {
      e.preventDefault();
    }
  });

  // Auto-translate field (Google Translate free endpoint)
  $(document).on('click', '.js-auto-translate', function () {
    var $btn = $(this);
    var $src = $($btn.data('src'));
    var $target = $($btn.data('target'));
    var lang = $btn.data('lang') || 'en';
    var srcLang = $btn.data('srcLang') || 'id';
    var text = $.trim($src.val());
    if (!text) {
      alert('Kolom sumber masih kosong.');
      return;
    }
    var $icon = $btn.find('i').first();
    var prevHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menerjemahkan...');
    $.ajax({
      url: 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=' + srcLang + '&tl=' + lang + '&dt=t&q=' + encodeURIComponent(text),
      dataType: 'json',
      success: function (data) {
        if (!data || !data[0]) {
          alert('Terjemahan gagal: respons tidak valid.');
          return;
        }
        var out = '', seg, i, orig, m;
        for (i = 0; i < data[0].length; i++) {
          seg = data[0][i];
          if (!seg || !seg[0]) continue;
          out += seg[0];
          orig = seg[1] || '';
          m = orig.match(/\s+$/);
          if (m) out += m[0];
        }
        $target.val(out);
      },
      error: function () {
        alert('Terjemahan gagal. Periksa koneksi internet lalu coba lagi.');
      },
      complete: function () {
        $btn.prop('disabled', false).html(prevHtml);
      }
    });
  });

  // Live image preview
  $('input[type=file][data-preview]').on('change', function () {
    var input = this;
    var $img = $($(this).data('preview'));
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (ev) {
        $img.attr('src', ev.target.result).show();
      };
      reader.readAsDataURL(input.files[0]);
    }
  });

  // Live map preview (settings)
  var $mapInput = $('#siteMaps');
  if ($mapInput.length) {
    var $mapWrap = $('#mapPreviewWrap');
    var $mapFrame = $('#mapPreview');
    var updateMap = function () {
      var val = $.trim($mapInput.val());
      if (val) {
        $mapFrame.attr('src', val);
        $mapWrap.removeClass('d-none');
      } else {
        $mapFrame.attr('src', '');
        $mapWrap.addClass('d-none');
      }
    };
    $mapInput.on('input change', updateMap);
  }

  // Auto generate slug from title (posts)
  var $slug = $('#slugField');
  if ($slug.length) {
    var slugify = function (t) {
      return t.toLowerCase().trim()
        .replace(/&/g, ' dan ')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s_]+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    };
    $('#titleField').on('keyup blur', function () {
      if (!$slug.data('locked')) {
        $slug.val(slugify($(this).val()));
      }
    });
  }

  // Reset a single date field (careers deadline)
  $(document).on('click', '.js-clear-deadline', function () {
    $(this).closest('.input-group').find('input[type=date]').val('');
  });

  // Bootstrap icon picker
  var $biModal = $('#biIconModal');
  if ($biModal.length) {
    var biGrid = document.getElementById('biIconGrid');
    var biSearch = document.getElementById('biIconSearch');
    var biCount = document.getElementById('biIconCount');
    var biEmpty = document.getElementById('biIconEmpty');
    var biIcons = window.BI_ICONS || [];

    var getBiTarget = function () { return $biModal.data('target') || ''; };

    var renderBiIcons = function (filter) {
      filter = (filter || '').toLowerCase().trim();
      var items = filter
        ? biIcons.filter(function (n) { return n.indexOf(filter) !== -1; })
        : biIcons;
      var html = '';
      for (var i = 0; i < items.length; i++) {
        html += '<button type="button" class="bi-icon-cell" data-name="' + items[i] + '" title="bi bi-' + items[i] + '"><i class="bi bi-' + items[i] + '"></i></button>';
      }
      biGrid.innerHTML = html;
      biEmpty.classList.toggle('d-none', items.length > 0);
      biCount.textContent = items.length + ' ikon';

      var current = getBiTarget() ? $.trim($(getBiTarget()).val()) : '';
      if (current.indexOf('bi bi-') === 0) {
        var cell = biGrid.querySelector('[data-name="' + current.substring(6) + '"]');
        if (cell) cell.classList.add('selected');
      }
    };

    // Open picker
    $(document).on('click', '[data-bi-picker]', function () {
      $biModal.data('target', $(this).data('target'));
      biSearch.value = '';
      renderBiIcons('');
      $biModal.modal('show');
    });

    // Live filter
    $(biSearch).on('input', function () { renderBiIcons(this.value); });

    // Pick an icon
    $(document).on('click', '.bi-icon-cell', function () {
      var target = getBiTarget();
      if (target) {
        $(target).val('bi bi-' + $(this).data('name')).trigger('change');
      }
      $biModal.modal('hide');
    });

    // Live preview (manual typing or picker)
    $(document).on('input change', '.js-icon-field', function () {
      var val = $.trim($(this).val());
      var cls = val || $(this).data('defaultIcon') || 'bi bi-circle';
      $('#biIconPreview').html('<i class="' + cls + '"></i>');
    });
  }

});
