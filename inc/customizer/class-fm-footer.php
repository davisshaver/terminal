<?php
/**
 * FM Customizer/footer integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for footer.
 */
class FM_Footer {

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
		$wp_customize->selective_refresh->add_partial( 'footer_partial', array(
			'selector'        => '#footer',
			'settings'        => array( 'terminal_footer_options' ),
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
		get_template_part( 'partials/footer' );
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
	 * Add a customizer GROUP in a single SECTION for footer.
	 */
	public function customizer_init() {
		$fm = new \Fieldmanager_Group(
			'Footer Options',
			array(
				'name'     => 'terminal_footer_options',
				'children' => array(
					'copyright_from_year' => new \Fieldmanager_Textfield( 'Optional copyright start year' ),
				),
			)
		);
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority' => 52,
				'title'    => __( 'Footer Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'postMessage' ),
		), $fm );
	}
}

FM_Footer::instance();

