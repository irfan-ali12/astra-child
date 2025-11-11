<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {

    ob_start(); ?>
    <span class="kt-cart-badge">
        <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
    </span>
    <?php

    $fragments['span.kt-cart-badge'] = ob_get_clean();
    return $fragments;
});
