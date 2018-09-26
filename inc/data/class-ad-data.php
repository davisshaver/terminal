<?php
/**
 * Ad data helper.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for data loading.
 */
class Ad_Data {

	use Singleton;

	/**
	 * Ad data.
	 *
	 * @var array $ad_data Ad data.
	 */
	private $ad_data = array();


	/**
	 * Get mailchimp list.
	 *
	 * @return string mailchimp list
	 */
	public function get_mailchimp_list() {
		return $this->get_ad_data( 'mailchimp_list' );
	}

	/**
	 * Get mailchimp user.
	 *
	 * @return string mailchimp user
	 */
	public function get_mailchimp_user() {
		return $this->get_ad_data( 'mailchimp_user' );
	}

	/**
	 * Are inline stream ads enabled?
	 *
	 * @return boolean Has inline ads
	 */
	public function has_inline_ads() {
		return (bool) $this->get_ad_data( 'inline_ads' );
	}

	/**
	 * What unit should we use?
	 *
	 * @return string Ad unit
	 */
	public function is_blocker_disabled() {
		$value = $this->get_ad_data( 'disable_blocker' );
		if ( empty( $value ) ) {
			return false;
		}
		return true;
	}

	/**
	 * What unit should we use?
	 *
	 * @return string Ad unit
	 */
	public function get_inline_ads_tag() {
		return $this->get_ad_data( 'inline_unit' );
	}

	/**
	 * What AMP header unit should we use?
	 *
	 * @return string Ad tag
	 */
	public function get_amp_header_tag() {
		return $this->get_ad_data( 'amp_header' );
	}

	/**
	 * What AMP post unit should we use?
	 *
	 * @return string Ad tag
	 */
	public function get_amp_post_tag() {
		return $this->get_ad_data( 'amp_post' );
	}

	/**
	 * What AMP footer unit should we use?
	 *
	 * @return string Ad tag
	 */
	public function get_amp_footer_tag() {
		return $this->get_ad_data( 'amp_footer' );
	}

	/**
	 * Get mailchimp URL.
	 *
	 * @return string mailchimp URL
	 */
	public function get_mailchimp_url() {
		return $this->get_ad_data( 'mailchimp_url' );
	}

	/**
	 * Get Ad block link.
	 *
	 * @return string Ad block link
	 */
	public function get_ad_block_link() {
		return $this->get_ad_data( 'adblock_link' );
	}

	/**
	 * Get membership signup text.
	 *
	 * @return string membership signup text
	 */
	public function get_bypass_text() {
		$alert = $this->get_ad_data( 'bypass_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Already recieving enough emails?', 'terminal' );
	}

	/**
	 * Get membership signup text.
	 *
	 * @return string membership signup text
	 */
	public function get_membership_signup_button_text() {
		$alert = $this->get_ad_data( 'membership_signup_button_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Purchase a Subscription!', 'terminal' );
	}

	/**
	 * Get membership signup text.
	 *
	 * @return string membership signup text
	 */
	public function get_membership_signup_text() {
		$alert = $this->get_ad_data( 'membership_signup_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Support quality journalism:', 'terminal' );
	}

	/**
	 * Get email signup text.
	 *
	 * @return string email signup text
	 */
	public function get_email_signup_text() {
		$alert = $this->get_ad_data( 'email_signup_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Sign up for our e-mail newsletter:', 'terminal' );
	}

	/**
	 * Get Ad block alert.
	 *
	 * @return string Ad block alert
	 */
	public function get_ad_block_header() {
		$alert = $this->get_ad_data( 'adblock_header' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Your ad blocker is on.', 'terminal' );
	}

	/**
	 * Get Ad block alert.
	 *
	 * @return string Ad block alert
	 */
	public function get_ad_block_text() {
		$alert = $this->get_ad_data( 'adblock_alert' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Please choose an option.', 'terminal' );
	}


	/**
	 * Get prepared data.
	 *
	 * @param string $key Optional key.
	 * @return array Prepared data.
	 */
	public function get_ad_data( $key ) {
		if ( empty( $this->ad_data ) ) {
			$this->ad_data = get_option( 'terminal_ad_options' );
		}
		if ( ! empty( $this->ad_data[ $key ] ) ) {
			return $this->ad_data[ $key ];
		}
		return null;
	}

}

add_action( 'after_setup_theme', [ '\Terminal\Ad_Data', 'instance' ] );
