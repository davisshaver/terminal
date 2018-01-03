<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
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
							<img height="54" width="455" src="<?php bloginfo( 'template_directory' ); ?>/client/static/images/banner.png" alt="Onward State" /></a>
					</div>
					<div id="search"></div>
				</div>
			</div>
			<div id="nav-bar">
				<div id="nav-bar-inside">
					<?php wp_nav_menu( array( 'theme_location' => 'primary-nav' ) ); ?>
					<div style="clear: both;"></div>
				</div>
			</div>
		</div>
