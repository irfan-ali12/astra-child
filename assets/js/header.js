jQuery(function($) {
  var $input   = $('#kt-search-input');
  var $cat     = $('#kt-cat');
  var $results = $('#kt-search-results');
  var timer    = null;

  function hideResults() {
    $results.hide().empty();
  }

  $input.on('keyup', function() {
    var q = $.trim($input.val());
    clearTimeout(timer);

    if (q.length < 2) {
      hideResults();
      return;
    }

    timer = setTimeout(function() {
      $.ajax({
        url: KT_AJAX.ajax_url,
        method: 'GET',
        dataType: 'json',
        data: {
          action: 'kt_ajax_search_products',
          nonce: KT_AJAX.nonce,
          q: q,
          cat: $cat.val()
        },
        success: function(resp) {
          if (!resp.success || !resp.data || !resp.data.length) {
            hideResults();
            return;
          }
          var html = '<ul>';
          resp.data.forEach(function(p) {
            html += '<li data-url="'+p.url+'">' +
                      '<span class="title">'+p.title+'</span>' +
                      '<span class="price">'+p.price+'</span>' +
                    '</li>';
          });
          html += '</ul>';
          $results.html(html).show();
        }
      });
    }, 220);
  });

  $results.on('click', 'li', function() {
    window.location.href = $(this).data('url');
  });

  $(document).on('click', function(e) {
    if (!$(e.target).closest('.kt-search-bar').length) {
      hideResults();
    }
  });
});
