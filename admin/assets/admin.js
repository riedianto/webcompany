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

});
