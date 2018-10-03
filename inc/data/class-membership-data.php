<?php
/**
 * Apps data helper.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for data loading.
 */
class Membership_Data {

	use Singleton;

	/**
	 * Get membership data.
	 *
	 * @return array Data.
	 */
	public function get_restricted_domains_by_membership() {
		$restricted_membership = array();
		$membership_options    = get_option( 'terminal_membership_options', array() );
		if ( empty( $membership_options['restricted_memberships'] ) ) {
			return $restricted_membership;
		}
		foreach ( $membership_options['restricted_memberships'] as $membership ) {
			$restricted_membership[ $membership['membership_id'] ] = explode( ',', $membership['domains'] );
		}
		return $restricted_membership;
	}

	/**
	 * Get membership data.
	 *
	 * @return array Data.
	 */
	public function get_membership_page() {
		$membership_options = $this->get_prepared_membership_data();
		if ( empty( $membership_options['membership_page'] ) ) {
			return false;
		}
		return $membership_options['membership_page'];
	}

	/**
	 * Get membership data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_prepared_membership_data( $default = array() ) {
		$membership_options = get_option( 'terminal_membership_options', array() );
		if ( empty( $membership_options ) ) {
			return $default;
		}
		return array_merge( $default, $membership_options );
	}

}

add_action( 'after_setup_theme', [ '\Terminal\Membership_Data', 'instance' ] );
