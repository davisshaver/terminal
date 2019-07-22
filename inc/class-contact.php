<?php
/**
 * AMP Integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for AMP.
 */
class Contact {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_shortcode( 'terminal-contact', [ $this, 'terminal_contact' ] );

		add_action( 'wp_ajax_contact', [ $this, 'ajax_contact' ] );
		add_action( 'wp_ajax_nopriv_contact', [ $this, 'ajax_contact' ] );
	}

	/**
	 * Admin AJAX endpoint for sending an email.
	 */
	public function ajax_contact() {
		if ( ! isset( $_REQUEST['message'] ) ) {
			wp_send_json_error( __( 'Sorry, we couldn\'t complete your request. Try again later.', 'terminal' ) );
		}
		if ( ! check_ajax_referer( 'contact-nonce', 'security', false ) ) {
			wp_send_json_error( __( 'Invalid security token.', 'terminal' ) );
		}
		$message = wp_kses_post( wp_unslash( $_REQUEST['message'] ) );
		if ( isset( $_REQUEST['email'] ) ) {
			$email = sanitize_email( wp_unslash( $_REQUEST['email'] ) );
		} else {
			$email = __( 'Not provided', 'terminal' );
		}
		if ( isset( $_REQUEST['name'] ) ) {
			$name = wp_kses_post( wp_unslash( $_REQUEST['name'] ) );
		} else {
			$name = __( 'Not provided', 'terminal' );
		}
		wp_mail(
			get_option( 'admin_email' ),
			sprintf( 'Message from %s <%s>', esc_html( $name ), esc_html( $email ) ),
			$message
		);
		wp_send_json_success( __( 'Thanks for contacting us!', 'terminal' ) );
	}

	/**
	 * Print contact form.
	 */
	public function terminal_contact( $atts ) {
		return terminal_get_template_part( 'contact', $atts );
	}

}

add_action( 'after_setup_theme', [ '\Terminal\Contact', 'instance' ] );
