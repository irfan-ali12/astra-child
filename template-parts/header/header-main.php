<?php
/**
 * Header Main Template - KachoTech Custom Header
 * Integrated with Astra Theme & WooCommerce
 *
 * @package Astra Child
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Get site information
$site_name = get_bloginfo( 'name' );
$site_url  = home_url();
$cart_count = 0;

// WooCommerce cart count
if ( function_exists( 'WC' ) && WC()->cart ) {
  $cart_count = intval( WC()->cart->get_cart_contents_count() );
}

// My Account URL
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '';
$account_label = is_user_logged_in() ? __( 'My Account', 'astra-child' ) : __( 'Login / Register', 'astra-child' );

// Cart URL
$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '';
?>

<div class="page">

  <!-- TOP BAR -->
  <div class="topbar">
    <div class="topbar-inner">
      <div>
        <span class="top-pill"><?php _e( 'KACHOTECH WINTER FEST', 'astra-child' ); ?></span>
        <span><?php _e( 'Heaters, electronics & cosmetics delivered nationwide.', 'astra-child' ); ?></span>
      </div>
      <div><?php _e( 'WhatsApp: 03XX-XXXXXXX • Cash on Delivery Available', 'astra-child' ); ?></div>
    </div>
  </div>

  <!-- HEADER -->
  <header class="header">
    <div class="header-inner">

      <!-- LOGO -->
      <div class="logo-wrap">
        <?php 
        if ( has_custom_logo() ) {
          the_custom_logo();
        } else {
          $logo_path = get_stylesheet_directory_uri() . '/assets/images/kacho-tech-logo-large-size.png';
          echo '<img src="' . esc_url( $logo_path ) . '" alt="' . esc_attr( $site_name ) . '">';
        }
        ?>
        <a href="<?php echo esc_url( $site_url ); ?>" class="logo-text">
          <?php echo esc_html( $site_name ); ?>
        </a>
      </div>

      <!-- SEARCH BAR -->
      <div class="search-bar">
        <!-- Category dropdown toggle -->
        <div class="search-category-toggle" id="kt-category-toggle">
          <i class="ri-layout-grid-fill"></i>
          <span id="kt-category-label"><?php _e( 'All Categories', 'astra-child' ); ?></span>
          <i class="ri-arrow-down-s-line" style="font-size:14px;"></i>
        </div>

        <!-- Hidden selected category slug -->
        <input type="hidden" id="kt-category-slug" value="" />

        <!-- Live search input -->
        <input
          id="kt-search-input"
          class="search-input"
          type="search"
          autocomplete="off"
          placeholder="<?php _e( 'Search heaters, electronics, cosmetics, kitchen & more...', 'astra-child' ); ?>"
        />

        <button class="search-btn" id="kt-search-submit" type="button">
          <span><?php _e( 'Search', 'astra-child' ); ?></span>
        </button>

        <!-- Category dropdown list -->
        <div class="category-dropdown" id="kt-category-dropdown">
          <ul>
            <li data-slug="">
              <i class="ri-layout-grid-fill"></i> <?php _e( 'All Categories', 'astra-child' ); ?>
            </li>
            <li data-slug="heaters">
              <i class="ri-fire-line"></i> <?php _e( 'Heaters & Geysers', 'astra-child' ); ?>
            </li>
            <li data-slug="electronics">
              <i class="ri-tv-2-line"></i> <?php _e( 'Electronics & Audio', 'astra-child' ); ?>
            </li>
            <li data-slug="cosmetics">
              <i class="ri-magic-line"></i> <?php _e( 'Cosmetics & Grooming', 'astra-child' ); ?>
            </li>
            <li data-slug="kitchen-appliances">
              <i class="ri-restaurant-line"></i> <?php _e( 'Kitchen Appliances', 'astra-child' ); ?>
            </li>
            <li data-slug="accessories">
              <i class="ri-plug-line"></i> <?php _e( 'Accessories', 'astra-child' ); ?>
            </li>
          </ul>
        </div>

        <!-- AJAX search results -->
        <div class="search-results" id="kt-search-results"></div>
      </div>

      <!-- HEADER ACTIONS -->
      <div class="header-actions">
        <div class="ha">
          <span class="ha-label"><?php _e( 'Order', 'astra-child' ); ?></span>
          <span class="ha-main"><?php _e( 'Track Now', 'astra-child' ); ?></span>
        </div>
        
        <?php if ( $account_url ) : ?>
          <a class="ha" href="<?php echo esc_url( $account_url ); ?>">
            <span class="ha-label"><?php _e( 'Account', 'astra-child' ); ?></span>
            <span class="ha-main"><?php echo esc_html( $account_label ); ?></span>
          </a>
        <?php endif; ?>

        <?php if ( $cart_url ) : ?>
          <a class="cart-btn" href="<?php echo esc_url( $cart_url ); ?>">
            <i class="ri-shopping-bag-3-line"></i>
            <span><?php _e( 'Cart', 'astra-child' ); ?></span>
            <span class="badge"><?php echo intval( $cart_count ); ?></span>
          </a>
        <?php endif; ?>
      </div>

    </div>

    <!-- NAV STRIP -->
    <div class="nav-strip">
      <div class="nav-links">
        <a href="<?php echo esc_url( $site_url ); ?>"><?php _e( 'All Products', 'astra-child' ); ?></a>
        <?php
        // Get product categories dynamically
        $categories = get_terms( array(
          'taxonomy'   => 'product_cat',
          'hide_empty' => true,
          'number'     => 4,
        ) );

        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
          foreach ( $categories as $category ) {
            $cat_url = get_term_link( $category );
            echo '<a href="' . esc_url( $cat_url ) . '">' . esc_html( $category->name ) . '</a>';
          }
        } else {
          // Fallback categories if none exist
          ?>
          <a href="<?php echo esc_url( $site_url ); ?>"><?php _e( 'Heaters', 'astra-child' ); ?></a>
          <a href="<?php echo esc_url( $site_url ); ?>"><?php _e( 'Electronics', 'astra-child' ); ?></a>
          <a href="<?php echo esc_url( $site_url ); ?>"><?php _e( 'Cosmetics', 'astra-child' ); ?></a>
          <a href="<?php echo esc_url( $site_url ); ?>"><?php _e( 'Deals', 'astra-child' ); ?></a>
          <?php
        }
        ?>
      </div>
      <div class="nav-usp">
        <span>🚚 <?php _e( 'Nationwide Delivery', 'astra-child' ); ?></span>
        <span>💳 <?php _e( 'COD & Online Payments', 'astra-child' ); ?></span>
        <span>✅ <?php _e( '100% Original Stock', 'astra-child' ); ?></span>
      </div>
    </div>
  </header>
</div>



    <div class="page">

      <!-- TOP BAR -->
      <div class="topbar">
        <div class="topbar-inner">
          <div>
            <span class="top-pill">KACHOTECH WINTER FEST</span>
            <span>Heaters, electronics &amp; cosmetics delivered nationwide.</span>
          </div>
          <div>WhatsApp: 03XX-XXXXXXX • Cash on Delivery Available</div>
        </div>
      </div>

      <!-- HEADER -->
      <header class="header">
        <div class="header-inner">

          <!-- LOGO -->
          <div class="logo-wrap">
            <?php if ( has_custom_logo() ) : ?>
              <?php the_custom_logo(); ?>
            <?php else : ?>
              <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/kacho-tech-logo-large-size.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
              <div class="logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
            <?php endif; ?>
          </div>

          <!-- SEARCH -->
          <div class="search-bar">
            <!-- Category dropdown toggle -->
            <div class="search-category-toggle" id="kt-category-toggle">
              <i class="ri-layout-grid-fill"></i>
              <span id="kt-category-label">All Categories</span>
              <i class="ri-arrow-down-s-line" style="font-size:14px;"></i>
            </div>

            <!-- Hidden selected category slug -->
            <input type="hidden" id="kt-category-slug" value="" />

            <!-- Live search input -->
            <input
              id="kt-search-input"
              class="search-input"
              type="search"
              autocomplete="off"
              placeholder="Search heaters, electronics, cosmetics, kitchen & more..."
            />

            <button class="search-btn" id="kt-search-submit" type="button">
              <span>Search</span>
            </button>

            <!-- Category dropdown list -->
            <div class="category-dropdown" id="kt-category-dropdown">
              <ul>
                <li data-slug="">
                  <i class="ri-layout-grid-fill"></i> All Categories
                </li>
                <li data-slug="heaters">
                  <i class="ri-fire-line"></i> Heaters & Geysers
                </li>
                <li data-slug="electronics">
                  <i class="ri-tv-2-line"></i> Electronics & Audio
                </li>
                <li data-slug="cosmetics">
                  <i class="ri-magic-line"></i> Cosmetics & Grooming
                </li>
                <li data-slug="kitchen-appliances">
                  <i class="ri-restaurant-line"></i> Kitchen Appliances
                </li>
                <li data-slug="accessories">
                  <i class="ri-plug-line"></i> Accessories
                </li>
              </ul>
            </div>

            <!-- AJAX search results -->
            <div class="search-results" id="kt-search-results"></div>
          </div>

          <!-- HEADER ACTIONS -->
          <div class="header-actions">
            <div class="ha">
              <span class="ha-label">Order</span>
              <span class="ha-main">Track Now</span>
            </div>
            <a class="ha" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
              <span class="ha-label">Account</span>
              <span class="ha-main"><?php echo is_user_logged_in() ? esc_html__( 'My Account', 'astra-child' ) : esc_html__( 'Login / Register', 'astra-child' ); ?></span>
            </a>
            <a class="cart-btn" href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#' ); ?>">
              <i class="ri-shopping-bag-3-line"></i>
              <span>Cart</span>
              <span class="badge"><?php echo function_exists( 'WC' ) ? intval( WC()->cart->get_cart_contents_count() ) : 0; ?></span>
            </a>
          </div>

        </div>

        <!-- NAV -->
        <div class="nav-strip">
          <div class="nav-links">
            <a href="#">All Products</a>
            <a href="#">Heaters</a>
            <a href="#">Electronics</a>
            <a href="#">Cosmetics</a>
            <a href="#">Deals</a>
          </div>
          <div class="nav-usp">
            <span>🚚 Nationwide Delivery</span>
            <span>💳 COD & Online Payments</span>
            <span>✅ 100% Original Stock</span>
          </div>
        </div>
      </header>
    </div>

