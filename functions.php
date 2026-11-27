<?php
/**
 * Scensara theme functions.
 *
 * @package Scensara
 */

defined( 'ABSPATH' ) || exit;

define( 'SCENSARA_VERSION', '1.1.0' );

/**
 * Theme supports, menus, image sizes.
 */
function scensara_setup() {
	load_theme_textdomain( 'scensara', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 480,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// WooCommerce.
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width'         => 720,
		'single_image_width'            => 1200,
		'gallery_thumbnail_image_width' => 160,
		'product_grid'                  => array(
			'default_rows'    => 3,
			'min_rows'        => 1,
			'default_columns' => 3,
			'min_columns'     => 1,
			'max_columns'     => 4,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Custom image sizes.
	add_image_size( 'scensara-card', 720, 720, true );
	add_image_size( 'scensara-editorial', 1200, 800, true );
	add_image_size( 'scensara-wide', 1920, 1080, true );

	register_nav_menus( array(
		'primary' => __( 'Primary navigation', 'scensara' ),
		'footer'  => __( 'Footer navigation', 'scensara' ),
	) );
}
add_action( 'after_setup_theme', 'scensara_setup' );

/**
 * Enqueue styles and scripts.
 */
function scensara_enqueue_assets() {
	wp_enqueue_style(
		'scensara-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'scensara-style', get_stylesheet_uri(), array( 'scensara-fonts' ), SCENSARA_VERSION );

	wp_enqueue_script(
		'scensara-store',
		get_template_directory_uri() . '/js/store.js',
		array(),
		SCENSARA_VERSION,
		true
	);

	if ( scensara_is_contact_page() ) {
		wp_enqueue_script(
			'scensara-contact',
			get_template_directory_uri() . '/js/contact.js',
			array(),
			SCENSARA_VERSION,
			true
		);
		wp_localize_script( 'scensara-contact', 'scensaraContact', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'scensara_contact' ),
			'failure' => __( 'Your message could not be sent. Please try again or email us directly.', 'scensara' ),
		) );
	}

	if ( function_exists( 'WC' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'scensara_enqueue_assets', 30 );

/**
 * Preconnect to Google Fonts.
 */
function scensara_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'scensara_resource_hints', 10, 2 );

/**
 * Body class.
 */
function scensara_body_class( $classes ) {
	$classes[] = 'scensara';
	return $classes;
}
add_filter( 'body_class', 'scensara_body_class' );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/contact.php';
require get_template_directory() . '/inc/woocommerce.php';
require get_template_directory() . '/inc/setup.php';
