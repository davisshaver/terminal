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
			'selector'        => '.terminal-header',
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
		$fm = new \Fieldmanager_Group(
			'Header Layout Options',
			array(
				'name'     => 'terminal_header_options',
				'children' => array(
					'signup_show_on_desktop'          => new \Fieldmanager_Checkbox( 'Show Signup on Desktop' ),
					'signup_show_on_mobile'           => new \Fieldmanager_Checkbox( 'Show Signup on Mobile' ),
					'signup_button'                   => new \Fieldmanager_Textfield( 'Signup Button' ),
					'signup_icon'                     => new \Fieldmanager_Media( 'Signup Icon' ),
					'signup_link'                     => new \Fieldmanager_Link( 'Signup Link' ),
					'cta_show_on_desktop'          => new \Fieldmanager_Checkbox( 'Show CTA on Desktop' ),
					'cta_show_on_mobile'           => new \Fieldmanager_Checkbox( 'Show CTA on Mobile' ),
					'cta_tagline'                  => new \Fieldmanager_TextArea( 'CTA Tagline' ),
					'cta_button'                   => new \Fieldmanager_Textfield( 'CTA Button' ),
					'cta_icon'                     => new \Fieldmanager_Media( 'CTA Icon' ),
					'cta_link'                     => new \Fieldmanager_Link( 'CTA Link' ),
					'mobile_header_image_override' => new \Fieldmanager_Media( 'Mobile Header Image Override' ),
				),
			)
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

