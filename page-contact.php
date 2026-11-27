<?php
/**
 * Template Name: Scensara Contact
 *
 * @package Scensara
 */
get_header();
$info = scensara_contact_info();
?>
<main id="main-content" tabindex="-1">
	<div class="page-banner"><div class="container">
		<span class="eyebrow"><?php esc_html_e( 'Get in Touch', 'scensara' ); ?></span>
		<h1><?php esc_html_e( 'Contact Scensara', 'scensara' ); ?></h1>
		<p><?php esc_html_e( 'Questions about your order, international shipping, or boutique stockist partnerships? Send us a note below.', 'scensara' ); ?></p>
	</div></div>

	<section class="section"><div class="container">
		<div class="contact-layout">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Studio & Support', 'scensara' ); ?></span>
				<h2 class="split-title"><?php esc_html_e( 'We would love to hear from you', 'scensara' ); ?></h2>
				<p class="split-copy"><?php esc_html_e( 'Our team responds to all customer and wholesale inquiries within 1–2 business days.', 'scensara' ); ?></p>
				<div class="card contact-card">
					<p><strong><?php esc_html_e( 'Customer Care:', 'scensara' ); ?></strong> <a href="mailto:<?php echo esc_attr( antispambot( $info['care'] ) ); ?>"><?php echo esc_html( antispambot( $info['care'] ) ); ?></a></p>
					<p><strong><?php esc_html_e( 'Wholesale & Retreats:', 'scensara' ); ?></strong> <a href="mailto:<?php echo esc_attr( antispambot( $info['wholesale'] ) ); ?>"><?php echo esc_html( antispambot( $info['wholesale'] ) ); ?></a></p>
					<p><strong><?php esc_html_e( 'Instagram & Pinterest:', 'scensara' ); ?></strong> <?php echo esc_html( $info['social'] ); ?></p>
				</div>
			</div>

			<div class="card">
				<form id="scensara-contact-form" novalidate>
					<div class="form-group">
						<label for="contact-name"><?php esc_html_e( 'Full Name', 'scensara' ); ?></label>
						<input type="text" id="contact-name" name="name" class="form-control" placeholder="<?php esc_attr_e( 'e.g., Maya Laurent', 'scensara' ); ?>" autocomplete="name" required>
						<span id="contact-name-error" class="field-error"></span>
					</div>
					<div class="form-group">
						<label for="contact-email"><?php esc_html_e( 'Email Address', 'scensara' ); ?></label>
						<input type="email" id="contact-email" name="email" class="form-control" placeholder="maya@example.com" autocomplete="email" required>
						<span id="contact-email-error" class="field-error"></span>
					</div>
					<div class="form-group">
						<label for="contact-topic"><?php esc_html_e( 'Inquiry Topic', 'scensara' ); ?></label>
						<select id="contact-topic" name="topic" class="form-control">
							<option value="Order Inquiry"><?php esc_html_e( 'Order & International Shipping', 'scensara' ); ?></option>
							<option value="Wholesale Inquiry"><?php esc_html_e( 'Boutique / Studio Wholesale', 'scensara' ); ?></option>
							<option value="Press & Gifting"><?php esc_html_e( 'Press & Event Gifting', 'scensara' ); ?></option>
							<option value="General Inquiry"><?php esc_html_e( 'General Question', 'scensara' ); ?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="contact-message"><?php esc_html_e( 'Message', 'scensara' ); ?></label>
						<textarea id="contact-message" name="message" rows="5" class="form-control" placeholder="<?php esc_attr_e( 'How can we help you?', 'scensara' ); ?>" required></textarea>
						<span id="contact-message-error" class="field-error"></span>
					</div>
					<div class="hp-field" aria-hidden="true"><label for="contact-website">Website</label><input type="text" id="contact-website" name="website" tabindex="-1" autocomplete="off"></div>
					<button type="submit" class="btn btn-primary" style="width:100%"><?php esc_html_e( 'Send Message', 'scensara' ); ?></button>
					<div id="contact-status" class="form-status" role="alert"></div>
				</form>
			</div>
		</div>
	</div></section>
</main>
<?php get_footer(); ?>
