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
					'smart_banner_enable_ios' => new \Fieldmanager_Checkbox( 'Enable iOS smartbanner' ),
					'smart_banner_enable_google' => new \Fieldmanager_Checkbox( 'Enable Android smartbanner' ),
					'smart_banner_title' => new \Fieldmanager_Textfield( 'Smartbanner title' ),
					'smart_banner_author' => new \Fieldmanager_Textfield( 'Smartbanner author' ),
					'smart_banner_price' => new \Fieldmanager_Textfield( 'Smartbanner price' ),
					'smart_banner_suffix_apple' => new \Fieldmanager_Textfield( 'Suffix (Apple)' ),
					'smart_banner_suffix_google' => new \Fieldmanager_Textfield( 'Suffix (Google)' ),
					'smart_banner_icon_apple' => new \Fieldmanager_Media( 'Icon (Apple)' ),
					'smart_banner_icon_google' => new \Fieldmanager_Media( 'Icon (Google)' ),
					'smart_banner_button_text' => new \Fieldmanager_Textfield( 'Button Text' ),
					'smart_banner_button_link_apple' => new \Fieldmanager_Link( 'iOS Link' ),
					'smart_banner_button_link_google' => new \Fieldmanager_Link( 'Android Link' ),
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
