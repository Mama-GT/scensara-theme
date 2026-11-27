<?php
/**
 * Customizer settings.
 *
 * @package Scensara
 */

defined( 'ABSPATH' ) || exit;

function scensara_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'scensara_storefront', array(
		'title'    => __( 'Scensara Storefront', 'scensara' ),
		'priority' => 30,
	) );

	$text = array(
		'scensara_announcement'     => array( __( 'Announcement bar text', 'scensara' ), 'Worldwide Shipping · Complimentary Delivery on Orders $60+ USD' ),
		'scensara_email_care'       => array( __( 'Customer care email', 'scensara' ), 'hello@scensara.com' ),
		'scensara_email_wholesale'  => array( __( 'Wholesale email', 'scensara' ), 'partnerships@scensara.com' ),
		'scensara_social_handle'    => array( __( 'Instagram / Pinterest handle', 'scensara' ), '@scensara' ),
	);
	foreach ( $text as $id => $info ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $info[1],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $id, array(
			'section' => 'scensara_storefront',
			'label'   => $info[0],
			'type'    => 'text',
		) );
	}

	$wp_customize->add_setting( 'scensara_hero_video', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'scensara_hero_video', array(
		'section'     => 'scensara_storefront',
		'label'       => __( 'Optional home hero video (short, muted MP4)', 'scensara' ),
		'description' => __( 'Plays softly behind the sunlight animation. Leave empty to use the still hero image.', 'scensara' ),
		'mime_type'   => 'video',
	) ) );

	$wp_customize->add_setting( 'scensara_hero_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'scensara_hero_image', array(
		'section'   => 'scensara_storefront',
		'label'     => __( 'Hero image override (16:9)', 'scensara' ),
		'mime_type' => 'image',
	) ) );
}
add_action( 'customize_register', 'scensara_customize_register' );
