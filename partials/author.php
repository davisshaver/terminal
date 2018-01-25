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
<div id="author">
	<div class="image">
		<?php terminal_print_avatar( 100, $default_gravatar ); ?>
	</div>
	<div class="bio">
		<?php the_author_meta( 'description' ); ?>
	</div>
</div>
