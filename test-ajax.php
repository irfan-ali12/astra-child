<?php
// Simple test to verify AJAX filtering works
define( 'WP_USE_THEMES', false );
require( dirname( __FILE__ ) . '/../../../../wp-load.php' );

// Test parameters
$_POST['nonce'] = wp_create_nonce( 'kt_filter_nonce' );
$_POST['categories'] = '';
$_POST['availability'] = '';
$_POST['brands'] = '';
$_POST['min_rating'] = 0;
$_POST['max_price'] = '';
$_POST['search'] = '';
$_POST['paged'] = 1;

// Call the AJAX function
$output = ob_get_clean();
include( get_stylesheet_directory() . '/inc/shop-ajax.php' );

// Manually call the function to see output
kt_filter_products_ajax();
