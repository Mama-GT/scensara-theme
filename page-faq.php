<?php
/**
 * Template Name: Scensara Shipping & FAQ
 *
 * @package Scensara
 */
get_header();
$faqs = array(
	array( __( 'Where does Scensara ship?', 'scensara' ), __( 'We ship internationally across the United States, Canada, the United Kingdom, Europe, and the Caribbean. Orders are printed and dispatched from regional fulfillment centers to ensure fast delivery in 4–8 business days.', 'scensara' ) ),
	array( __( 'What makes Scensara different from a regular notebook?', 'scensara' ), __( 'Every Scensara journal is structured around 75 curated, thought-narrowing questions organized into five thematic chapters. Instead of staring at a blank page, you are guided step by step to clarify priorities, boundaries, and next decisions.', 'scensara' ) ),
	array( __( 'Are the pages dated?', 'scensara' ), __( 'No. All Scensara journals are completely undated so you can write at your own pace without feeling behind if you skip a day or week.', 'scensara' ) ),
	array( __( 'Can I send a journal directly as a gift?', 'scensara' ), __( 'Yes. Simply enter your recipient’s shipping address at checkout and include a complimentary gift note. We never include pricing on packing slips.', 'scensara' ) ),
);
?>
<main id="main-content" tabindex="-1">
	<div class="page-banner"><div class="container">
		<span class="eyebrow"><?php esc_html_e( 'Helpful Details', 'scensara' ); ?></span>
		<h1><?php esc_html_e( 'Shipping & FAQ', 'scensara' ); ?></h1>
		<p><?php esc_html_e( 'Everything you need to know about our journal craftsmanship, international delivery, and gifting.', 'scensara' ); ?></p>
	</div></div>
	<section class="section"><div class="container">
		<div class="faq-list">
			<?php foreach ( $faqs as $i => $faq ) : ?>
				<div class="faq-item<?php echo 0 === $i ? ' open' : ''; ?>">
					<button type="button" class="faq-question" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>">
						<span><?php echo esc_html( $faq[0] ); ?></span><span class="faq-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-answer"><?php echo esc_html( $faq[1] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		if ( have_posts() ) {
			the_post();
			if ( trim( get_the_content() ) ) {
				echo '<div class="entry-content" style="margin-top:48px">';
				the_content();
				echo '</div>';
			}
		}
		?>
	</div></section>
</main>
<?php get_footer(); ?>
