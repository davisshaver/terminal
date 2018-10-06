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
class AMP {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_filter( 'filter_ampnews_amp_plugin_path', [ $this, 'terminal_filter_amp_plugin_path' ] );
		add_action( 'ampnews-after-body', [ $this, 'print_app_tag' ] );
		add_action( 'ampnews-before-excerpt', [ $this, 'print_reading_time' ] );
		add_action( 'ampnews-before-article', [ $this, 'print_reading_time' ] );
		add_action( 'ampnews-before-footer', [ $this, 'print_sponsors_module' ] );
		add_action( 'ampnews-before-entry-header', [ $this, 'print_featured_image_info' ] );
		add_action( 'wp_ajax_email_signup', [ $this, 'ajax_email_signup' ] );
		add_action( 'wp_ajax_nopriv_email_signup', [ $this, 'ajax_email_signup' ] );
		add_shortcode( 'terminal-mailchimp', [ $this, 'mailchimp_print' ] );
	}

	/**
	 * Print app banner tag.
	 */
	public function print_app_tag() {
		printf(
			'<amp-app-banner
				layout="nodisplay"
				id="banner">
				<div id="app-banner-text">%s</div>
				<div id="app-banner-action"><button open-button>%s</button></div>
			</amp-app-banner>',
			esc_html( $app_data->get_app_banner_text() ),
			esc_html( $app_data->get_app_banner_view() )
		);
	}

	/**
	 * Shortcode wrapper for mailchimp signup.
	 */
	public function mailchimp_print() {
		return terminal_get_template_part( 'signup-small' );
	}

	/**
	 * Call reading time shortcode.
	 */
	public function print_reading_time() {
		if ( get_post_type() === 'post' ) {
			echo do_shortcode( '[rt_reading_time label="Reading Time:" postfix="mins" postfix_singular="min"]' );
		}
	}
	/**
	 * Add email to list.
	 *
	 * @param string $email Email to add.
	 * @return bool|WP_Error True on success, otherwise WP Error.
	 */
	public function add_email_to_list( $email ) {
		$ad_data = Ad_Data::instance();
		if ( empty( $ad_data->get_mailchimp_list() ) || empty( $ad_data->get_mailchimp_api_key() ) ) {
			return 'Mailchimp is not configured. Try again later.';
		}
		$status   = 'subscribed';
		$args     = array(
			'method'  => 'PUT',
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( 'user:' . $ad_data->get_mailchimp_api_key() ),
			),
			'body'    => wp_json_encode( array(
				'email_address' => $email,
				'status'        => $status,
			) ),
		);
		$response = wp_remote_post(
			'https://' .
			substr( $ad_data->get_mailchimp_api_key(), strpos( $ad_data->get_mailchimp_api_key(), '-' ) + 1 ) .
			'.api.mailchimp.com/3.0/lists/' .
			$ad_data->get_mailchimp_list() .
			'/members/' .
			md5( strtolower( $email ) ),
			$args
		);
		if ( is_wp_error( $response ) ) {
			return 'A server error occurred. Try again later.';
		}
		$body = json_decode( $response['body'] );
		if ( 200 === $response['response']['code'] && $body->status === $status ) {
			// translators: Success message.
			return sprintf( __( 'Thanks for signing up, check %s for more.', 'terminal' ), $email );
		} else {
			return __( 'Sorry, we couldn\'t complete your request. Try again later.', 'terminal' );
		}
	}

	/**
	 * Admin AJAX endpoint for sending an email.
	 */
	public function ajax_email_signup() {
		if ( ! isset( $_REQUEST['email'] ) ) {
			wp_send_json_error( __( 'Sorry, we couldn\'t complete your request. Try again later.', 'terminal' ) );
		}
		if ( ! check_ajax_referer( 'email-signup-nonce', 'security', false ) ) {
			wp_send_json_error( __( 'Invalid security token.', 'terminal' ) );
		}
		$email = sanitize_email( wp_unslash( $_REQUEST['email'] ) );
		if ( ! is_email( $email ) ) {
			wp_send_json_error( __( 'Invalid format.', 'terminal' ) );
		}
		wp_send_json_success( $this->add_email_to_list( $email ) );
	}

	/**
	 * Print featured image info.
	 */
	public function print_featured_image_info() {
		\terminal_print_featured_image_caption();
	}

	/**
	 * Print sponsors module.
	 */
	public function print_sponsors_module() {
		echo '<div class="wrap">';
		terminal_print_template_part( 'sponsors' );
		echo '</div>';
		echo '<div class="wrap">';
		terminal_print_template_part( 'signup' );
		echo '</div>';
	}

	/**
	 * Filter plugin path.
	 *
	 * @return string Filtered path
	 */
	public function terminal_filter_amp_plugin_path() {
		return 'amp-wp/amp.php';
	}

}

add_action( 'after_setup_theme', [ '\Terminal\AMP', 'instance' ] );
