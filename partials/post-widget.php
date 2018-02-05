<?php
/**
 * Post widget template.
 *
 * @package terminal
 */

?>

<div class="post-widget">
	<?php
	if ( has_post_thumbnail() ) :
	?>
		<div class="image">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'terminal-widget-thumbnail', array( 'title' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php
	endif;
	?>
	<h4 class="terminal-headline-font story-text">
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h4>
</div>
