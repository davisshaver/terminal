<?php
/**
 * Hero template.
 *
 * @package terminal
 */

?>

<div class="hero-widget">
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
				<?php the_post_thumbnail( 'terminal-widget-featured', array( 'title' => get_the_title() ) ); ?>
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
