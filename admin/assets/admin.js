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
