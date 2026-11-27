<?php
/**
 * Template Name: Scensara Products
 *
 * @package Scensara
 */
get_header();
?>
<main id="main-content" tabindex="-1">
	<?php get_template_part( 'template-parts/collection-banner' ); ?>
	<section class="section"><div class="container">
		<?php
		$products = scensara_get_products( array( 'limit' => 24 ) );
		if ( $products ) :
			?>
			<div class="product-grid">
				<?php foreach ( $products as $p ) { get_template_part( 'template-parts/product-card', null, array( 'product' => $p ) ); } ?>
			</div>
		<?php else : ?>
			<p class="no-products"><?php esc_html_e( 'Our editions are being prepared. Please check back shortly.', 'scensara' ); ?></p>
		<?php endif; ?>
	</div></section>
	<?php get_template_part( 'template-parts/prompt-preview' ); ?>
</main>
<?php get_footer(); ?>
