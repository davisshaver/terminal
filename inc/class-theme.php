<?php
/**
 * Theme customization.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Theme.
 */
class Theme {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		load_theme_textdomain( 'terminal', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'title-tag' );

		add_theme_support( 'post-thumbnails' );

		register_default_headers( array(
			'terminal' => array(
				'url'           => '%s/client/static/images/banner.png',
				'thumbnail_url' => '%s/client/static/images/banner-thumbnail.png',
			),
		) );
		$custom_header_args = array(
			'height'         => 54,
			'random-default' => true,
			'width'          => 455,
		);
		add_theme_support( 'custom-header', $custom_header_args );

		$custom_background_args = array(
			'default-color' => '#ffffff',
		);
		add_theme_support( 'custom-background', $custom_background_args );

		register_nav_menus( array(
			'header' => esc_html__( 'Header Menu', 'terminal' ),
			'footer' => esc_html__( 'Footer Menu', 'terminal' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'gallery',
			'caption',
		) );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Theme', 'instance' ] );
