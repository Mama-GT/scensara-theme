<?php
/**
 * WooCommerce wrapper: shop archive, categories and single product.
 *
 * @package Scensara
 */
get_header();
?>
<main id="main-content" class="scensara-commerce" tabindex="-1">
<?php if ( is_shop() || is_product_taxonomy() ) : ?>
	<?php get_template_part( 'template-parts/collection-banner' ); ?>
	<section class="section"><div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="product-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$p = wc_get_product( get_the_ID() );
					if ( $p ) {
						get_template_part( 'template-parts/product-card', null, array( 'product' => $p ) );
					}
				endwhile;
				?>
			</div>
			<?php scensara_pagination(); ?>
		<?php else : ?>
			<p class="no-products"><?php esc_html_e( 'No products found.', 'scensara' ); ?></p>
		<?php endif; ?>
	</div></section>
	<?php if ( is_shop() ) { get_template_part( 'template-parts/prompt-preview' ); } ?>
<?php else : ?>
	<section class="section"><div class="container">
		<?php woocommerce_breadcrumb(); ?>
		<?php woocommerce_content(); ?>
	</div></section>
<?php endif; ?>
</main>
<?php get_footer(); ?>
