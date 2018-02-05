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
					'inline_unit' => new \Fieldmanager_Textfield( 'Ad Layers unit to use' ),
					'ios_install' => new \Fieldmanager_Textfield( 'iOS app ID for install banner' ),
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
