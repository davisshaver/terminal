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
terminal_print_author_bio_header();
?>
<div class="terminal-author-snippet terminal-limit-max-content-width">
	<?php terminal_print_avatar( 150, $default_gravatar ); ?>
	<div class="terminal-card-text terminal-single-meta-font">
		<h2>
			<a class="terminal-link-gray" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>"><?php the_author(); ?>
			</a>
		</h2>
		<?php echo wp_kses_post( wpautop( get_the_author_meta( 'description' ) ) ); ?>
	</div>
</div>
