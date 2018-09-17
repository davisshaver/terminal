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
					'terminal_ad_option_disable_blocker'  => new \Fieldmanager_Checkbox( 'Disable ad blocker protection' ),
					'terminal_ad_option_inline_ads'  => new \Fieldmanager_Checkbox( 'Enable inline ads' ),
					'terminal_ad_option_inline_unit' => new \Fieldmanager_Textfield( 'Ad Layers unit to use' ),
					'terminal_ad_option_amp_header' => new \Fieldmanager_Textfield( array(
						'label' => 'AMP code for header ad',
						'sanitize' => 'wp_kses_post',
					) ),
					'terminal_ad_option_amp_post' => new \Fieldmanager_Textfield( array(
						'label' => 'AMP code for in-post ad',
						'sanitize' => 'wp_kses_post',
					) ),
					'terminal_ad_option_amp_footer' => new \Fieldmanager_Textfield( array(
						'label' => 'AMP code for footer ad',
						'sanitize' => 'wp_kses_post',
					) ),
					'terminal_ad_option_adblock_header' => new \Fieldmanager_Textfield( 'Adblock Header' ),
					'terminal_ad_option_adblock_alert' => new \Fieldmanager_Textfield( 'Adblock Alert' ),
					'terminal_ad_option_email_signup_text' => new \Fieldmanager_Textfield( 'Email Signup Text' ),
					'terminal_ad_option_membership_signup_button_text' => new \Fieldmanager_Textfield( 'Membership Signup Text' ),
					'terminal_ad_option_membership_signup_text' => new \Fieldmanager_Textfield( 'Membership Signup Text' ),
					'terminal_ad_option_bypass_text' => new \Fieldmanager_Textfield( 'Bypass Text' ),
					'terminal_ad_option_adblock_nag' => new \Fieldmanager_Media( 'Adblock Nag' ),
					'terminal_ad_option_adblock_link' => new \Fieldmanager_Link( 'Adblock Link' ),
					'terminal_ad_option_mailchimp_url' => new \Fieldmanager_Link( 'Mailchimp URL' ),
					'terminal_ad_option_mailchimp_user' => new \Fieldmanager_Textfield( 'Mailchimp User' ),
					'terminal_ad_option_mailchimp_list' => new \Fieldmanager_Textfield( 'Mailchimp List' ),
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
