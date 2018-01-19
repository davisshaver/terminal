<?php
/**
 * FM Customizer/header integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for header.
 */
class FM_Header {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'customize_register', array( $this, 'add_partial' ) );
		add_action( 'fm_beta_customize', array( $this, 'customizer_init' ), 1000 );
	}

	/**
	 * Register header partial.
	 *
	 * @param object $wp_customize Customize object.
	 */
	public function add_partial( $wp_customize ) {
		if ( empty( $wp_customize->selective_refresh ) ) {
			return;
		}
		$wp_customize->selective_refresh->add_partial( 'header_partial', array(
			'selector'        => '#header',
			'settings'        => array( 'terminal_header_options' ),
			'render_callback' => array( $this, 'get_basic_partial' ),
		) );
	}

	/**
	 * Buffer template.
	 *
	 * @return string Buffered markup.
	 */
	public function buffer_template() {
		ob_start();
		get_template_part( 'partials/header' );
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
	}

	/**
	 * Render callback for basic_partial.
	 */
	public function get_basic_partial() {
		return $this->buffer_template();
	}

	/**
	 * Add a customizer GROUP in a single SECTION for header.
	 */
	public function customizer_init() {
		$fm = new \Fieldmanager_TextField(
			'Text Field with Selective Refresh',
			array( 'name' => 'terminal_header_options' )
		);
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority' => 51,
				'title'    => __( 'Header Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'postMessage' ),
		), $fm );
	}
}

FM_Header::instance();

