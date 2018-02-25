<?php
/**
 * Hero template.
 *
 * @package terminal
 */

?>

<div 
	id="hero-post-<?php the_ID(); ?>"
	<?php
	printf(
		'class="%s hero-widget"',
		! is_singular() ? 'terminal-post-tracking' : ''
	);
	?>
	data-terminal-post-id="<?php the_ID(); ?>"
	data-terminal-has-image="<?php echo has_post_thumbnail(); ?>"
	data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
	data-terminal-title="<?php the_title_attribute(); ?>"
	data-terminal-view="featured-hero"
>
	<h2 class="terminal-headline-featured-font">
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
	<?php
	if ( has_post_thumbnail() ) :
	?>
		<div class="image">
		<?php
			printf(
				'<a href="%s" class="%s bookmark" title="%s">',
				get_the_permalink(),
				! is_singular() ? 'terminal-tracking' : '',
				the_title_attribute( array( 'echo' => false ) )
			);
		?>
				<?php the_post_thumbnail( 'terminal-featured', array( 'title' => get_the_title() ) ); ?>
			</a>
		</div>
		<div class="story-text terminal-sidebar-body-font">
			<?php
				the_excerpt();
			?>
		</div>
	<?php
	endif;
	?>
</div>
