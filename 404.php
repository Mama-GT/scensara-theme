<?php
/**
 * 404.
 *
 * @package Scensara
 */
get_header();
?>
<main id="main-content" tabindex="-1">
	<div class="page-banner"><div class="container">
		<span class="eyebrow">404</span>
		<h1><?php esc_html_e( 'This page has gone quiet', 'scensara' ); ?></h1>
		<p><?php esc_html_e( 'The page you were looking for is not here. Let us find your way back.', 'scensara' ); ?></p>
	</div></div>
	<section class="section"><div class="container" style="text-align:center">
		<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'scensara' ); ?></a>
	</div></section>
</main>
<?php get_footer(); ?>
