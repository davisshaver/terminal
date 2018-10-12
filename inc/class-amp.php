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
		add_action( 'ampnews-after-body', [ $this, 'print_gtm_container' ] );
		add_action( 'ampnews-after-body', [ $this, 'print_app_tag' ] );
		add_action( 'ampnews-before-excerpt', [ $this, 'print_reading_time' ] );
		add_action( 'ampnews-before-article', [ $this, 'print_reading_time' ] );
		if ( apply_filters( 'terminal_show_analytics', current_user_can( 'edit_posts' ) ) ) {
			add_action( 'ampnews-before-excerpt', [ $this, 'print_analytics' ] );
			add_action( 'ampnews-before-article', [ $this, 'print_analytics' ] );
		}
		add_action( 'ampnews-before-footer', [ $this, 'print_sticky_ad' ] );
		add_action( 'ampnews-before-footer', [ $this, 'print_sponsors_module' ] );
		add_action( 'ampnews-before-footer', [ $this, 'print_signup_module' ], 1 );
		add_action( 'ampnews-after-comments', [ $this, 'print_signup_module' ] );
		add_action( 'ampnews-before-entry-header', [ $this, 'print_featured_image_info' ] );
		add_action( 'wp_ajax_email_signup', [ $this, 'ajax_email_signup' ] );
		add_action( 'wp_ajax_nopriv_email_signup', [ $this, 'ajax_email_signup' ] );
		add_shortcode( 'terminal-mailchimp', [ $this, 'mailchimp_print' ] );
		add_filter( 'wp_kses_allowed_html', [ $this, 'add_amp_ad' ], 10, 1 );
	}

	/**
	 * Print sticky ad.
	 */
	public function print_sticky_ad() {
		$ad_data = Ad_Data::instance();
		$sticky  = $ad_data->get_amp_sticky_ad();
		if ( empty( $sticky ) ) {
			return;
		}
		// phpcs:ignore
		echo '<amp-sticky-ad layout="nodisplay">';
		// phpcs:ignore
		echo $ad_data->get_amp_sticky_ad();
		echo '</amp-sticky-ad>';
	}

	/**
	 * Add amp-ad to allowed wp_kses_post tags
	 *
	 * @param string $tags Allowed tags, attributes, and/or entities.
	 *
	 * @return mixed
	 */
	public function add_amp_ad( $tags ) {
		$tags['amp-ad'] = array(
			'width'          => true,
			'height'         => true,
			'type'           => true,
			'data-ad-client' => true,
			'data-ad-slot'   => true,
			'layout'         => true,
		);
		return $tags;
	}

	/**
	 * Print GTM container.
	 */
	public function print_gtm_container() {
		echo '<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-WSHC4JR&gtm.url=SOURCE_URL" data-credentials="include">';
		printf(
			'<script type="application/json">{ "vars": %s }</script>',
			// phpcs:ignore
			terminal_print_data_layer_json( false )
		);
		echo '</amp-analytics>';
	}

	/**
	 * Print app banner tag.
	 */
	public function print_app_tag() {
		$app_data = Apps_Data::instance();
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
	 * Print analytics
	 */
	public function print_analytics() {
		$parsely = Parsely::instance();
		$views   = $parsely->get_cached_meta( get_the_id(), 'terminal_parsely_analytics_views', true, 'analytics' );
		$shares  = $parsely->get_cached_meta( get_the_id(), 'terminal_parsely_facebook_shares', true, 'shares' );
		if (
			empty( $views ) ||
			empty( $shares ) ||
			$views < 2 ||
			$shares < 2
		) {
			return;
		}
		printf(
			'<span class="terminal-analytics">%s %s %s %s %s</span>',
			esc_html( $views ),
			esc_html( __( 'views ', 'terminal' ) ),
			esc_html( __( 'and ', 'terminal' ) ),
			esc_html( $shares ),
			esc_html( __( ' shares', 'terminal' ) )
		);
	}

	/**
	 * Call reading time shortcode.
	 */
	public function print_reading_time() {
		if ( get_post_type() === 'post' ) {
			echo do_shortcode( '[rt_reading_time label="" postfix="min read" postfix_singular="min read"]' );
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
	 * Print signup module.
	 */
	public function print_signup_module() {
		echo '<div class="wrap">';
		terminal_print_template_part( 'signup' );
		echo '</div>';
	}

	/**
	 * Print sponsors module.
	 */
	public function print_sponsors_module() {
		echo '<div class="wrap">';
		terminal_print_template_part( 'sponsors' );
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
