<?php
/**
 * Post widget template.
 *
 * @package terminal
 */

?>

<div class="terminal-post-widget terminal-card-text">
	<h4 class="terminal-headline-font">
		<a href="<?php the_permalink(); ?>" class="terminal-link-gray">
			<?php the_title(); ?>
		</a>
	</h4>
</div>
