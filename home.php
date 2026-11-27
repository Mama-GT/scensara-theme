<?php
/**
 * Posts page (Journal).
 *
 * @package Scensara
 */
get_header();
?>
<main id="main-content" tabindex="-1">
	<div class="page-banner"><div class="container">
		<span class="eyebrow"><?php esc_html_e( 'The Sunday Edit', 'scensara' ); ?></span>
		<h1><?php esc_html_e( 'Weekly Reflection Blog', 'scensara' ); ?></h1>
		<p><?php esc_html_e( 'Short weekly essays and guided prompts to pair with your morning tea or coffee.', 'scensara' ); ?></p>
	</div></div>
	<section class="section section-sand"><div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="blog-grid">
				<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/blog-card' ); endwhile; ?>
			</div>
			<?php scensara_pagination(); ?>
		<?php else : ?>
			<p class="blog-empty"><?php esc_html_e( 'Our first reflections are on the way.', 'scensara' ); ?></p>
		<?php endif; ?>
	</div></section>
</main>
<?php get_footer(); ?>
