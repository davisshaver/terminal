<?php
/**
 * Nav partial.
 *
 * @package Terminal
 */

if ( ! has_nav_menu( 'header' ) ) {
	return;
}
?>

<div id="nav-bar" class="terminal-utility-font">
	<div id="nav-bar-inside">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'header',
				'depth'          => 1,
			) );
			if ( has_nav_menu( 'header-more' ) ) {
				echo '<div id="nav-bar-inside-more">';
				wp_nav_menu( array(
					'theme_location' => 'header-more',
					'depth'          => 1,
				) );
				if ( has_nav_menu( 'footer-more' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer-more',
						'depth'          => 1,
					) );
				}
				echo '</div>';
			}
		?>
	</div>
</div>
