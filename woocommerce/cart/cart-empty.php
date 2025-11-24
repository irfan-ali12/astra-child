<?php
defined( 'ABSPATH' ) || exit;
?>

<style>
  /* Remove the default Astra page title header on the cart page */
  header.entry-header.ast-no-thumbnail {
    display: none;
  }

  /* Main wrapper for the empty cart page – full viewport height */
  .kt-empty-cart-page {
    font-family: "Inter", system-ui, -apple-system, sans-serif;
    background-color: #ffffff;
    min-height: 100vh;             /* full screen height */
    padding: 16px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
  }

  /* WooCommerce notices (product removed, cart empty, etc.) */
  .kt-empty-cart-page .woocommerce-notices-wrapper {
    margin-bottom: 12px;
  }

  /* Inner wrapper that takes the remaining height and centers content */
  .kt-empty-cart-inner {
    flex: 1;                        /* take up remaining vertical space */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;        /* perfectly center header + image */
    text-align: center;
  }

  /* HEADER */
  .kt-cart-header {
    margin-bottom: 12px;
  }

  .kt-cart-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin: 0;
    position: relative;
    display: inline-block;
    letter-spacing: -0.02em;
  }

  .kt-cart-header h1::after {
    content: "";
    width: 42px;
    height: 3px;
    background: #ff2446;
    display: block;
    margin: 10px auto 0;
    border-radius: 999px;
  }

  /* EMPTY CART VISUAL */
  .kt-empty-cart-visual {
    margin-top: 10px;
  }

  .kt-empty-cart-image {
    max-width: 260px;
    width: 100%;
    margin: 0 auto;
    display: block;
  }

  @media (max-width: 640px) {
    .kt-cart-header h1 {
      font-size: 26px;
    }
  }
</style>

<div class="kt-empty-cart-page">


  <div class="kt-empty-cart-inner">
    <!-- Custom KT Header -->
    <div class="kt-cart-header">
      <h1>Your Cart is Empty</h1>
    </div>

    <!-- Empty Cart Center Image -->
    <div class="kt-empty-cart-visual">
      <img
        class="kt-empty-cart-image"
        src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png"
        alt="<?php esc_attr_e( 'Empty cart', 'kachotech-child' ); ?>"
      />
    </div>
  </div>

</div>
