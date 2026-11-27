<?php
/**
 * One-time setup on activation: pages, menus, reading settings, products, sample essays.
 * Safe to re-run: nothing is overwritten once it exists.
 *
 * @package Scensara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Page definitions.
 */
function scensara_page_definitions() {
	return array(
		'home'     => array( 'title' => 'Home', 'template' => '', 'content' => '' ),
		'products' => array( 'title' => 'Products', 'template' => 'page-products.php', 'content' => '' ),
		'about'    => array( 'title' => 'About & Blog', 'template' => 'page-about.php', 'content' => '' ),
		'contact'  => array( 'title' => 'Contact', 'template' => 'page-contact.php', 'content' => '' ),
		'faq'      => array( 'title' => 'Shipping & FAQ', 'template' => 'page-faq.php', 'content' => '' ),
		'journal'  => array( 'title' => 'Journal', 'template' => '', 'content' => '' ),
	);
}

/**
 * Create or find a page; returns its ID.
 */
function scensara_ensure_page( $slug, $def ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		return (int) $existing->ID;
	}
	if ( $def['template'] ) {
		$found = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => $def['template'],
			'fields'         => 'ids',
		) );
		if ( $found ) {
			return (int) $found[0];
		}
	}
	$id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $def['title'],
		'post_name'    => $slug,
		'post_content' => $def['content'],
	) );
	if ( $id && ! is_wp_error( $id ) && $def['template'] ) {
		update_post_meta( $id, '_wp_page_template', $def['template'] );
	}
	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * Pages + reading settings + WooCommerce page wiring.
 */
function scensara_setup_pages() {
	$ids = array();
	foreach ( scensara_page_definitions() as $slug => $def ) {
		$ids[ $slug ] = scensara_ensure_page( $slug, $def );
	}

	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}
	if ( ! empty( $ids['journal'] ) ) {
		update_option( 'page_for_posts', $ids['journal'] );
	}

	if ( scensara_has_wc() ) {
		// Products is the WooCommerce shop page.
		if ( ! empty( $ids['products'] ) ) {
			update_option( 'woocommerce_shop_page_id', $ids['products'] );
		}
		// Cart and Checkout must be classic shortcodes so the theme templates and every payment gateway apply.
		$map = array(
			'cart'     => '[woocommerce_cart]',
			'checkout' => '[woocommerce_checkout]',
		);
		foreach ( $map as $key => $shortcode ) {
			$page_id = wc_get_page_id( $key );
			if ( $page_id > 0 ) {
				$post = get_post( $page_id );
				if ( $post && false === strpos( $post->post_content, $shortcode ) ) {
					wp_update_post( array( 'ID' => $page_id, 'post_content' => $shortcode ) );
				}
			}
		}
	}

	return $ids;
}

/**
 * Primary and footer menus.
 */
function scensara_setup_menus( $ids ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	if ( empty( $locations['primary'] ) ) {
		$menu = wp_get_nav_menu_object( 'Scensara Primary' );
		$id   = $menu ? $menu->term_id : wp_create_nav_menu( 'Scensara Primary' );
		if ( ! is_wp_error( $id ) ) {
			if ( ! $menu ) {
				$items = array(
					array( 'Home', 'home' ),
					array( 'Products', 'products' ),
					array( 'About & Blog', 'about' ),
					array( 'Contact', 'contact' ),
					array( 'FAQ', 'faq' ),
				);
				foreach ( $items as $item ) {
					if ( empty( $ids[ $item[1] ] ) ) {
						continue;
					}
					wp_update_nav_menu_item( $id, 0, array(
						'menu-item-title'     => $item[0],
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $ids[ $item[1] ],
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					) );
				}
			}
			$locations['primary'] = $id;
		}
	}

	if ( empty( $locations['footer'] ) ) {
		$menu = wp_get_nav_menu_object( 'Scensara Footer' );
		$id   = $menu ? $menu->term_id : wp_create_nav_menu( 'Scensara Footer' );
		if ( ! is_wp_error( $id ) ) {
			if ( ! $menu ) {
				$items = array(
					array( 'The Clarity Collection', 'products' ),
					array( 'Our Story & Weekly Blog', 'about' ),
					array( 'Shipping & FAQ', 'faq' ),
					array( 'Contact & Wholesale', 'contact' ),
				);
				foreach ( $items as $item ) {
					if ( empty( $ids[ $item[1] ] ) ) {
						continue;
					}
					wp_update_nav_menu_item( $id, 0, array(
						'menu-item-title'     => $item[0],
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $ids[ $item[1] ],
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					) );
				}
			}
			$locations['footer'] = $id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Copy a bundled theme image into the Media Library; returns attachment ID.
 */
function scensara_import_image( $filename, $alt = '' ) {
	$source = get_template_directory() . '/images/' . $filename;
	if ( ! file_exists( $source ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$existing = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 1,
		'meta_key'       => '_scensara_source',
		'meta_value'     => $filename,
		'fields'         => 'ids',
	) );
	if ( $existing ) {
		return (int) $existing[0];
	}

	$upload = wp_upload_bits( $filename, null, file_get_contents( $source ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$type   = wp_check_filetype( $upload['file'] );
	$attach = wp_insert_attachment( array(
		'post_mime_type' => $type['type'],
		'post_title'     => sanitize_text_field( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_status'    => 'inherit',
	), $upload['file'] );
	if ( is_wp_error( $attach ) || ! $attach ) {
		return 0;
	}
	wp_update_attachment_metadata( $attach, wp_generate_attachment_metadata( $attach, $upload['file'] ) );
	update_post_meta( $attach, '_scensara_source', $filename );
	if ( $alt ) {
		update_post_meta( $attach, '_wp_attachment_image_alt', $alt );
	}
	return (int) $attach;
}

/**
 * Seed the four collection products (only when a product with the SKU does not exist yet).
 */
function scensara_setup_products() {
	if ( ! scensara_has_wc() ) {
		return;
	}

	$long = "<p>The Clarity Edition is a guided journal for women who want to move from overthinking to calm, decisive clarity. Five chapters and 75 thought-narrowing questions help you name what matters, set boundaries and choose your next step, one page at a time.</p>"
		. "<ul><li>6\" × 9\" matte hardcover</li><li>60# warm cream pages with generous ruled space</li><li>5 chapters · 75 guided questions</li><li>Undated, so you can begin any day</li></ul>"
		. "<p>Printed on demand and shipped worldwide.</p>";

	$items = array(
		array(
			'sku'   => 'clarity-alabaster',
			'name'  => 'The Clarity Edition: Warm Alabaster',
			'cover' => 'Warm Alabaster',
			'price' => '36.00',
			'short' => 'A soft, luminous alabaster cover for slow mornings. Five chapters, 75 guided questions, undated.',
			'main'  => 'scensara-packshot-front-alabaster.jpg',
			'gallery' => array( 'scensara-lifestyle-bed-alabaster.jpg', 'scensara-interior-spread.jpg', 'scensara-lineup-square.jpg' ),
		),
		array(
			'sku'   => 'clarity-sand',
			'name'  => 'The Clarity Edition: Soft Linen Sand',
			'cover' => 'Soft Linen Sand',
			'price' => '36.00',
			'short' => 'A warm, textured linen-sand cover that feels quiet and grounded. Five chapters, 75 guided questions, undated.',
			'main'  => 'scensara-packshot-34-linen-sand.jpg',
			'gallery' => array( 'scensara-lifestyle-bed-linen-sand.jpg', 'scensara-interior-spread.jpg', 'scensara-lineup-square.jpg' ),
		),
		array(
			'sku'   => 'clarity-espresso',
			'name'  => 'The Clarity Edition: Deep Espresso',
			'cover' => 'Deep Espresso',
			'price' => '36.00',
			'short' => 'A deep espresso cover with champagne-gold type, made for evening reflection. Five chapters, 75 guided questions, undated.',
			'main'  => 'scensara-packshot-front-espresso.jpg',
			'gallery' => array( 'scensara-packshot-34-espresso.jpg', 'scensara-lifestyle-bed-espresso.jpg', 'scensara-interior-spread.jpg' ),
		),
		array(
			'sku'   => 'clarity-duo',
			'name'  => 'The Intentional Duo Set',
			'cover' => 'Gift Bundle',
			'price' => '66.00',
			'short' => 'Two Clarity Editions in your choice of covers: one for you, one for someone you love. A ready-to-gift bundle.',
			'main'  => 'scensara-lineup-3x2.jpg',
			'gallery' => array( 'scensara-lineup-square.jpg', 'scensara-lifestyle-bed-alabaster.jpg' ),
		),
	);

	$order = 0;
	foreach ( $items as $item ) {
		$order++;
		if ( wc_get_product_id_by_sku( $item['sku'] ) ) {
			continue;
		}
		$product = new WC_Product_Simple();
		$product->set_name( $item['name'] );
		$product->set_sku( $item['sku'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_regular_price( $item['price'] );
		$product->set_description( $long );
		$product->set_short_description( $item['short'] );
		$product->set_manage_stock( false );
		$product->set_stock_status( 'instock' );
		$product->set_sold_individually( false );
		$product->set_virtual( false );
		$product->set_menu_order( $order );

		$attr = new WC_Product_Attribute();
		$attr->set_id( 0 );
		$attr->set_name( 'Cover' );
		$attr->set_options( array( $item['cover'] ) );
		$attr->set_position( 0 );
		$attr->set_visible( true );
		$attr->set_variation( false );
		$product->set_attributes( array( $attr ) );

		$main = scensara_import_image( $item['main'], $item['name'] );
		if ( $main ) {
			$product->set_image_id( $main );
		}
		$gallery = array();
		foreach ( $item['gallery'] as $file ) {
			$gid = scensara_import_image( $file, $item['name'] );
			if ( $gid ) {
				$gallery[] = $gid;
			}
		}
		$product->set_gallery_image_ids( $gallery );
		$product->save();
	}
}

/**
 * Three starter essays for the weekly blog (published once, never duplicated).
 */
function scensara_setup_posts() {
	if ( get_option( 'scensara_posts_seeded' ) ) {
		return;
	}
	$existing = get_posts( array( 'post_type' => 'post', 'post_status' => array( 'publish', 'draft' ), 'posts_per_page' => 1, 'fields' => 'ids' ) );
	// Hello World alone does not count as real content.
	$real = array_filter( $existing, function ( $id ) {
		return 'hello-world' !== get_post_field( 'post_name', $id );
	} );
	if ( $real ) {
		update_option( 'scensara_posts_seeded', 1 );
		return;
	}

	$posts = array(
		array(
			'Week 01 · Clarity',
			'How to Spot the Difference Between Busy and Aligned',
			'When every task feels urgent, it is easy to mistake constant motion for meaningful progress. Here is a three-question filter to reclaim your week.',
			"<p>When every task feels urgent, it is easy to mistake constant motion for meaningful progress. Here is a three-question filter to reclaim your week.</p><p>Ask yourself this Sunday:</p><ol><li>If I could only complete one outcome this month, what would make everything else easier?</li><li>Which commitment am I keeping solely out of habit?</li><li>What would happen if I paused non-essential tasks for seven days?</li></ol>",
		),
		array(
			'Week 02 · Decision Fatigue',
			'The Art of Narrowing Your Choices',
			'Overthinking rarely comes from a lack of intelligence. It comes from holding too many unfiltered options in your head at once.',
			"<p>Overthinking rarely comes from a lack of intelligence. It comes from holding too many unfiltered options in your head at once.</p><p>Write down every open loop sitting in your mind right now. Circle only the two decisions that will still matter six months from today, and give yourself permission to set the rest aside until next week.</p>",
		),
		array(
			'Week 03 · Boundaries',
			'Designing a Quiet Morning Ritual',
			'Ten minutes with pen and paper before checking your phone can shift your entire nervous system from reactive to intentional.',
			"<p>Ten minutes with pen and paper before checking your phone can shift your entire nervous system from reactive to intentional.</p><p>Keep your Scensara journal and favourite pen beside your bedside or coffee corner. Before opening messages, answer one prompt honestly without editing your handwriting.</p>",
		),
	);
	$offset = count( $posts );
	foreach ( $posts as $p ) {
		wp_insert_post( array(
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_title'   => $p[1],
			'post_excerpt' => $p[2],
			'post_content' => $p[3],
			'post_date'    => gmdate( 'Y-m-d H:i:s', time() - ( $offset-- * DAY_IN_SECONDS ) ),
			'meta_input'   => array( '_scensara_kicker' => $p[0] ),
		) );
	}
	update_option( 'scensara_posts_seeded', 1 );
}

/**
 * Run the full setup.
 */
function scensara_run_setup() {
	$ids = scensara_setup_pages();
	scensara_setup_menus( $ids );
	scensara_setup_products();
	scensara_setup_posts();
	update_option( 'scensara_setup_version', SCENSARA_VERSION );
	if ( function_exists( 'wc_delete_product_transients' ) ) {
		wc_delete_product_transients( 0 );
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'scensara_run_setup' );

/**
 * Appearance > Scensara Setup: re-run the setup and see the Lulu / payment checklist.
 */
function scensara_setup_menu() {
	add_theme_page( __( 'Scensara Setup', 'scensara' ), __( 'Scensara Setup', 'scensara' ), 'manage_options', 'scensara-setup', 'scensara_setup_screen' );
}
add_action( 'admin_menu', 'scensara_setup_menu' );

function scensara_setup_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( isset( $_POST['scensara_rerun'] ) && check_admin_referer( 'scensara_rerun' ) ) {
		scensara_run_setup();
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Setup finished. Pages, menus and any missing products were created.', 'scensara' ) . '</p></div>';
	}
	$wc = scensara_has_wc();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Scensara Setup', 'scensara' ); ?></h1>
		<p><?php esc_html_e( 'Creates the Home, Products, About & Blog, Contact, Shipping & FAQ and Journal pages, the menus, the four collection products and three starter essays. Nothing that already exists is overwritten.', 'scensara' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'scensara_rerun' ); ?>
			<p><button class="button button-primary" name="scensara_rerun" value="1"><?php esc_html_e( 'Run setup', 'scensara' ); ?></button></p>
		</form>
		<h2><?php esc_html_e( 'Store checklist', 'scensara' ); ?></h2>
		<ol>
			<li><?php echo $wc ? '✅' : '❌'; ?> <?php esc_html_e( 'WooCommerce is active', 'scensara' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce > Settings > Payments: enable PayPal and WiPay and complete their merchant connection.', 'scensara' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce > Settings > Shipping: add zones/rates (and a free-shipping rule at $60 if you keep the announcement text).', 'scensara' ); ?></li>
			<li><?php esc_html_e( 'Lulu Direct: in your Lulu account open Connect a store, choose WooCommerce, enter this site URL and authorise it, then link each product to its Lulu print job.', 'scensara' ); ?></li>
			<li><?php esc_html_e( 'Contact form mail: install an SMTP plugin (for example WP Mail SMTP) so messages reach your inbox reliably.', 'scensara' ); ?></li>
		</ol>
	</div>
	<?php
}
