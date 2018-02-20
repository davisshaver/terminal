<?php
	$post_author      = $this->get( 'post_author' );
	$byline_data      = terminal_get_byline_options( array(
		'default_gravatar'     => null,
	) );
	$default_gravatar = ! empty( $byline_data['default_gravatar'] ) ?
		intval( $byline_data['default_gravatar'] ) :
		false;

?>
<?php if ( $post_author ) : ?>
	<div class="amp-wp-meta amp-wp-byline">
		<?php
		if ( function_exists( 'terminal_print_avatar_amp' ) ) :
			terminal_print_avatar_amp( $post_author->ID, $default_gravatar );
		endif;
		?>
		<span class="amp-wp-author author vcard"><?php echo esc_html( $post_author->display_name ); ?></span>
	</div>
<?php endif; ?>
