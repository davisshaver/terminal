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
				<?php the_post_thumbnail( 'terminal-widget-featured', array( 'title' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php
	endif;
	?>
	<div class="post-row">
		<h4>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h4>
		<div>
			<?php
				the_excerpt();
			?>
		</div>
	</div>
</div>
