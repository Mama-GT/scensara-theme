<?php
/**
 * Contact form AJAX handler (admin-ajax.php + nonce + wp_mail).
 *
 * @package Scensara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return a fresh nonce so full-page caching (LiteSpeed) cannot serve a stale one.
 */
function scensara_contact_fresh_nonce() {
	wp_send_json_success( array( 'nonce' => wp_create_nonce( 'scensara_contact' ) ) );
}
add_action( 'wp_ajax_scensara_contact_nonce', 'scensara_contact_fresh_nonce' );
add_action( 'wp_ajax_nopriv_scensara_contact_nonce', 'scensara_contact_fresh_nonce' );

function scensara_contact_submit() {
	if ( ! check_ajax_referer( 'scensara_contact', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Your session expired. Please refresh the page and try again.', 'scensara' ) ), 403 );
	}

	// Honeypot: real visitors never fill this in.
	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success( array( 'message' => __( 'Thank you. Your message has been received.', 'scensara' ) ) );
	}

	// Simple rate limit: 5 messages per hour per IP.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'scensara_cf_' . md5( $ip );
	$hit = (int) get_transient( $key );
	if ( $hit >= 5 ) {
		wp_send_json_error( array( 'message' => __( 'You have sent several messages recently. Please try again later.', 'scensara' ) ), 429 );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$topic   = isset( $_POST['topic'] ) ? sanitize_text_field( wp_unslash( $_POST['topic'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	$topics = array( 'Order Inquiry', 'Wholesale Inquiry', 'Press & Gifting', 'General Inquiry' );
	if ( ! in_array( $topic, $topics, true ) ) {
		$topic = 'General Inquiry';
	}

	$errors = array();
	if ( mb_strlen( $name ) < 2 ) {
		$errors['name'] = __( 'Please enter your full name.', 'scensara' );
	}
	if ( ! is_email( $email ) ) {
		$errors['email'] = __( 'Please enter a valid email address.', 'scensara' );
	}
	if ( mb_strlen( $message ) < 10 ) {
		$errors['message'] = __( 'Please include a message of at least 10 characters.', 'scensara' );
	}
	if ( $errors ) {
		wp_send_json_error( array( 'message' => __( 'Please review the highlighted fields.', 'scensara' ), 'errors' => $errors ), 422 );
	}

	$to      = get_option( 'admin_email' );
	$subject = sprintf( '[Scensara] %s from %s', $topic, $name );
	$body    = sprintf(
		"Name: %s\nEmail: %s\nTopic: %s\n\nMessage:\n%s\n\n--\nSent from the %s contact form (%s)",
		$name,
		$email,
		$topic,
		$message,
		get_bloginfo( 'name' ),
		home_url( '/' )
	);
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', str_replace( array( "\r", "\n", ',' ), '', $name ), $email ),
	);

	$sent = wp_mail( $to, $subject, $body, $headers );
	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => __( 'We could not send your message right now. Please email us directly.', 'scensara' ) ), 500 );
	}

	set_transient( $key, $hit + 1, HOUR_IN_SECONDS );

	wp_send_json_success( array(
		'message' => sprintf(
			/* translators: 1: visitor first name, 2: topic */
			__( 'Thank you, %1$s. Your %2$s has been received by the Scensara studio. We reply within 1–2 business days.', 'scensara' ),
			$name,
			strtolower( $topic )
		),
	) );
}
add_action( 'wp_ajax_scensara_contact', 'scensara_contact_submit' );
add_action( 'wp_ajax_nopriv_scensara_contact', 'scensara_contact_submit' );
