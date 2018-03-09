<?php
/**
 * Author bio box
 *
 * @package Terminal
 */

$byline_data = terminal_get_byline_options( array(
	'default_gravatar' => null,
) );

$default_gravatar = ! empty( $byline_data['default_gravatar'] ) ?
	intval( $byline_data['default_gravatar'] ) :
	false;
?>
<div class="terminal-author">
	<div class="terminal-image">
		<?php terminal_print_avatar( 150, $default_gravatar ); ?>
	</div>
	<div class="terminal-author-bio terminal-single-meta-font">
		<?php the_author_meta( 'description' ); ?>
	</div>
</div>
