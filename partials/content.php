<?php
/**
 * Main content template.
 *
 * @package Terminal
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="headline">
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</div>
	<div class="post-row">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( 'terminal-primary-thumbnail', array( 'title' => get_the_title() ) ); ?>
				</a>
			</div>
		<?php endif; ?>
		<div class="story-text">
			<?php
				the_excerpt();
			?>
		</div>
	</div>
</div>
