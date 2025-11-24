<?php
/**
 * KachoTech Child - Footer Wrapper
 * Closes the HTML markup and enqueues footer hooks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	</main><!-- Close main from header.php -->

	<?php
	/**
	 * Astra footer hooks - keep compatibility
	 */
	do_action( 'astra_footer_before' );
	?>

	<footer id="site-footer" class="kt-footer">
		<?php
		if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) {
			?>
			<div class="ast-container">
				<div class="ast-footer-widgets-wrapper">
					<?php
					for ( $i = 1; $i <= 4; $i++ ) {
						if ( is_active_sidebar( 'footer-' . $i ) ) {
							?>
							<div class="ast-footer-widget-col">
								<?php dynamic_sidebar( 'footer-' . $i ); ?>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
			<?php
		}
		?>

		<!-- Footer bottom bar -->
		<div class="ast-footer-bottom">
			<div class="ast-container">
				<div class="ast-footer-bottom-content">
					<p class="ast-footer-copyright">
						&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> 
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php bloginfo( 'name' ); ?>
						</a>. 
						<?php esc_html_e( 'All Rights Reserved.', 'astra-child' ); ?>
					</p>
				</div>
			</div>
		</div>
	</footer>

	<?php
	do_action( 'astra_footer_after' );

	wp_footer();
	?>

</body>
</html>
