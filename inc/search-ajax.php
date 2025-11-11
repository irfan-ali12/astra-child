<?php
/**
 * Custom Search AJAX Handler for WooCommerce Products
 * Provides fallback when Store API is not available
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Handle AJAX product search
 */
add_action( 'wp_ajax_nopriv_kt_product_search', 'kt_product_search_handler' );
add_action( 'wp_ajax_kt_product_search', 'kt_product_search_handler' );

function kt_product_search_handler() {
  check_ajax_referer( 'kt_search_nonce', 'nonce' );
  
  $search_query = sanitize_text_field( $_POST['search'] ?? '' );
  $category = sanitize_text_field( $_POST['category'] ?? '' );
  $per_page = intval( $_POST['per_page'] ?? 8 );
  
  if ( strlen( $search_query ) < 2 ) {
    wp_send_json_error( 'Search term too short' );
  }
  
  $args = array(
    's'              => $search_query,
    'post_type'      => 'product',
    'posts_per_page' => $per_page,
    'post_status'    => 'publish',
  );
  
  if ( ! empty( $category ) ) {
    $args['tax_query'] = array(
      array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => $category,
      ),
    );
  }
  
  $products = new WP_Query( $args );
  $results = array();
  
  if ( $products->have_posts() ) {
    while ( $products->have_posts() ) {
      $products->the_post();
      
      $product = wc_get_product( get_the_ID() );
      
      if ( ! $product ) {
        continue;
      }
      
      $image_id = $product->get_image_id();
      $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';
      
      $results[] = array(
        'id'        => $product->get_id(),
        'name'      => $product->get_name(),
        'price'     => $product->get_price(),
        'currency_symbol' => get_woocommerce_currency_symbol(),
        'images'    => array(
          array( 'src' => $image_url )
        ),
        'permalink' => $product->get_permalink(),
      );
    }
  }
  
  wp_reset_postdata();
  
  wp_send_json_success( $results );
}

/**
 * Test endpoint to check API availability
 */
add_action( 'wp_ajax_nopriv_kt_test_api', 'kt_test_api_handler' );
add_action( 'wp_ajax_kt_test_api', 'kt_test_api_handler' );

function kt_test_api_handler() {
  wp_send_json_success( array(
    'status' => 'ok',
    'woocommerce' => class_exists( 'WooCommerce' ),
    'rest_api' => function_exists( 'register_rest_route' ),
  ) );
}
