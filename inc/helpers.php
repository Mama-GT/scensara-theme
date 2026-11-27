<?php
/**
 * Helper functions and navigation.
 *
 * @package Scensara
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL to a file inside the theme.
 */
function scensara_asset( $path ) {
	return trailingslashit( get_template_directory_uri() ) . ltrim( $path, '/' );
}

/**
 * Is WooCommerce active?
 */
function scensara_has_wc() {
	return class_exists( 'WooCommerce' );
}

/**
 * Is the current request the contact page?
 */
function scensara_is_contact_page() {
	return is_page_template( 'page-contact.php' ) || is_page( 'contact' );
}

/**
 * Find a published page URL by slug or by template, with WooCommerce awareness.
 */
function scensara_page_url( $slug ) {
	if ( 'home' === $slug ) {
		return home_url( '/' );
	}

	if ( 'products' === $slug && scensara_has_wc() ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id > 0 && 'publish' === get_post_status( $shop_id ) ) {
			return get_permalink( $shop_id );
		}
	}

	$page = get_page_by_path( $slug );
	if ( $page && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}

	$template_slugs = array( 'products', 'about', 'contact', 'faq' );
	if ( in_array( $slug, $template_slugs, true ) ) {
		$pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-' . $slug . '.php',
			'fields'         => 'ids',
		) );
		if ( $pages ) {
			return get_permalink( $pages[0] );
		}
	}

	return '';
}

/**
 * Flat walker: preserves the original <nav class="main-nav"><a class="active"> markup.
 */
class Scensara_Flat_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item   = $data_object;
		$active = in_array( 'current-menu-item', (array) $item->classes, true )
			|| in_array( 'current-menu-ancestor', (array) $item->classes, true )
			|| in_array( 'current-page-ancestor', (array) $item->classes, true );
		$output .= sprintf(
			'<a href="%s"%s>%s</a>',
			esc_url( $item->url ),
			$active ? ' class="active" aria-current="page"' : '',
			esc_html( $item->title )
		);
	}
}

/**
 * Fallback navigation if no menu is assigned.
 */
function scensara_nav_fallback() {
	$links = array(
		'home'     => __( 'Home', 'scensara' ),
		'products' => __( 'Products', 'scensara' ),
		'about'    => __( 'About & Blog', 'scensara' ),
		'contact'  => __( 'Contact', 'scensara' ),
		'faq'      => __( 'FAQ', 'scensara' ),
	);
	echo '<nav class="main-nav" aria-label="' . esc_attr__( 'Primary', 'scensara' ) . '">';
	foreach ( $links as $slug => $label ) {
		$url = scensara_page_url( $slug );
		if ( ! $url ) {
			continue;
		}
		$current = ( 'home' === $slug && is_front_page() )
			|| ( is_page() && get_permalink() === $url )
			|| ( 'products' === $slug && scensara_has_wc() && ( is_shop() || is_product() ) );
		printf( '<a href="%s"%s>%s</a>', esc_url( $url ), $current ? ' class="active" aria-current="page"' : '', esc_html( $label ) );
	}
	echo '</nav>';
}

/**
 * Bag link with live count (replaced via WooCommerce cart fragments).
 */
function scensara_cart_link_html() {
	$count = ( scensara_has_wc() && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	$url   = scensara_has_wc() ? wc_get_cart_url() : '#';
	$class = ( scensara_has_wc() && is_cart() ) ? ' active' : '';
	return sprintf(
		'<a href="%1$s" class="cart-link%2$s">%3$s <span class="cart-badge">%4$s</span></a>',
		esc_url( $url ),
		$class,
		esc_html__( 'Bag', 'scensara' ),
		esc_html( number_format_i18n( $count ) )
	);
}

/**
 * Swatch colours for each cover shade (matched against the product's Cover attribute or SKU).
 */
function scensara_swatch_color( $product ) {
	$map = array(
		'alabaster' => '#FAF8F5',
		'sand'      => '#EAE3D9',
		'linen'     => '#EAE3D9',
		'travertine'=> '#EAE3D9',
		'espresso'  => '#2C2420',
	);
	$hay = strtolower( $product->get_sku() . ' ' . $product->get_attribute( 'Cover' ) . ' ' . $product->get_name() );
	foreach ( $map as $needle => $hex ) {
		if ( false !== strpos( $hay, $needle ) ) {
			return $hex;
		}
	}
	return '';
}

/**
 * Get published WooCommerce products visible in the catalog.
 */
function scensara_get_products( $args = array() ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}
	$defaults = array(
		'status'     => 'publish',
		'visibility' => 'catalog',
		'limit'      => 12,
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
	);
	return wc_get_products( wp_parse_args( $args, $defaults ) );
}

/**
 * Social / contact settings from the Customizer.
 */
function scensara_contact_info() {
	return array(
		'care'      => get_theme_mod( 'scensara_email_care', 'hello@scensara.com' ),
		'wholesale' => get_theme_mod( 'scensara_email_wholesale', 'partnerships@scensara.com' ),
		'social'    => get_theme_mod( 'scensara_social_handle', '@scensara' ),
	);
}

/**
 * Safe the_posts_pagination wrapper.
 */
function scensara_pagination() {
	the_posts_pagination( array(
		'mid_size'  => 1,
		'prev_text' => __( '&larr; Newer', 'scensara' ),
		'next_text' => __( 'Older &rarr;', 'scensara' ),
	) );
}
