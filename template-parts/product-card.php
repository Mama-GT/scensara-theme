<?php
/**
 * Product card used on the home page, Products page and shop archive.
 * Expects $args['product'] (WC_Product).
 *
 * @package Scensara
 */
$product = isset( $args['product'] ) ? $args['product'] : null;
if ( ! $product instanceof WC_Product ) {
	return;
}
$direct = $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock();
$cover  = $product->get_attribute( 'Cover' );
$swatch = scensara_swatch_color( $product );
$desc   = wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), 26 );
if ( $product->get_image_id() ) {
	$image = wp_get_attachment_image( $product->get_image_id(), 'scensara-card', false, array( 'loading' => 'lazy', 'alt' => $product->get_name() ) );
} else {
	// Branded fallback until a product photo is uploaded.
	$n   = strtolower( $product->get_name() . ' ' . $product->get_sku() );
	$map = array( 'duo' => 'scensara-lineup-square.jpg', 'set' => 'scensara-lineup-square.jpg', 'espresso' => 'scensara-packshot-front-espresso.jpg', 'sand' => 'scensara-packshot-34-linen-sand.jpg', 'linen' => 'scensara-packshot-34-linen-sand.jpg', 'travertine' => 'scensara-packshot-34-linen-sand.jpg', 'alabaster' => 'scensara-packshot-front-alabaster.jpg' );
	$file = 'scensara-lineup-square.jpg';
	foreach ( $map as $k => $f ) {
		if ( false !== strpos( $n, $k ) ) { $file = $f; break; }
	}
	$image = '<img src="' . esc_url( scensara_asset( 'images/' . $file ) ) . '" alt="' . esc_attr( $product->get_name() ) . '" loading="lazy" width="800" height="1000">';
}
?>
<article class="product-card">
	<a class="product-media" href="<?php echo esc_url( $product->get_permalink() ); ?>" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
		<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput -- WordPress-generated markup. ?>
		<?php if ( $product->is_on_sale() ) : ?><span class="onsale"><?php esc_html_e( 'Sale', 'scensara' ); ?></span><?php endif; ?>
	</a>
	<div class="product-body">
		<?php if ( $cover ) : ?>
			<div class="product-shade">
				<?php if ( $swatch ) : ?><span class="swatch" style="background:<?php echo esc_attr( $swatch ); ?>"></span><?php endif; ?>
				<?php echo esc_html( $cover ); ?>
			</div>
		<?php endif; ?>
		<h3 class="product-title"><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
		<div class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
		<?php if ( $desc ) : ?><p class="product-desc"><?php echo esc_html( $desc ); ?></p><?php endif; ?>
		<?php if ( $direct ) : ?>
			<a class="btn btn-primary" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" rel="nofollow" aria-label="<?php echo esc_attr( sprintf( __( 'Add to Bag: %s', 'scensara' ), $product->get_name() ) ); ?>"><?php esc_html_e( 'Add to Bag & Checkout', 'scensara' ); ?></a>
		<?php else : ?>
			<a class="btn btn-outline" href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php esc_html_e( 'View Details', 'scensara' ); ?></a>
		<?php endif; ?>
	</div>
</article>
