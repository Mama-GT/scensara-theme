<?php
/**
 * Default page.
 *
 * @package Scensara
 */
get_header();
?>
<main id="main-content" tabindex="-1">
<?php while ( have_posts() ) : the_post(); ?>
	<div class="page-banner"><div class="container">
		<h1><?php the_title(); ?></h1>
	</div></div>
	<section class="section"><div class="container">
		<div class="entry-content<?php echo ( scensara_has_wc() && ( is_cart() || is_checkout() || is_account_page() ) ) ? ' entry-content--wide' : ''; ?>">
			<?php the_content(); wp_link_pages(); ?>
		</div>
	</div></section>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
