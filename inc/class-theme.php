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

		add_theme_support( 'header-image' );

		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'terminal' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'gallery',
			'caption',
		) );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Theme', 'instance' ] );
