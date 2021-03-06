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
		<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=contain">
		<?php
			if (is_home()) {
				echo '<meta http-equiv="refresh" content="600">';
			}
		?>
		<?php wp_head(); ?>
		<?php terminal_print_data_layer(); ?>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-PPN9ZXB');</script>
	</head>
	<body <?php body_class(); ?>>
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PPN9ZXB&noscript=true"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<?php
			get_template_part( 'partials/header' );
