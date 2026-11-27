<?php
/**
 * Fallback template.
 *
 * @package Scensara
 */
get_header();
?>
<main id="main-content" tabindex="-1">
	<div class="page-banner"><div class="container">
		<span class="eyebrow"><?php esc_html_e( 'The Sunday Edit', 'scensara' ); ?></span>
		<h1><?php echo is_search() ? esc_html( sprintf( __( 'Search: %s', 'scensara' ), get_search_query() ) ) : esc_html__( 'Weekly Reflection Blog', 'scensara' ); ?></h1>
	</div></div>
	<section class="section"><div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="blog-grid">
				<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/blog-card' ); endwhile; ?>
			</div>
			<?php scensara_pagination(); ?>
		<?php else : ?>
			<p class="blog-empty"><?php esc_html_e( 'Nothing here yet.', 'scensara' ); ?></p>
		<?php endif; ?>
	</div></section>
</main>
<?php get_footer(); ?>
