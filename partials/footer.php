<?php
/**
 * Footer partial.
 *
 * @package Terminal
 */

$footer_data = terminal_get_footer_data( array(
	'copyright_from_year' => null,
) );

?>

<div class="terminal-footer terminal-footer-font">
	<div class="terminal-footer-inside">
		<div class="terminal-footer-leaderboard">
			<?php
			if ( is_active_sidebar( 'terminal-footer' ) ) {
				echo '<div class="terminal-limit-max-content-width-add-margin terminal-alignment-center">';
				dynamic_sidebar( 'terminal-footer' );
				echo '</div>';
			}
			?>
		</div>
		<div class="terminal-footer-spread">
			<div class="terminal-menu-footer">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'terminal-footer',
					'menu_id'        => 'terminal-menu-footer',
					'depth'          => 1,
				) );
				?>
			</div>
			<div class="terminal-copyright">
				<p>&copy;&nbsp;
				<?php
				if ( ! empty( $footer_data['copyright_from_year'] ) ) {
					echo esc_html( $footer_data['copyright_from_year'] . ' - ' );
				}
				echo esc_html( date( 'Y' ) );
				?>
				<span class="terminal-footer-title"><?php echo esc_html( get_bloginfo( 'title' ) ); ?></span></p>
			</div>
		</div>
	</div>
</div>
