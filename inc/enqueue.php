<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue KachoTech Custom Header Styles and Scripts
 */
add_action( 'wp_enqueue_scripts', function() {

    // Poppins Font (from Google Fonts)
    wp_enqueue_style(
        'poppins-font',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap',
        [],
        null,
        'all'
    );

    // RemixIcon (for icons: ri-layout-grid-fill, ri-fire-line, etc.)
    wp_enqueue_style(
        'remixicon-font',
        'https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css',
        [],
        '4.2.0',
        'all'
    );

    // Custom Header CSS
    wp_enqueue_style(
        'kachotech-header-styles',
        get_stylesheet_directory_uri() . '/assets/css/header-custom.css',
        [],
        CHILD_THEME_ASTRA_CHILD_VERSION,
        'all'
    );

    // Custom Header JS (search functionality)
    wp_enqueue_script(
        'kachotech-header-script',
        get_stylesheet_directory_uri() . '/assets/js/header-custom.js',
        [],
        CHILD_THEME_ASTRA_CHILD_VERSION,
        true
    );

    wp_localize_script( 'kachotech-header-script', 'KT_AJAX', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'kt_search_nonce' ),
    ] );

}, 20 );
