<?php
/**
 * Nav partial.
 *
 * @package Terminal
 */

if ( ! has_nav_menu( 'header' ) ) {
	return;
}

ob_start();
get_template_part( 'partials/svg/down.svg' );
$down = ob_get_contents();
ob_end_clean();
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
				'<li id="nav-bar-inside-more-link-container" class="hidden"><a id="nav-bar-inside-more-link" href="#">%s %s</a></li></ul>',
				esc_html( 'More', 'terminal' ),
				$down
			);
			$nav_menu = str_replace( '</ul>', $more, $nav_menu );
		}
		echo $nav_menu;
		if ( has_nav_menu( 'header-more' ) || has_nav_menu( 'header-more-meta' ) ) {
			echo '<div id="nav-bar-inside-more" class="hidden">';
			if ( has_nav_menu( 'header-more' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'header-more',
					'depth'          => 1,
					'menu_id'        => 'menu-header-inside',
				) );
			}
			if ( has_nav_menu( 'header-more-meta' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'header-more-meta',
					'depth'          => 1,
					'menu_id'        => 'menu-header-inside-meta',
				) );
			}
			echo '</div>';
		}
		?>
	</div>
</div>
