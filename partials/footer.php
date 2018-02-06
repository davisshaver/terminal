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

<div id="footer" class="terminal-utility-font">
	<div id="footer-inside">
		<div id="footer-leaderboard">
			<?php
			if ( is_active_sidebar( 'footer' ) ) {
				dynamic_sidebar( 'footer' );
			}
			?>
		</div>
		<div id="footer-spread">
			<div id="footer-icons">
				<a href="<?php echo esc_url( bloginfo( 'rss2_url' ) ); ?>">
					<img height="18" width="18" src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/rss.png" alt="<?php esc_attr_e( 'RSS logo', 'terminal' ); ?>" />
				</a>
			</div>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'menu_id'        => 'menu-footer',
				'menu_class'     => 'link-gray',
				'depth'          => 1,
			) );
			?>
			<div id="copyright">
				<p>&copy;&nbsp;
				<?php
				if ( ! empty( $footer_data['copyright_from_year'] ) ) {
					echo esc_html( $footer_data['copyright_from_year'] . ' - ' );
				}
				echo esc_html( date( 'Y' ) );
				?>
				<span id="footer-title"><?php echo esc_html( get_bloginfo( 'title' ) ); ?></span></p>
			</div>
			<div id="ppc-logo">
				<a href="https://phillypublishing.com" title="<?php esc_attr_e( 'Powered by Philadelphia Publishing Company' ); ?>">
					<img draggable="false" src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/ppc.png" alt="<?php esc_attr_e( 'Powered by Philadelphia Publishing Company logo' ); ?>" />
				</a>
			</div>
		</div>
	</div>
</div>
