<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

// Add theme support for child theme
add_action( 'after_setup_theme', function() {
    // Make sure parent loads first
    add_theme_support( 'astra-child-theme' );
});

require get_stylesheet_directory() . '/inc/setup.php';
require get_stylesheet_directory() . '/inc/enqueue.php';
require get_stylesheet_directory() . '/inc/shortcodes.php';
require get_stylesheet_directory() . '/inc/woocommerce-hooks.php';
require get_stylesheet_directory() . '/inc/header-hooks.php';
require get_stylesheet_directory() . '/inc/helpers.php';