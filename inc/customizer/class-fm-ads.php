<?php
/**
 * FM Customizer/ads integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for ads.
 */
class FM_Ads {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'fm_beta_customize', array( $this, 'customizer_init' ), 1000 );
	}

	/**
	 * Add a customizer GROUP in a single SECTION for ads.
	 */
	public function customizer_init() {
		$fm = new \Fieldmanager_Group(
			'Ad Options',
			array(
				'name'     => 'terminal_ad_options',
				'children' => array(
					'inline_ads'  => new \Fieldmanager_Checkbox( 'Enable inline ads' ),
					'inline_rate' => new \Fieldmanager_Select( 'Insertion rate', array( 'options' => array( 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 ) ) ),
					'inline_unit' => new \Fieldmanager_Textfield( 'Ad Layers unit to use' ),
				),
			)
		);
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority'   => 53,
				'capability' => 'edit_posts',
				'title'      => __( 'Ad Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'refresh' ),
		), $fm );
	}
}

FM_Ads::instance();
