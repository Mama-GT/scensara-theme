<?php
/**
 * Blog card.
 *
 * @package Scensara
 */
$kicker = get_post_meta( get_the_ID(), '_scensara_kicker', true );
?>
<article <?php post_class( 'blog-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="blog-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'scensara-editorial', array( 'loading' => 'lazy' ) ); ?></a>
	<?php endif; ?>
	<span class="blog-meta"><?php echo esc_html( $kicker ? $kicker : get_the_date() ); ?></span>
	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></p>
	<a class="blog-read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Full Entry', 'scensara' ); ?></a>
</article>
