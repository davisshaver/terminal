<?php
/**
 * Post widget template.
 *
 * @package terminal
 */

?>

<div class="terminal-post-widget terminal-card-text terminal-post-widget-featured">
	<?php
	if ( has_post_thumbnail() ) :
	?>
		<div class="terminal-image">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'terminal-primary-thumbnail', array( 'title' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php
	endif;
	?>
	<h4 class="terminal-headline-font">
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h4>
	<div class="terminal-sidebar-body-font">
		<?php
			the_excerpt();
		?>
	</div>
</div>
