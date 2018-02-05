<?php
/**
 * Hero template.
 *
 * @package terminal
 */

?>

<div class="hero-secondary-widget">
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
	<h2 class="terminal-headline-font">
		<a href="<?php the_permalink(); ?>" class="link-gray">
			<?php the_title(); ?>
		</a>
	</h2>
	<div class="story-text terminal-sidebar-body-font mobile-hide-1">
		<?php
			the_excerpt();
		?>
	</div>
</div>
