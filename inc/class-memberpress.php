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

	/**
	 * Setup Memberpress integration.
	 */
	public function setup() {
		add_action( 'mepr_account_nav', [ $this, 'print_tab' ] );
	}

	/**
	 * Custom print tab action.
	 */
	public function print_tab() {
		$data            = Data::instance();
		$membership_page = $data->get_membership_page_always();
		if ( empty( $membership_page ) ) {
			return;
		}
		printf(
			'<span class="mepr-nav-item"><a href="%s" id="mepr-membership-options">%s</a></span>',
			esc_url( $membership_page ),
			esc_html( __( 'Membership Options', 'terminal' ) )
		);
	}

	/**
	 * Handler to check for matches.
	 *
	 * @param mixed $haystack Array or string.
	 * @param mixed $needles Array or string.
	 * @return boolean Whether there's a match.
	 */
	private function ends_with( $haystack, $needles ) {
		if ( ! is_array( $needles ) && is_string( $needles ) ) {
			$needles = [ $needles ];
		}
		$ends_with = false;
		foreach ( $needles as $needle ) {
			$needle_match = (
				(
					$temp = strlen( $haystack ) - strlen( $needle )
				) >= 0 &&
				strpos( $haystack, $needle, $temp ) !== false
			);
			if ( $needle_match ) {
				$ends_with = true;
			}
		}
		return $ends_with;
	}

	/**
	 * Handler to check email domain from $_POST data.
	 *
	 * @param array $errors Existing errors.
	 * @return array Filtered errors.
	 */
	public function check_email_domain( $errors ) {
		$data       = Data::instance();
		$membership = $data->get_restricted_domains_by_membership();
		// phpcs:disable
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
		// phpcs:enable
		return $errors;
	}
}

add_action( 'init', [ '\Terminal\Memberpress', 'instance' ] );
