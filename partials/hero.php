<?php
/**
 * Hero template.
 *
 * @package terminal
 */
if ( empty( $size ) ) {
	$size = 'double';
}
?>

<div 
	id="terminal-hero-post-<?php the_ID(); ?>"
	<?php
	printf(
		'class="%s terminal-hero-widget terminal-card terminal-card-featured terminal-card-%s terminal-alignment-center"',
		! is_singular() ? 'terminal-post-tracking' : '',
		esc_attr( $size )
	);
	?>
	data-terminal-post-id="<?php the_ID(); ?>"
	data-terminal-has-image="<?php echo has_post_thumbnail(); ?>"
	data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
	data-terminal-title="<?php the_title_attribute(); ?>"
	data-terminal-view="hero"
>
	<h1 class="terminal-headline-featured-font terminal-limit-max-content-width">
		<?php
			printf(
				'<a id="hero-title-%s" href="%s" class="terminal-link-gray %s" title="%s" data-terminal-post-id="%s" data-terminal-view="hero title" data-terminal-title="%s">',
				get_the_ID(),
				get_the_permalink(),
				! is_singular() ? 'terminal-tracking' : '',
				the_title_attribute( array( 'echo' => false ) ),
				get_the_ID(),
				the_title_attribute( array( 'echo' => false ) )
			);
		?>
			<?php the_title(); ?>
		</a>
	</h1>
	<?php
	if ( has_post_thumbnail() ) :
	?>
		<div class="terminal-card-image">
		<?php
			printf(
				'<a id="terminal-hero-image-%s" href="%s" class="terminal-limit-max-content-width %s bookmark" title="%s" data-terminal-post-id="%s" data-terminal-view="hero image" data-terminal-title="%s">',
				get_the_ID(),
				get_the_permalink(),
				! is_singular() ? 'terminal-tracking' : '',
				the_title_attribute( array( 'echo' => false ) ),
				get_the_ID(),
				the_title_attribute( array( 'echo' => false ) )
			);
		?>
				<?php the_post_thumbnail( 'terminal-uncut-thumbnail-large', array( 'title' => get_the_title() ) ); ?>
			</a>
		</div>
		<div class="terminal-limit-max-content-width terminal-card-text terminal-sidebar-body-font">
			<?php
				the_excerpt();
			?>
		</div>
	<?php
	endif;
	?>
</div>
