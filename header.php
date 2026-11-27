<?php
/**
 * Site header.
 *
 * @package Scensara
 */
defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#FAF8F5">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'scensara' ); ?></a>

<?php $announcement = get_theme_mod( 'scensara_announcement', 'Worldwide Shipping · Complimentary Delivery on Orders $60+ USD' ); ?>
<?php if ( $announcement ) : ?>
<div class="announcement-bar"><?php echo esc_html( $announcement ); ?></div>
<?php endif; ?>

<header class="site-header">
	<div class="container header-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-logo" rel="home"><?php echo esc_html( 'Scensara' ); ?></a>
		<?php
		wp_nav_menu( array(
			'theme_location'       => 'primary',
			'container'            => 'nav',
			'container_class'      => 'main-nav',
			'container_aria_label' => __( 'Primary', 'scensara' ),
			'items_wrap'           => '%3$s',
			'depth'                => 1,
			'walker'               => new Scensara_Flat_Walker(),
			'fallback_cb'          => 'scensara_nav_fallback',
		) );
		echo scensara_cart_link_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper.
		?>
	</div>
</header>
