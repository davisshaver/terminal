<?php
/**
 * Hero template.
 *
 * @package terminal
 */

?>

<div 
	id="post-<?php the_ID(); ?>"
	class="terminal-post-tracking hero-widget"
	data-terminal-post-id="<?php the_ID(); ?>"
	data-terminal-has-image="<?php echo has_post_thumbnail(); ?>"
	data-terminal-author="<?php esc_attr( the_author_meta( 'user_nicename' ) ); ?>"
	data-terminal-title="<?php the_title_attribute(); ?>"
	data-terminal-view="featured-hero"
>
	<h2 class="terminal-headline-featured-font">
		<a href="<?php the_permalink(); ?>" class="link-gray">
			<?php the_title(); ?>
		</a>
	</h2>
	<?php
	if ( has_post_thumbnail() ) :
	?>
		<div class="image">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
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
