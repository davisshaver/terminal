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
			$nav_menu = wp_nav_menu( array(
				'theme_location' => 'header',
				'depth'          => 1,
				'echo'           => false,
				'menu_id'        => 'menu-header-nav',
			) );
			if ( has_nav_menu( 'header-more' ) ) {
				$more = sprintf(
					'<li><a id="nav-bar-inside-more-link" href="#">%s</a></li></ul>',
					esc_html( '▼', 'terminal' )
				);
				$nav_menu = str_replace( '</ul>', $more, $nav_menu );
			}
			echo $nav_menu;
			if ( has_nav_menu( 'header-more' ) ) {
				echo '<div id="nav-bar-inside-more">';
				wp_nav_menu( array(
					'theme_location' => 'header-more',
					'depth'          => 1,
				) );
				echo '</div>';
			}
		?>
	</div>
</div>
