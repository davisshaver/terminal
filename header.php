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
						<a href="<?php echo esc_url( site_url() ); ?>">
							<img id="logo-image" src="<?php header_image(); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>"  alt="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>" />
					</div>
					<div id="search"></div>
				</div>
			</div>
			<div id="nav-bar">
				<div id="nav-bar-inside">
					<?php wp_nav_menu( array( 'theme_location' => 'primary-nav' ) ); ?>
				</div>
			</div>
		</div>
