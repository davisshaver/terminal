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

<div class="terminal-utility-font terminal-footer">
	<div class="terminal-footer-inside">
		<div class="terminal-footer-leaderboard">
			<?php
			if ( is_active_sidebar( 'footer' ) ) {
				dynamic_sidebar( 'footer' );
			}
			?>
		</div>
		<div class="terminal-footer-spread">
			<div class="terminal-footer-icons">
				<a class="terminal-small-logo" href="<?php echo esc_url( bloginfo( 'rss2_url' ) ); ?>">
					<img class="lazyload lazypreload" height="18" width="18" data-src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/rss.png" alt="<?php esc_attr_e( 'RSS logo', 'terminal' ); ?>" />
				</a>
			</div>
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
			<div class="terminal-small-logo terminal-ppc-logo">
				<a href="https://phillypublishing.com" title="<?php esc_attr_e( 'Powered by Philadelphia Publishing Company' ); ?>">
					<img width="25px" height="25px" class="lazyload lazypreload" draggable="false" data-src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/ppc.png" alt="<?php esc_attr_e( 'Powered by Philadelphia Publishing Company logo' ); ?>" />
				</a>
			</div>
		</div>
	</div>
</div>
