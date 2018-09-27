<?php
/**
 * FM Customizer/apps integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for apps.
 */
class FM_Apps {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'fm_beta_customize', array( $this, 'customizer_init' ), 1000 );
	}

	/**
	 * Add a customizer GROUP in a single SECTION for apps.
	 */
	public function customizer_init() {
		$fm = new \Fieldmanager_Group(
			'App Options',
			array(
				'name'     => 'terminal_app_options',
				'children' => array(
					'enable_app_banner'  => new \Fieldmanager_Checkbox( 'Enable app banner' ),
					'apple_app_id' => new \Fieldmanager_Textfield( 'Apple App ID' ),
					'apple_app_argument' => new \Fieldmanager_Textfield( 'Apple App Argument' ),
					'android_app_url' => new \Fieldmanager_Textfield( 'Android App ID' ),
					'android_app_url' => new \Fieldmanager_Link( 'Android App URL' ),
				),
			)
		);
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority'   => 53,
				'capability' => 'edit_posts',
				'title'      => __( 'App Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'refresh' ),
		), $fm );
	}
}

FM_Apps::instance();
