<?php
/**
 * Main content template.
 *
 * @package Terminal
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="image">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'terminal-primary-thumbnail', array( 'title' => get_the_title() ) ); ?>
			</a>
		</div>
	<?php endif; ?>
	<div class="post-row">
		<h3 class="headline terminal-headline-font">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h3>
		<div class="story-text terminal-body-font">
			<?php
				the_excerpt();
			?>
		</div>
	</div>
</div>
