<?php
/**
 * Customizer.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Customizer.
 */
class Customizer {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_preview_init', [ $this, 'enqueue_customize_scripts' ] );
		add_filter( 'site_icon_meta_tags', [ $this, 'site_icon_meta_tags' ] );
	}

	/**
	 * Don't print site icon meta tags in the admin.
	 *
	 * @param array $tags Tags.
	 *
	 * @return array Tags
	 */
	public function site_icon_meta_tags( $tags ) {
		if ( is_admin() ) {
			return array();
		}
		return $tags;
	}

	/**
	 * Enqueue customize scripts.
	 */
	public function enqueue_customize_scripts() {
		wp_enqueue_script( 'terminal-customize-preview', get_template_directory_uri() . '/client/build/customizerPreview.bundle.js', array(), TERMINAL_VERSION, true );
	}

	/**
	 * Register customizer settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customize manager.
	 */
	public function customize_register( \WP_Customize_Manager $wp_customize ) {
		/**
		 * Opt some core fields into immediate update.
		 */
		$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
		$wp_customize->get_setting( 'header_image' )->transport      = 'postMessage';
		$wp_customize->get_setting( 'header_image_data' )->transport = 'postMessage';
		$wp_customize->get_setting( 'header_image_data' )->transport = 'postMessage';

		/**
		 * Remove some core fields we aren't using.
		 */
		$wp_customize->remove_control( 'header_textcolor' );
		$wp_customize->remove_control( 'display_header_text' );
		$wp_customize->remove_control( 'blogdescription' );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Customizer', 'instance' ] );
