<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * AJAX product search (live suggestions, category-aware)
 */
function kt_ajax_search_products() {
    check_ajax_referer( 'kt_search_nonce', 'nonce' );

    $term     = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
    $cat_slug = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';

    if ( $term === '' ) {
        wp_send_json_success( [] );
    }

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 8,
        's'              => $term,
    ];

    if ( $cat_slug && $cat_slug !== 'all' ) {
        $args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $cat_slug,
        ]];
    }

    $q = new WP_Query( $args );
    $results = [];

    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            $product = wc_get_product( get_the_ID() );
            if ( ! $product ) {
                continue;
            }
            $results[] = [
                'title' => get_the_title(),
                'url'   => get_permalink(),
                'price' => $product->get_price_html(),
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success( $results );
}
add_action( 'wp_ajax_kt_ajax_search_products', 'kt_ajax_search_products' );
add_action( 'wp_ajax_nopriv_kt_ajax_search_products', 'kt_ajax_search_products' );

/**
 * Header account label (Login/Register vs Hi, Name)
 */
function kt_header_account_label() {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        echo '<span class="ha-label">Account</span>';
        echo '<span class="ha-main">Hi, ' . esc_html( $user->display_name ) . '</span>';
    } else {
        echo '<span class="ha-label">Account</span>';
        echo '<span class="ha-main">Login / Register</span>';
    }
}
