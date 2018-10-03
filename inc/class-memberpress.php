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
	 * Setup class.
	 */
	public function setup() {
		add_action( 'mepr_account_nav', [ $this, 'print_tab' ] );
	}

	/**
	 * Print Memberpress tab.
	 */
	public function print_tab() {
		$data            = Data::instance();
		$membership_page = $data->get_membership_page();
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
	 * Check if string ends with another string.
	 *
	 * @param string $haystack Target string.
	 * @param mixed  $needles String or array to check.
	 * @return boolean Whether match is found.
	 */
	private function ends_with( $haystack, $needles ) {
		if ( ! is_array( $needles ) && is_string( $needles ) ) {
			$needles = [ $needles ];
		}
		$ends_with = false;
		foreach ( $needles as $needle ) {
			$needle_match = (
				(
					$temp = strlen( $haystack ) - strlen( $needle ) ) >= 0 &&
					false !== strpos( $haystack, $needle, $temp )
			);
			if ( $needle_match ) {
				$ends_with = true;
			}
		}
		return $ends_with;
	}

	/**
	 * Check user email for validity.
	 *
	 * @param array $errors Current errors.
	 * @return array Filtered errors
	 */
	public function check_email_domain( $errors ) {
		$data       = Data::instance();
		$membership = $data->get_restricted_domains_by_membership();
		if (
			! empty( $membership ) &&
			! empty( $_POST['mepr_product_id'] ) &&
			! empty( $_POST['user_email'] ) &&
			! empty( $membership[ $_POST['mepr_product_id'] ] )
		) {
			$email = sanitize_email( wp_unslash( $_POST['user_email'] ) );
			if ( ! $this->ends_with( $email, $membership[ sanitize_text_field( wp_unslash( $_POST['mepr_product_id'] ) ) ] ) ) {
				$errors[] = __( 'Sorry, your email domain is not eligible for this account type.', 'terminal' );
			}
		}
		return $errors;
	}
}

add_action( 'init', [ '\Terminal\Memberpress', 'instance' ] );
