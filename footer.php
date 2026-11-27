<?php
/**
 * Site footer.
 *
 * @package Scensara
 */
defined( 'ABSPATH' ) || exit;
?>
<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div>
				<div class="footer-brand">Scensara</div>
				<p class="footer-text"><?php esc_html_e( 'Minimalist, intentional guided journals designed for women aged 20–40 to quiet mental clutter and find clarity on paper.', 'scensara' ); ?></p>
			</div>
			<div>
				<div class="footer-heading"><?php esc_html_e( 'Explore', 'scensara' ); ?></div>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'depth'          => 1,
					) );
				} else {
					$links = array(
						'products' => __( 'The Clarity Collection', 'scensara' ),
						'about'    => __( 'Our Story & Weekly Blog', 'scensara' ),
						'faq'      => __( 'Shipping & FAQ', 'scensara' ),
						'contact'  => __( 'Contact & Wholesale', 'scensara' ),
					);
					echo '<ul class="footer-links">';
					foreach ( $links as $slug => $label ) {
						$url = scensara_page_url( $slug );
						if ( $url ) {
							printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
						}
					}
					echo '</ul>';
				}
				?>
			</div>
			<div>
				<div class="footer-heading"><?php esc_html_e( 'Weekly Ritual', 'scensara' ); ?></div>
				<p class="footer-text"><?php esc_html_e( 'Every Sunday we publish one thought-narrowing question on our journal blog to help you reset for the week ahead.', 'scensara' ); ?></p>
			</div>
		</div>
		<div class="footer-bottom">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Scensara. <?php esc_html_e( 'All rights reserved. Crafted for intentional living.', 'scensara' ); ?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
