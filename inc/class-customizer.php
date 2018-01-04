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
		if ( is_customize_preview() ) {
			$this->enqueue_customize_scripts();
		}
	}

	/**
	 * Enqueue customize scripts.
	 */
	public function enqueue_customize_scripts() {
		wp_enqueue_script( 'terminal-customize-preview', get_template_directory_uri() . '/build/customizePreview.js', $deps, TERMINAL_VERSION, true );
	}

	/**
	 * Register customizer settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customize manager.
	 */
	public function customize_register( \WP_Customize_Manager $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Customizer', 'instance' ] );
