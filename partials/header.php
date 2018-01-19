<?php
/**
 * Header partial.
 *
 * @package Terminal
 */

?>
<div id="header">
	<div id="header-inside">
		<div id="header-leaderboard">
		<?php var_dump( get_option( 'terminal_header_options', 'test' ) ); ?>

			<?php
			if ( is_active_sidebar( 'header' ) ) {
				dynamic_sidebar( 'header' );
			}
			?>
		</div>
		<div id="logo_bar">
			<div id="logo">
				<a title="<?php esc_attr_e( 'Home', 'terminal' ); ?>" href="<?php echo esc_url( home_url() ); ?>">
					<img id="logo-image" src="<?php header_image(); ?>" draggable="false" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>" />
				</a>
			</div>
		</div>
	</div>
	<div id="nav-bar" class="terminal-utility-font">
		<div id="nav-bar-inside">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'header',
					'depth'          => 2,
				) );
			?>
		</div>
	</div>
</div>
