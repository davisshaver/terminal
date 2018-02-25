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
		'class="%s hero-secondary-widget"',
		! is_singular() ? 'terminal-post-tracking' : ''
	);
	?>
	data-terminal-post-id="<?php the_ID(); ?>"
	data-terminal-has-image="<?php echo has_post_thumbnail(); ?>"
	data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
	data-terminal-title="<?php the_title_attribute(); ?>"
	data-terminal-view="featured-hero-secondary"
>
	<?php
	if ( has_post_thumbnail() ) :
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'terminal-thumbnail' );
	?>
		<div class="image" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>')">
		<?php
			printf(
				'<a href="%s" class="%s bookmark" title="%s"></a>',
				get_the_permalink(),
				! is_singular() ? 'terminal-tracking' : '',
				the_title_attribute( array( 'echo' => false ) )
			);
		?>
		</div>
	<?php
	endif;
	?>
	<h2 class="terminal-headline-font">
	<?php
			printf(
				'<a href="%s" class="%s link-gray" title="%s">',
				get_the_permalink(),
				! is_singular() ? 'terminal-tracking' : '',
				the_title_attribute( array( 'echo' => false ) )
			);
		?>
			<?php the_title(); ?>
		</a>
	</h2>
	<div class="story-text terminal-sidebar-body-font mobile-hide-1">
		<?php
			the_excerpt();
		?>
	</div>
</div>
