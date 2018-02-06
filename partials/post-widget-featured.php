<?php
/**
 * Post widget template.
 *
 * @package terminal
 */

?>

<div class="post-widget post-widget-featured">
	<?php
	if ( has_post_thumbnail() ) :
	?>
		<div class="image">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'terminal-primary-thumbnail', array( 'title' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php
	endif;
	?>
	<div class="post-row">
		<h4 class="terminal-headline-font">
			<a href="<?php the_permalink(); ?>" class="link-gray">
				<?php the_title(); ?>
			</a>
		</h4>
		<div class="terminal-sidebar-body-font story-text text-gray-lighter">
			<?php
				the_excerpt();
			?>
		</div>
	</div>
</div>
