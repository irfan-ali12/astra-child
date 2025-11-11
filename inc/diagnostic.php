<?php
/**
 * Header Setup - Diagnostic and Fix Utilities
 * Add this to functions.php temporarily to diagnose issues
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Diagnostic: Check header configuration
 */
add_action( 'wp_footer', 'kt_diagnostic_output', 999 );

function kt_diagnostic_output() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  
  // Only show on frontend (not admin)
  if ( is_admin() ) {
    return;
  }
  
  // Optional: Uncomment to see diagnostics
  // Uncomment the line below to see diagnostics
  // echo '<!-- KT_DIAGNOSTIC_START -->' . "\n";
  
  echo '<!-- KachoTech Header Diagnostic Info' . "\n";
  echo 'WooCommerce Active: ' . ( class_exists( 'WooCommerce' ) ? 'YES' : 'NO' ) . "\n";
  echo 'REST API Available: ' . ( function_exists( 'register_rest_route' ) ? 'YES' : 'NO' ) . "\n";
  echo 'Store API Available: ' . ( function_exists( 'woocommerce_rest_api_init' ) ? 'YES' : 'NO' ) . "\n";
  echo 'PHP Version: ' . PHP_VERSION . "\n";
  echo 'WordPress Version: ' . get_bloginfo( 'version' ) . "\n";
  echo '-->' . "\n";
}

/**
 * Enqueue diagnostic console script (for testing)
 */
add_action( 'wp_footer', 'kt_diagnostic_console', 999 );

function kt_diagnostic_console() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  
  if ( is_admin() ) {
    return;
  }
  
  ?>
  <script>
  // KachoTech Header Diagnostic Console
  (function() {
    if (typeof console === 'undefined') return;
    
    console.log('=== KachoTech Header Diagnostics ===');
    console.log('Page URL:', window.location.href);
    console.log('Site URL:', window.location.origin);
    
    // Test API endpoints
    console.log('Testing API endpoints...');
    
    // Test REST API
    fetch(window.location.origin + '/wp-json/wc/store/products?search=test&per_page=1')
      .then(r => {
        console.log('Store API Status:', r.status, r.ok ? '✓' : '✗');
        if (!r.ok) {
          console.log('Store API Response:', r.statusText);
        }
        return r.json();
      })
      .then(d => console.log('Store API Data Sample:', d.slice ? d.slice(0,1) : d))
      .catch(e => console.log('Store API Error:', e.message));
    
    // Test custom AJAX
    const formData = new FormData();
    formData.append('action', 'kt_test_api');
    
    fetch(window.location.origin + '/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: formData,
    })
    .then(r => r.json())
    .then(d => console.log('Custom AJAX Status:', d.success ? '✓' : '✗', d))
    .catch(e => console.log('Custom AJAX Error:', e.message));
    
    console.log('Header elements:', {
      searchInput: !!document.getElementById('kt-search-input'),
      searchBtn: !!document.getElementById('kt-search-submit'),
      results: !!document.getElementById('kt-search-results'),
      category: !!document.getElementById('kt-category-toggle'),
    });
    
    console.log('KT_AJAX available:', typeof KT_AJAX !== 'undefined');
    if (typeof KT_AJAX !== 'undefined') {
      console.log('KT_AJAX:', {
        ajax_url: KT_AJAX.ajax_url,
        nonce: KT_AJAX.nonce ? 'Present' : 'Missing',
      });
    }
    
    console.log('=== End Diagnostics ===');
  })();
  </script>
  <?php
}
