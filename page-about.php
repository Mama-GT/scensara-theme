<?php
/**
 * Template Name: Scensara About & Weekly Blog
 *
 * @package Scensara
 */
get_header();
?>
<main id="main-content" tabindex="-1">
	<div class="page-banner"><div class="container">
		<span class="eyebrow"><?php esc_html_e( 'Our Story & Weekly Journal', 'scensara' ); ?></span>
		<h1><?php esc_html_e( 'About Scensara', 'scensara' ); ?></h1>
		<p><?php esc_html_e( 'Created for women who crave depth, calm aesthetics, and space to hear their own thoughts clearly.', 'scensara' ); ?></p>
	</div></div>

	<section class="section"><div class="container">
		<div class="about-split">
			<img src="<?php echo esc_url( scensara_asset( 'images/scensara-lifestyle-bed-linen-sand.jpg' ) ); ?>" alt="<?php esc_attr_e( 'A Scensara journal and morning coffee', 'scensara' ); ?>" width="1500" height="1000">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Why We Created Scensara', 'scensara' ); ?></span>
				<h2 class="split-title"><?php esc_html_e( 'Designed for the woman in transition', 'scensara' ); ?></h2>
				<p class="split-copy"><?php esc_html_e( 'Between ages 20 and 40, women make hundreds of defining decisions around career direction, relationships, boundaries, and personal identity, all while surrounded by digital noise.', 'scensara' ); ?></p>
				<p class="split-copy"><?php esc_html_e( 'Scensara was born from a simple belief: the quality of your life is shaped by the quality of the questions you ask yourself. Instead of overwhelming you with rigid daily trackers, every page offers one thoughtful question that narrows your focus.', 'scensara' ); ?></p>
			</div>
		</div>
	</div></section>

	<section class="section section-sand" id="weekly-blog"><div class="container">
		<div class="section-header">
			<span class="eyebrow"><?php esc_html_e( 'The Sunday Edit', 'scensara' ); ?></span>
			<h2><?php esc_html_e( 'Weekly Reflection Blog', 'scensara' ); ?></h2>
			<p><?php esc_html_e( 'Short weekly essays and guided prompts to pair with your morning tea or coffee.', 'scensara' ); ?></p>
		</div>
		<?php
		$q = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 6, 'ignore_sticky_posts' => true ) );
		if ( $q->have_posts() ) :
			?>
			<div class="blog-grid">
				<?php while ( $q->have_posts() ) : $q->the_post(); get_template_part( 'template-parts/blog-card' ); endwhile; wp_reset_postdata(); ?>
			</div>
			<?php
			$posts_page = (int) get_option( 'page_for_posts' );
			if ( $posts_page ) :
				?>
				<p class="blog-more"><a class="btn btn-outline" href="<?php echo esc_url( get_permalink( $posts_page ) ); ?>"><?php esc_html_e( 'View All Entries', 'scensara' ); ?></a></p>
			<?php endif; ?>
		<?php else : ?>
			<p class="blog-empty"><?php esc_html_e( 'Our first reflections are on the way. Publish posts in WordPress and they appear here.', 'scensara' ); ?></p>
		<?php endif; ?>
	</div></section>
	<?php
	if ( have_posts() ) {
		the_post();
		if ( trim( get_the_content() ) ) {
			echo '<div class="section"><div class="container entry-content">';
			the_content();
			echo '</div></div>';
		}
	}
	?>
</main>
<?php get_footer(); ?>
