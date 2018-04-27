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
					'disable_blocker'  => new \Fieldmanager_Checkbox( 'Disable ad blocker protection' ),
					'inline_ads'  => new \Fieldmanager_Checkbox( 'Enable inline ads' ),
					'inline_unit' => new \Fieldmanager_Textfield( 'Ad Layers unit to use' ),
					'ios_install' => new \Fieldmanager_Textfield( 'iOS app ID for install banner' ),
					'amp_header' => new \Fieldmanager_RichTextArea( array(
						'label' => 'AMP code for header ad',
						'editor_settings' => array(
							'wpautop' => false,
							'media_buttons' => false,
							'default_editor' => 'html',
						)
					) ),
					'amp_post' => new \Fieldmanager_RichTextArea( array(
						'label' => 'AMP code for in-post ad',
						'editor_settings' => array(
							'wpautop' => false,
							'media_buttons' => false,
							'default_editor' => 'html',
						)
					) ),
					'amp_footer' => new \Fieldmanager_RichTextArea( array(
						'label' => 'AMP code for footer ad',
						'editor_settings' => array(
							'wpautop' => false,
							'media_buttons' => false,
							'default_editor' => 'html',
						)
					) ),
					'adblock_alert' => new \Fieldmanager_Textfield( 'Adblock Alert' ),
					'adblock_nag' => new \Fieldmanager_Media( 'Adblock Nag' ),
					'adblock_link' => new \Fieldmanager_Link( 'Adblock Link' ),
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
