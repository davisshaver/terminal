<?php
/**
 * Memberpress integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Memberpress integration.
 */
class Memberpress {

	use Singleton;

	public function setup() {
		add_action( 'mepr_account_nav', [ $this, 'print_tab' ] );
	}

	public function print_tab() {
		$membership_page = $data->get_membership_page();
		if ( empty( $membership_page ) ) {
			return;
		}
		printf(
			'<span class="mepr-nav-item"><a href="%s" id="mepr-membership-options">%s</a></span>',
			esc_url( get_permalink( $membership_page ) ),
			__( 'Membership Options', 'terminal' )
		);
	}
	private function ends_with( $haystack, $needles ) {
		if ( ! is_array ( $needles ) && is_string( $needles ) ) {
			$needles = [ $needles ];
		}
		$ends_with = false;
		foreach( $needles as $needle ) {
			$needle_match = (
				( $temp = strlen( $haystack ) - strlen( $needle ) ) >= 0 &&
				strpos( $haystack, $needle, $temp ) !== FALSE
			);
			if ( $needle_match ) {
				$ends_with = true;
			}
		}
		return $ends_with;
	}

	public function check_email_domain( $errors ) {
		$data = Data::instance();
		$membership = $data->get_restricted_domains_by_membership();
		if (
			! empty( $membership ) &&
			! empty( $_POST['mepr_product_id'] ) &&
			! empty( $_POST['user_email'] ) &&
			! empty( $membership[ $_POST['mepr_product_id'] ] )
		) {
			$email = stripslashes( $_POST['user_email'] );
			if( ! $this->ends_with( $email, $membership[ $_POST['mepr_product_id'] ] ) ) {
				$errors[] = __( 'Sorry, your email domain is not eligible for this account type.', 'terminal' );
			}
		}
		return $errors;
	}
}

add_action( 'init', [ '\Terminal\Memberpress', 'instance' ] );
