<?php
/**
 * Category Strip Template
 * Displays product categories with images
 * 
 * @package KachoTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get product categories
$categories = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'number'     => 4,
	'orderby'    => 'term_order',
	'order'      => 'ASC',
) );
?>

<section class="kt-category-strip">
	<div class="kt-category-strip-header">
		<h2 class="kt-category-strip-title"><?php esc_html_e( 'Shop Deals by Category', 'astra-child' ); ?></h2>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="kt-category-strip-link">
			<?php esc_html_e( 'All Categories', 'astra-child' ); ?>
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
		</a>
	</div>

	<div class="kt-category-grid">
		<?php
		if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				$image_url    = wp_get_attachment_url( $thumbnail_id );
				if ( ! $image_url ) {
					$image_url = 'https://via.placeholder.com/100x100?text=' . urlencode( $category->name );
				}
				?>
				<a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="kt-category-card">
					<div class="kt-category-card-icon">
						<img
							src="<?php echo esc_url( $image_url ); ?>"
							alt="<?php echo esc_attr( $category->name ); ?>"
							loading="lazy"
						/>
					</div>
					<span class="kt-category-card-name"><?php echo esc_html( $category->name ); ?></span>
				</a>
				<?php
			}
		} else {
			// Fallback categories
			$fallback_categories = array(
				array( 'name' => 'All Products', 'icon' => 'Store' ),
				array( 'name' => 'Heaters', 'icon' => 'Heater' ),
				array( 'name' => 'Home Appliances', 'icon' => 'Tv' ),
				array( 'name' => 'Cosmetics & Personal Care', 'icon' => 'Beauty' ),
			);
			foreach ( $fallback_categories as $cat ) {
				?>
				<div class="kt-category-card">
					<div class="kt-category-card-icon">
						<img
							src="https://via.placeholder.com/100x100?text=<?php echo urlencode( $cat['name'] ); ?>"
							alt="<?php echo esc_attr( $cat['name'] ); ?>"
							loading="lazy"
						/>
					</div>
					<span class="kt-category-card-name"><?php echo esc_html( $cat['name'] ); ?></span>
				</div>
				<?php
			}
		}
		?>
	</div>
</section>
