<?php
/**
 * Nav partial.
 *
 * @package Terminal
 */

if ( ! has_nav_menu( 'terminal-header' ) ) {
	return;
}

ob_start();
get_template_part( 'partials/svg/down.svg' );
$down = ob_get_contents();
ob_end_clean();
?>

<div class="terminal-nav-bar terminal-utility-font">
	<?php
	$nav_menu = wp_nav_menu( array(
		'theme_location' => 'terminal-header',
		'depth'          => 1,
		'echo'           => false,
		'menu_id'        => 'terminal-nav-bar-header',
	) );
	if ( has_nav_menu( 'terminal-header-more' ) || has_nav_menu( 'terminal-header-more-meta' ) ) {
		$more = sprintf(
			'<li class="terminal-nav-bar-inside-more-link terminal-hidden-no-js"><a href="#">%s %s</a></li></ul>',
			esc_html( 'More', 'terminal' ),
			$down
		);
		$nav_menu = str_replace( '</ul>', $more, $nav_menu );
	}
	echo $nav_menu;
	if ( has_nav_menu( 'terminal-header-more' ) || has_nav_menu( 'terminal-header-more-meta' ) ) {
		echo '<div class="terminal-nav-bar-inside-more terminal-hidden">';
		if ( has_nav_menu( 'terminal-header-more' ) ) {
			printf(
				'<h2>%s</h2>',
				terminal_get_nav_menu_title( 'terminal-header-more' )
			);
			wp_nav_menu( array(
				'theme_location' => 'terminal-header-more',
				'depth'          => 1,
				'menu_id'        => 'terminal-nav-bar-header-more',
				) );
		}
		if ( has_nav_menu( 'terminal-header-more-meta' ) ) {
			printf(
				'<h2>%s</h2>',
				terminal_get_nav_menu_title( 'terminal-header-more-meta' )
			);
			wp_nav_menu( array(
				'theme_location' => 'terminal-header-more-meta',
				'depth'          => 1,
				'menu_id'        => 'terminal-nav-bar-header-more-meta',
				) );
		}
		echo '</div>';
	}
	?>
</div>
