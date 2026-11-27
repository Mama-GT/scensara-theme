<?php
/**
 * Single blog post.
 *
 * @package Scensara
 */
get_header();
?>
<main id="main-content" tabindex="-1">
<?php while ( have_posts() ) : the_post(); ?>
	<div class="page-banner"><div class="container">
		<span class="eyebrow"><?php echo esc_html( get_post_meta( get_the_ID(), '_scensara_kicker', true ) ?: get_the_date() ); ?></span>
		<h1><?php the_title(); ?></h1>
	</div></div>
	<article <?php post_class( 'section' ); ?>>
		<div class="container">
			<div class="entry-wrap">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-hero-image"><?php the_post_thumbnail( 'scensara-editorial' ); ?></div>
				<?php endif; ?>
				<div class="entry-content"><?php the_content(); wp_link_pages(); ?></div>
				<nav class="post-nav" aria-label="<?php esc_attr_e( 'Posts', 'scensara' ); ?>">
					<?php previous_post_link( '%link', '&larr; %title' ); ?>
					<?php next_post_link( '%link', '%title &rarr;' ); ?>
				</nav>
			</div>
		</div>
	</article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
