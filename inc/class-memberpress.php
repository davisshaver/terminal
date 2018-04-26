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
	 * Setup actions
	 */
	public function setup() {
		add_filter( 'mepr-validate-signup', [ $this, 'check_email_domain' ] );
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
		$data = Terminal\Data::instance();
		$membership = $data->get_prepared_membership_data( array() );
		if (
			! empty( $membership ) &&
			! empty( $_POST['mepr_product_id'] ) &&
			! empty( $_POST['user_email'] ) &&
			! empty( $membership[ $_POST['mepr_product_id'] ] ) &&
			! empty($membership[ $_POST['mepr_product_id'] ]['domains'] )
		) {
			$email = stripslashes( $_POST['user_email'] );
			if( ! $this->ends_with( $email, $membership[ $_POST['mepr_product_id'] ]['domains'] ) ) {
				$errors[] = __( 'Sorry, only valid email addresses are allowed on this membership', 'terminal' );
			}
		}
		return $errors;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Memberpress', 'instance' ] );

