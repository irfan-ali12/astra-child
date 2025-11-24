(function($) {
  'use strict';

  // Global page loader management
  var loader = $('#kt-global-loader');

  // Show loader on AJAX start (globally across WooCommerce and custom AJAX)
  $(document).on('ajaxStart', function() {
    if (loader.length) {
      loader.addClass('kt-loading');
    }
  });

  // Hide loader on AJAX complete
  $(document).on('ajaxStop', function() {
    if (loader.length) {
      setTimeout(function() {
        loader.removeClass('kt-loading');
      }, 300);
    }
  });

  // Show loader on page transitions (links to cart, checkout, etc.)
  $(document).on('click', 'a[href*="cart"], a[href*="checkout"], a[href*="my-account"]', function() {
    // Check if it's an external link
    if (!$(this).attr('href').includes(window.location.hostname)) {
      return;
    }
    if (loader.length) {
      loader.addClass('kt-loading');
    }
  });

  // Hide loader when page is fully loaded
  $(window).on('load', function() {
    if (loader.length) {
      setTimeout(function() {
        loader.removeClass('kt-loading');
      }, 500);
    }
  });

})(jQuery);
