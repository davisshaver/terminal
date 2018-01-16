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
		<?php terminal_print_data_layer(); ?>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-PPN9ZXB');</script>
	</head>
	<body <?php body_class(); ?>>
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PPN9ZXB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<div id="header">
			<div id="header-inside">
				<div id="header-leaderboard">
					<broadstreet-zone zone-id="64586"></broadstreet-zone>
				</div>
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
