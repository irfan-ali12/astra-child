<?php
/**
 * KachoTech Child - Header Wrapper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      integrity="sha512-RXf+QSDCUq0xI1NQ1cT1t7LJYp6wH4u7q9jwIsyhlHLLJoPLD114F8CbnZ4PlzyBbs6j8ZZr3Su2MzKK3KpYkg=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Global Page Loader -->
<div id="kt-global-loader" class="kt-global-loader">
	<div class="kt-spinner"></div>
</div>

<?php
/**
 * Astra standard hooks (keep compatibility)
 */
do_action( 'astra_header_before' );

// Our custom header layout:
get_template_part( 'template-parts/header/header', 'main' );

do_action( 'astra_header_after' );

?><main class="site-main" id="main">
