<?php
/**
 * Hero template.
 *
 * @package terminal
 */

?>
<div 
	id="hero-secondary-post-<?php the_ID(); ?>"
	<?php
	printf(
		'class="%s terminal-hero-secondary-widget "',
		! is_singular() ? 'terminal-post-tracking' : ''
	);
	?>
	data-terminal-post-id="<?php the_ID(); ?>"
	data-terminal-has-image="<?php echo has_post_thumbnail(); ?>"
	data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
	data-terminal-title="<?php the_title_attribute(); ?>"
	data-terminal-view="hero-secondary"
>
	<?php
	if ( has_post_thumbnail() ) :
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'terminal-uncut-thumbnail-large' );
	?>
		<?php
			printf(
				'<a id="terminal-secondary-hero-image-%s" href="%s" class="terminal-secondary-hero-image terminal-headline-featured-font terminal-alignment-center %s bookmark" title="%s" data-terminal-post-id="%s" data-terminal-view="hero image" data-terminal-title="%s" style="background: linear-gradient(
					rgba(0, 0, 0, 0.1),
					rgba(0, 0, 0, 0.1)
				), url(%s) center center / cover no-repeat;">',
				get_the_ID(),
				get_the_permalink(),
				! is_singular() ? 'terminal-tracking' : '',
				the_title_attribute( array( 'echo' => false ) ),
				get_the_ID(),
				the_title_attribute( array( 'echo' => false ) ),
				esc_url( $thumb['0'] )
			);
			the_title(); ?>
		</a>
	<?php
	endif;
	?>
</div>
