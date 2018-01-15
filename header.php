<?php
/**
 * The header for our theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Terminal
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="header">
			<div id="header-inside">
				<div id="header-leaderboard"></div>
				<div id="logo_bar">
					<div id="logo">
						<a title="<?php esc_attr_e( 'Home', 'terminal' ); ?>" href="<?php echo esc_url( home_url() ); ?>">
							<img id="logo-image" src="<?php header_image(); ?>" draggable="false" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>" />
						</a>
					</div>
				</div>
			</div>
			<div id="nav-bar">
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
