<?php
/**
 * Nav partial.
 *
 * @package Terminal
 */

if ( ! has_nav_menu( 'terminal-header' ) ) {
	return;
}

$header_data = terminal_get_header_data( array(
	'mobile_header_image_override' => null,
	'example_searches' => '',
) );

ob_start();
get_template_part( 'partials/svg/hamburger.svg' );
$hamburger = ob_get_contents();
ob_end_clean();

ob_start();
get_template_part( 'partials/svg/search.svg' );
$search_icon = ob_get_contents();
ob_end_clean();

ob_start();
get_template_part( 'partials/svg/home.svg' );
$home = ob_get_contents();
ob_end_clean();

ob_start();
get_template_part( 'partials/svg/down.svg' );
$down = ob_get_contents();
ob_end_clean();
if ( $header_data['mobile_header_image_override'] ) {
	$logo = wp_get_attachment_image(
		$header_data['mobile_header_image_override'],
		'terminal-uncut-thumbnail-logo',
		false
	);
} else {
	$logo = sprintf(
		'<img class="terminal-image" src="%s" draggable="false" alt="%s" />',
		get_header_image(),
		esc_attr( get_bloginfo( 'title' ) )
	);
}
?>

<div class="terminal-nav-bar">
	<div class="terminal-nav-bar-header-container terminal-nav-font">
		<?php
		$nav_menu = wp_nav_menu( array(
			'theme_location' => 'terminal-header',
			'depth'          => 1,
			'echo'           => false,
			'menu_id'        => 'terminal-nav-bar-header',
			'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>'
		) );
		if ( has_nav_menu( 'terminal-header-more' ) || has_nav_menu( 'terminal-header-more-meta' ) ) {
			$rotation = is_search() ? 'terminal-flipped' : '';
			$search_icon = str_replace( 'terminal-svg', sprintf( 'terminal-svg %s', esc_attr( $rotation ) ), $search_icon );
			$search = sprintf(
				'<li class="terminal-nav-bar-logo terminal-desktop-hide terminal-scroll-show"><a href="%s">%s</a></li><li class="terminal-nav-bar-inside-search-link terminal-hidden-no-js"><a href="#">%s</a></li></ul>',
				home_url(),
				$logo,
				$search_icon
			);
			if ( is_home() ) {
				$more = sprintf(
					'<ul id="terminal-nav-bar-header" class="menu"><li class="terminal-nav-bar-inside-more-link terminal-hidden-no-js"><a href="#">%s %s</a></li>',
					esc_html( '', 'terminal' ),
					$hamburger
				);
			} else {
				$more = sprintf(
					'<ul id="terminal-nav-bar-header" class="menu"><li class="terminal-nav-bar-inside-more-link terminal-hidden-no-js"><a href="#">%s %s</a></li><li class="terminal-nav-bar-home"><a href="%s">%s</a></li>',
					esc_html( '', 'terminal' ),
					$hamburger,
					esc_url( home_url() ),
					$home
				);
			}
			$nav_menu = str_replace( '<ul id="terminal-nav-bar-header" class="menu">', $more, $nav_menu );
			$nav_menu = str_replace( '</ul>', $search, $nav_menu );
		}
		echo $nav_menu;
	?>
	</div>
	<?php
		printf(
			'<div class="terminal-nav-bar-inside-search terminal-nav-font terminal-limit-max-content-width %s">',
			! is_search() ? esc_attr( 'terminal-hidden' ) : null
		);
		get_search_form( true );
		if ( ! empty( $header_data['example_searches'] ) ) {
			echo '<div class="terminal-example-searches terminal-mobile-hide terminal-alignment-center">';
			printf(
				'<h4>%s</h4>',
				esc_html( __( 'Example Searches', 'terminal' ) )
			);
			echo wp_kses_post( wpautop( $header_data['example_searches'] ) );
			echo '</div>';
		}
		?>
	</div>
	<?php
	if ( has_nav_menu( 'terminal-header-more' ) || has_nav_menu( 'terminal-header-more-meta' ) ) {
		echo '<div class="terminal-nav-bar-inside-more terminal-nav-font terminal-hidden">';
		wp_nav_menu( array(
			'theme_location' => 'terminal-header',
			'depth'          => 1,
			'menu_id'        => 'terminal-nav-bar-header-inside',
			'items_wrap'     => '<ul id="%1$s" class="%2$s terminal-scroll-show">%3$s</ul>'
		) );
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
		} ?>
<?php
		if ( is_home() || is_archive() ) {
			?>
				<div class="terminal-footer-leaderboard">
					<?php
					if ( is_active_sidebar( 'terminal-footer' ) ) {
						dynamic_sidebar( 'terminal-footer' );
					}
					?>
				</div>
				<div class="terminal-footer-spread">
					<div class="terminal-footer-icons">
						<a class="terminal-small-logo" href="<?php echo esc_url( bloginfo( 'rss2_url' ) ); ?>">
							<img class="lazyload lazypreload" height="18" width="18" data-src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/rss.png" alt="<?php esc_attr_e( 'RSS logo', 'terminal' ); ?>" />
						</a>
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
			<?php
		}
		echo '</div>';
	}
	?>
</div>
