<?php
/**
 * Front page.
 *
 * @package Scensara
 */
defined( 'ABSPATH' ) || exit;
get_header();

$products_url = scensara_page_url( 'products' );
$about_url    = scensara_page_url( 'about' );
$hero_id      = absint( get_theme_mod( 'scensara_hero_image' ) );
$hero_src     = $hero_id ? wp_get_attachment_image_url( $hero_id, 'scensara-wide' ) : scensara_asset( 'images/scensara-hero-16x9.jpg' );
$video_id     = absint( get_theme_mod( 'scensara_hero_video' ) );
$video_url    = $video_id && wp_attachment_is( 'video', $video_id ) ? wp_get_attachment_url( $video_id ) : '';
if ( ! $video_url && file_exists( get_template_directory() . '/videos/hero.mp4' ) ) {
	$video_url = scensara_asset( 'videos/hero.mp4' );
}
$products = scensara_get_products( array( 'limit' => 6 ) );
$featured = null;
$singles  = array();
$bundle   = null;
foreach ( $products as $p ) {
	if ( false !== stripos( $p->get_sku(), 'duo' ) || false !== stripos( $p->get_name(), 'duo' ) ) {
		$bundle = $p;
	} else {
		$singles[] = $p;
	}
}
$featured = $singles ? $singles[0] : null;
?>
<main id="main-content" tabindex="-1">

<section class="hero-stage" aria-label="<?php esc_attr_e( 'Introduction', 'scensara' ); ?>">
	<div class="hero-media">
		<img src="<?php echo esc_url( $hero_src ); ?>" width="2560" height="1440" alt="<?php esc_attr_e( 'The Clarity Edition journals in warm morning light', 'scensara' ); ?>" fetchpriority="high" decoding="async">
		<?php if ( $video_url ) : ?>
			<video class="hero-video" muted loop playsinline preload="none" aria-hidden="true" data-src="<?php echo esc_url( $video_url ); ?>"></video>
		<?php endif; ?>
	</div>
	<div class="hero-wash" aria-hidden="true"></div>
	<div class="hero-sun" aria-hidden="true">
		<span class="hero-sun__glow"></span>
		<span class="hero-sun__rays"></span>
		<span class="hero-sun__rays hero-sun__rays--soft"></span>
	</div>
	<div class="hero-inner">
		<div class="hero-content">
			<span class="eyebrow"><?php esc_html_e( 'Clarity on Paper', 'scensara' ); ?></span>
			<h1><?php esc_html_e( 'Quiet the noise. Narrow down what truly matters.', 'scensara' ); ?></h1>
			<p><?php esc_html_e( 'Scensara creates minimalist, feminine guided journals built around deeply intentional questions, helping thoughtful women move from overthinking to calm, decisive clarity.', 'scensara' ); ?></p>
			<div class="hero-actions">
				<?php if ( $products_url ) : ?><a href="<?php echo esc_url( $products_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'Shop the Journal', 'scensara' ); ?></a><?php endif; ?>
				<?php if ( $about_url ) : ?><a href="<?php echo esc_url( $about_url . '#weekly-blog' ); ?>" class="btn btn-outline"><?php esc_html_e( 'Read the Weekly Blog', 'scensara' ); ?></a><?php endif; ?>
			</div>
		</div>
	</div>
</section>

<div class="proof-bar">
	<div class="container proof-bar__inner">
		<span><?php esc_html_e( '75 guided questions', 'scensara' ); ?></span>
		<span><?php esc_html_e( 'Undated pages', 'scensara' ); ?></span>
		<span><?php esc_html_e( 'Matte hardcover', 'scensara' ); ?></span>
		<span><?php esc_html_e( 'Ships worldwide', 'scensara' ); ?></span>
	</div>
</div>

<section class="section section-sand">
	<div class="container">
		<div class="section-header">
			<span class="eyebrow"><?php esc_html_e( 'The Philosophy', 'scensara' ); ?></span>
			<h2><?php esc_html_e( 'More than blank pages', 'scensara' ); ?></h2>
			<p><?php esc_html_e( 'Blank notebooks can feel overwhelming when your mind is already full. Each Scensara edition guides your reflection with precision.', 'scensara' ); ?></p>
		</div>
		<div class="grid-3">
			<div class="card"><span class="eyebrow">01 &middot; <?php esc_html_e( 'Intentional', 'scensara' ); ?></span><h3><?php esc_html_e( 'Thought-Narrowing Questions', 'scensara' ); ?></h3><p><?php esc_html_e( 'Carefully sequenced prompts cut through daily distractions and help you pinpoint your real priorities in career, relationships, and self-growth.', 'scensara' ); ?></p></div>
			<div class="card"><span class="eyebrow">02 &middot; <?php esc_html_e( 'Tactile', 'scensara' ); ?></span><h3><?php esc_html_e( 'Quiet Luxury Craftsmanship', 'scensara' ); ?></h3><p><?php esc_html_e( 'Designed in warm alabaster and travertine neutrals with smooth cream pages, generous whitespace, and a calm, unhurried layout.', 'scensara' ); ?></p></div>
			<div class="card"><span class="eyebrow">03 &middot; <?php esc_html_e( 'Undated', 'scensara' ); ?></span><h3><?php esc_html_e( 'At Your Own Rhythm', 'scensara' ); ?></h3><p><?php esc_html_e( 'No missed-day guilt. Open your journal during a slow Sunday morning or whenever you face an important life decision.', 'scensara' ); ?></p></div>
		</div>
	</div>
</section>

<?php if ( $singles ) : ?>
<section class="section">
	<div class="container">
		<div class="section-header">
			<span class="eyebrow"><?php esc_html_e( 'The Collection', 'scensara' ); ?></span>
			<h2><?php esc_html_e( 'Choose your shade', 'scensara' ); ?></h2>
			<p><?php esc_html_e( 'One journal, three quiet colourways. Adding an item takes you directly to your bag for seamless international checkout.', 'scensara' ); ?></p>
		</div>
		<div class="product-grid">
			<?php foreach ( array_slice( $singles, 0, 3 ) as $p ) { get_template_part( 'template-parts/product-card', null, array( 'product' => $p ) ); } ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section section-sand">
	<div class="container">
		<div class="about-split">
			<img src="<?php echo esc_url( scensara_asset( 'images/scensara-interior-spread.jpg' ) ); ?>" alt="<?php esc_attr_e( 'An open Scensara journal spread with a guided question', 'scensara' ); ?>" loading="lazy" width="1500" height="1000">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Inside the Journal', 'scensara' ); ?></span>
				<h2 class="split-title"><?php esc_html_e( 'One question per page. Endless room to answer.', 'scensara' ); ?></h2>
				<p class="split-copy"><?php esc_html_e( 'Five structured chapters guide you from quieting the noise to choosing your next step. Every page offers one thoughtful question and generous ruled space, with nothing to track and nothing to catch up on.', 'scensara' ); ?></p>
				<ul class="check-list">
					<li><?php esc_html_e( '5 chapters · 75 foundational questions', 'scensara' ); ?></li>
					<li><?php esc_html_e( '60# warm cream pages, built for pen', 'scensara' ); ?></li>
					<li><?php esc_html_e( '6" × 9" matte hardcover', 'scensara' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</section>

<section class="lifestyle-band" style="background-image:url('<?php echo esc_url( scensara_asset( 'images/scensara-lifestyle-bed-alabaster.jpg' ) ); ?>')">
	<div class="lifestyle-band__veil"></div>
	<div class="container lifestyle-band__inner">
		<blockquote>
			<?php esc_html_e( 'The quality of your life is shaped by the quality of the questions you ask yourself.', 'scensara' ); ?>
			<cite>Scensara</cite>
		</blockquote>
	</div>
</section>

<section id="signature-edition" class="section">
	<div class="container">
		<div class="about-split about-split--reverse">
			<img src="<?php echo esc_url( scensara_asset( 'images/scensara-lineup-3x2.jpg' ) ); ?>" alt="<?php esc_attr_e( 'The Clarity Edition hardcover in three colours', 'scensara' ); ?>" loading="lazy" width="1500" height="1000">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Signature Edition', 'scensara' ); ?></span>
				<h2 class="split-title"><?php esc_html_e( 'The Clarity Edition Hardcover', 'scensara' ); ?></h2>
				<p class="split-copy"><?php esc_html_e( 'Featuring 5 structured chapters and 75 foundational reflection questions for finding calm, decisive clarity.', 'scensara' ); ?></p>
				<?php if ( $featured ) : ?><div class="product-price"><?php echo wp_kses_post( $featured->get_price_html() ); ?></div><?php endif; ?>
				<div class="hero-actions">
					<?php if ( $featured && $featured->is_type( 'simple' ) && $featured->is_purchasable() ) : ?>
						<a class="btn btn-primary" href="<?php echo esc_url( $featured->add_to_cart_url() ); ?>" rel="nofollow"><?php esc_html_e( 'Add to Bag & Checkout', 'scensara' ); ?></a>
					<?php endif; ?>
					<?php if ( $products_url ) : ?><a href="<?php echo esc_url( $products_url ); ?>" class="btn btn-outline"><?php esc_html_e( 'View All Editions', 'scensara' ); ?></a><?php endif; ?>
				</div>
				<?php if ( $bundle ) : ?>
					<p class="gift-hint"><?php printf( /* translators: 1: link open, 2: link close */ esc_html__( 'Gifting? Try %1$sThe Intentional Duo Set%2$s, two journals, one thoughtful present.', 'scensara' ), '<a href="' . esc_url( $bundle->get_permalink() ) . '">', '</a>' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
$latest = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );
if ( $latest->have_posts() ) :
	?>
<section class="section section-sand">
	<div class="container">
		<div class="section-header">
			<span class="eyebrow"><?php esc_html_e( 'The Sunday Edit', 'scensara' ); ?></span>
			<h2><?php esc_html_e( 'Weekly Reflection Blog', 'scensara' ); ?></h2>
			<p><?php esc_html_e( 'One thought-narrowing question, every Sunday.', 'scensara' ); ?></p>
		</div>
		<div class="blog-grid">
			<?php while ( $latest->have_posts() ) : $latest->the_post(); get_template_part( 'template-parts/blog-card' ); endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
if ( is_page() && have_posts() ) {
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
