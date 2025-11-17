<?php
/**
 * Featured Products Template
 * Displays featured WooCommerce products with category filtering
 * 
 * @package KachoTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="mx-auto mt-6 max-w-6xl px-4 pb-4">
	<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
		<h2 class="text-base font-semibold"><?php esc_html_e( 'Featured Products', 'astra-child' ); ?></h2>
		<div class="flex flex-wrap gap-2 text-[11px]" id="kt-product-filters">
			<button class="kt-filter-btn kt-filter-active rounded-full border border-[#1A1A1D] bg-[#1A1A1D] text-white px-3 py-1 font-semibold transition" data-category="all">
				<?php esc_html_e( 'All Products', 'astra-child' ); ?>
			</button>
			<button class="kt-filter-btn rounded-full border border-[#E4E6EC] bg-white text-[#6B6F76] px-3 py-1 font-semibold hover:border-[#1A1A1D]/50 transition" data-category="heaters">
				<?php esc_html_e( 'Heaters', 'astra-child' ); ?>
			</button>
			<button class="kt-filter-btn rounded-full border border-[#E4E6EC] bg-white text-[#6B6F76] px-3 py-1 font-semibold hover:border-[#1A1A1D]/50 transition" data-category="electronics">
				<?php esc_html_e( 'Electronics', 'astra-child' ); ?>
			</button>
			<button class="kt-filter-btn rounded-full border border-[#E4E6EC] bg-white text-[#6B6F76] px-3 py-1 font-semibold hover:border-[#1A1A1D]/50 transition" data-category="cosmetics">
				<?php esc_html_e( 'Cosmetics', 'astra-child' ); ?>
			</button>
		</div>
	</div>

	<div id="kt-featured-products-grid" class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
		<!-- Products loaded via AJAX -->
		<p class="text-center col-span-full text-[#6B6F76]"><?php esc_html_e( 'Loading products...', 'astra-child' ); ?></p>
	</div>
</section>

<script>
(function() {
	const filterBtns = document.querySelectorAll('.kt-filter-btn');
	const productsGrid = document.getElementById('kt-featured-products-grid');

	function loadProducts(category) {
		const formData = new FormData();
		formData.append('action', 'kt_load_featured_products');
		formData.append('category', category);

		fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				productsGrid.innerHTML = data.data.html;
			} else {
				productsGrid.innerHTML = '<p class="text-center col-span-full text-[#6B6F76]"><?php esc_html_e( 'No products found', 'astra-child' ); ?></p>';
			}
		})
		.catch(err => {
			console.error('Error loading products:', err);
			productsGrid.innerHTML = '<p class="text-center col-span-full text-red-500"><?php esc_html_e( 'Error loading products', 'astra-child' ); ?></p>';
		});
	}

	filterBtns.forEach(btn => {
		btn.addEventListener('click', function() {
			const category = this.getAttribute('data-category');
			
			// Update button states
			filterBtns.forEach(b => {
				b.classList.remove('kt-filter-active', 'bg-[#1A1A1D]', 'border-[#1A1A1D]', 'text-white');
				b.classList.add('border-[#E4E6EC]', 'bg-white', 'text-[#6B6F76]');
			});
			
			this.classList.add('kt-filter-active', 'bg-[#1A1A1D]', 'border-[#1A1A1D]', 'text-white');
			this.classList.remove('border-[#E4E6EC]', 'bg-white', 'text-[#6B6F76]');
			
			// Load products
			loadProducts(category);
		});
	});

	// Load all products on page load
	loadProducts('all');
})();
</script>
