<?php
/**
 * WooCommerce integration.
 *
 * Cart and checkout are WooCommerce's own flows (templates overridden in /woocommerce),
 * so PayPal, WooPayments, WiPay and Lulu Direct order sync keep working natively.
 *
 * @package Scensara
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * The theme ships complete WooCommerce styling in style.css.
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Catalog layout.
 */
add_filter( 'loop_shop_columns', function () {
	return 3;
} );
add_filter( 'loop_shop_per_page', function () {
	return 12;
} );

/**
 * Remove default wrappers/sidebar; woocommerce.php provides the Scensara layout.
 */
function scensara_woo_cleanup() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	// Single product summary order.
	add_action( 'woocommerce_single_product_summary', 'scensara_single_eyebrow', 4 );
	add_action( 'woocommerce_single_product_summary', 'scensara_single_trust', 35 );
	add_action( 'woocommerce_after_single_product_summary', 'scensara_single_story', 8 );
}
add_action( 'wp', 'scensara_woo_cleanup' );

/**
 * Bag fragment (live badge).
 */
function scensara_cart_fragment( $fragments ) {
	$fragments['a.cart-link'] = scensara_cart_link_html();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'scensara_cart_fragment' );

/**
 * Adding to the bag takes the shopper directly to the bag (as on the original storefront).
 */
add_filter( 'woocommerce_add_to_cart_redirect', function ( $url ) {
	return wc_get_cart_url();
} );
add_filter( 'woocommerce_cart_redirect_after_add', '__return_true' );
add_filter( 'woocommerce_add_to_cart_message_html', '__return_empty_string' );
add_filter( 'woocommerce_return_to_shop_redirect', function () {
	return scensara_page_url( 'products' ) ?: wc_get_page_permalink( 'shop' );
} );

/**
 * Wording.
 */
add_filter( 'woocommerce_product_single_add_to_cart_text', function () {
	return __( 'Add to Bag', 'scensara' );
} );
add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
	return ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) ? __( 'Add to Bag', 'scensara' ) : $text;
}, 10, 2 );
add_filter( 'woocommerce_order_button_text', function () {
	return __( 'Complete Order', 'scensara' );
} );
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
	if ( isset( $fields['order']['order_comments'] ) ) {
		$fields['order']['order_comments']['label']       = __( 'Gift note or delivery notes (optional)', 'scensara' );
		$fields['order']['order_comments']['placeholder'] = __( 'Add a gift message, and we never include pricing on packing slips.', 'scensara' );
	}
	return $fields;
} );

/**
 * Free shipping threshold used by the bag progress note.
 */
function scensara_free_shipping_threshold() {
	return (float) apply_filters( 'scensara_free_shipping_threshold', 60 );
}

/**
 * Small eyebrow above the product title (Cover attribute, e.g. "Warm Alabaster").
 */
function scensara_single_eyebrow() {
	global $product;
	$cover = $product->get_attribute( 'Cover' );
	echo '<span class="eyebrow">' . esc_html( $cover ? $cover : __( 'The Clarity Edition', 'scensara' ) ) . '</span>';
}

/**
 * Reassurance strip beside the add to bag button.
 */
function scensara_single_trust() {
	$items = array(
		array( __( 'Worldwide shipping', 'scensara' ), __( 'Printed and dispatched from regional fulfilment centres in 4–8 business days.', 'scensara' ) ),
		array( __( 'Gift-ready', 'scensara' ), __( 'Add a note at checkout. We never print prices on packing slips.', 'scensara' ) ),
		array( __( 'Undated pages', 'scensara' ), __( 'Write at your own rhythm, with no missed-day guilt.', 'scensara' ) ),
	);
	echo '<ul class="trust-strip">';
	foreach ( $items as $item ) {
		printf(
			'<li><strong>%1$s</strong><span>%2$s</span></li>',
			esc_html( $item[0] ),
			esc_html( $item[1] )
		);
	}
	echo '</ul>';
}

/**
 * Rich editorial story under the product: inside the journal, how a page works, details, FAQ.
 */
function scensara_single_story() {
	$spread = scensara_asset( 'images/scensara-interior-spread.jpg' );
	$bed    = scensara_asset( 'images/scensara-lifestyle-bed-alabaster.jpg' );
	?>
	<section class="product-story" aria-label="<?php esc_attr_e( 'About this journal', 'scensara' ); ?>">
		<div class="story-split">
			<img src="<?php echo esc_url( $spread ); ?>" alt="<?php esc_attr_e( 'Inside The Clarity Edition: one thoughtful question per page', 'scensara' ); ?>" loading="lazy" width="1500" height="1000">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Inside the journal', 'scensara' ); ?></span>
				<h2><?php esc_html_e( 'One question. One page. One clear next step.', 'scensara' ); ?></h2>
				<p><?php esc_html_e( 'Instead of staring at a blank page, you are guided step by step through five chapters and 75 thought-narrowing questions that clarify priorities, boundaries and decisions.', 'scensara' ); ?></p>
				<ul class="check-list">
					<li><?php esc_html_e( 'Five chapters, from quieting the noise to choosing what comes next', 'scensara' ); ?></li>
					<li><?php esc_html_e( 'Generous ruled space, so every answer has room to breathe', 'scensara' ); ?></li>
					<li><?php esc_html_e( 'Completely undated. Begin on any day, on any page', 'scensara' ); ?></li>
				</ul>
			</div>
		</div>

		<div class="ritual-steps">
			<div class="section-header">
				<span class="eyebrow"><?php esc_html_e( 'A ten-minute ritual', 'scensara' ); ?></span>
				<h2><?php esc_html_e( 'How it works', 'scensara' ); ?></h2>
			</div>
			<div class="grid-3">
				<div class="card"><span class="step-no">01</span><h3><?php esc_html_e( 'Pause', 'scensara' ); ?></h3><p><?php esc_html_e( 'Keep the journal beside your coffee or bedside. Before opening messages, turn to your next page.', 'scensara' ); ?></p></div>
				<div class="card"><span class="step-no">02</span><h3><?php esc_html_e( 'Answer', 'scensara' ); ?></h3><p><?php esc_html_e( 'Respond to one deliberate question, honestly and without editing your handwriting.', 'scensara' ); ?></p></div>
				<div class="card"><span class="step-no">03</span><h3><?php esc_html_e( 'Decide', 'scensara' ); ?></h3><p><?php esc_html_e( 'Circle the one thing that matters most today, and let the rest wait until tomorrow.', 'scensara' ); ?></p></div>
			</div>
		</div>

		<div class="story-split story-split--reverse">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Craftsmanship', 'scensara' ); ?></span>
				<h2><?php esc_html_e( 'Quiet luxury, in your hands', 'scensara' ); ?></h2>
				<dl class="spec-list">
					<div><dt><?php esc_html_e( 'Format', 'scensara' ); ?></dt><dd><?php esc_html_e( '6" × 9" matte hardcover', 'scensara' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Pages', 'scensara' ); ?></dt><dd><?php esc_html_e( '60# warm cream paper', 'scensara' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Content', 'scensara' ); ?></dt><dd><?php esc_html_e( '5 chapters · 75 guided questions', 'scensara' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Made', 'scensara' ); ?></dt><dd><?php esc_html_e( 'Printed on demand, close to you', 'scensara' ); ?></dd></div>
				</dl>
			</div>
			<img src="<?php echo esc_url( $bed ); ?>" alt="<?php esc_attr_e( 'The Clarity Edition on a bed beside morning coffee', 'scensara' ); ?>" loading="lazy" width="1500" height="1000">
		</div>

		<div class="faq-list product-faq">
			<?php
			$faqs = array(
				array( __( 'Where does Scensara ship?', 'scensara' ), __( 'We ship internationally across the United States, Canada, the United Kingdom, Europe and the Caribbean. Orders are printed and dispatched from regional fulfilment centres for delivery in 4–8 business days.', 'scensara' ) ),
				array( __( 'Are the pages dated?', 'scensara' ), __( 'No. Every Scensara journal is undated, so you can write at your own pace.', 'scensara' ) ),
				array( __( 'Can I send it directly as a gift?', 'scensara' ), __( 'Yes. Enter your recipient’s address at checkout and add a gift note. We never include pricing on packing slips.', 'scensara' ) ),
			);
			foreach ( $faqs as $i => $faq ) :
				?>
				<div class="faq-item<?php echo 0 === $i ? ' open' : ''; ?>">
					<button type="button" class="faq-question" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>">
						<span><?php echo esc_html( $faq[0] ); ?></span><span class="faq-icon" aria-hidden="true">+</span>
					</button>
					<div class="faq-answer"><?php echo esc_html( $faq[1] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

/**
 * Sticky add-to-bag bar on single product pages.
 */
function scensara_sticky_bar() {
	if ( ! is_product() ) {
		return;
	}
	$product = wc_get_product( get_the_ID() );
	if ( ! $product ) {
		return;
	}
	$direct = $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock();
	$url    = $direct ? $product->add_to_cart_url() : '#product-' . $product->get_id();
	?>
	<div class="sticky-bag" id="sticky-bag" aria-hidden="true">
		<div class="container sticky-bag__inner">
			<div class="sticky-bag__meta">
				<span class="sticky-bag__title"><?php echo esc_html( $product->get_name() ); ?></span>
				<span class="sticky-bag__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			</div>
			<a class="btn btn-primary" href="<?php echo esc_url( $url ); ?>" tabindex="-1"><?php echo $direct ? esc_html__( 'Add to Bag', 'scensara' ) : esc_html__( 'Choose options', 'scensara' ); ?></a>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'scensara_sticky_bar' );
