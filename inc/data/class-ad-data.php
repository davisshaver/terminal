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
	 * Get AMP exta ads.
	 *
	 * @return string extra ad
	 */
	public function get_extra_ads() {
		return apply_filters( 'the_content', $this->get_ad_data( 'extra_ads' ) );
	}

	/**
	 * Get AMP sticky ad list.
	 *
	 * @return string sticky ad
	 */
	public function get_amp_sticky_ad() {
		return $this->get_ad_data( 'amp_sticky_ad' );
	}

	/**
	 * Get mailchimp list.
	 *
	 * @return string mailchimp list
	 */
	public function get_mailchimp_list() {
		return $this->get_ad_data( 'mailchimp_list' );
	}

	/**
	 * Get mailchimp api key.
	 *
	 * @return string mailchimp api key
	 */
	public function get_mailchimp_api_key() {
		return $this->get_ad_data( 'mailchimp_api_key' );
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
	 * Get email signup text.
	 *
	 * @return string email signup text
	 */
	public function get_email_signup_text() {
		$alert = $this->get_ad_data( 'email_signup_text' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'You seem like a real fan. Stay in the loop.', 'terminal' );
	}

	/**
	 * Get email signup header.
	 *
	 * @return string email signup header
	 */
	public function get_email_signup_header() {
		$alert = $this->get_ad_data( 'email_signup_header' );
		if ( ! empty( $alert ) ) {
			return $alert;
		}
		return __( 'Sign up for our newsletter', 'terminal' );
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
