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
				'serialize_data' => false,
				'children' => array(
					'disable_blocker'  => new \Fieldmanager_Checkbox( 'Disable ad blocker protection' ),
					'inline_ads'  => new \Fieldmanager_Checkbox( 'Enable inline ads' ),
					'inline_unit' => new \Fieldmanager_Textfield( 'Ad Layers unit to use' ),
					'amp_header' => new \Fieldmanager_Textfield( array(
						'label' => 'AMP code for header ad',
						'sanitize' => 'wp_kses_post',
					) ),
					'amp_post' => new \Fieldmanager_Textfield( array(
						'label' => 'AMP code for in-post ad',
						'sanitize' => 'wp_kses_post',
					) ),
					'amp_footer' => new \Fieldmanager_Textfield( array(
						'label' => 'AMP code for footer ad',
						'sanitize' => 'wp_kses_post',
					) ),
					'adblock_header' => new \Fieldmanager_Textfield( 'Adblock Header' ),
					'adblock_alert' => new \Fieldmanager_Textfield( 'Adblock Alert' ),
					'email_signup_text' => new \Fieldmanager_Textfield( 'Email Signup Text' ),
					'membership_signup_button_text' => new \Fieldmanager_Textfield( 'Membership Signup Text' ),
					'membership_signup_text' => new \Fieldmanager_Textfield( 'Membership Signup Text' ),
					'bypass_text' => new \Fieldmanager_Textfield( 'Bypass Text' ),
					'adblock_nag' => new \Fieldmanager_Media( 'Adblock Nag' ),
					'adblock_link' => new \Fieldmanager_Link( 'Adblock Link' ),
					'mailchimp_url' => new \Fieldmanager_Link( 'Mailchimp URL' ),
					'mailchimp_user' => new \Fieldmanager_Textfield( 'Mailchimp User' ),
					'mailchimp_list' => new \Fieldmanager_Textfield( 'Mailchimp List' ),
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
