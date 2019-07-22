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
		add_action( 'ampnews-after-body', [ $this, 'print_extra_ads' ] );
		add_action( 'ampnews-after-body', [ $this, 'print_app_tag' ] );
		add_action( 'ampnews-before-excerpt', [ $this, 'print_reading_time' ] );
		add_action( 'ampnews-before-article', [ $this, 'print_reading_time' ] );
		add_action( 'ampnews-after-search-form', [ $this, 'print_example_searches' ] );
		if ( apply_filters( 'terminal_show_analytics', current_user_can( 'edit_posts' ) ) ) {
			add_action( 'ampnews-before-excerpt', [ $this, 'print_analytics' ] );
			add_action( 'ampnews-before-article', [ $this, 'print_analytics' ] );
		}
		add_action( 'ampnews-before-footer', [ $this, 'print_sticky_ad' ] );
		add_action( 'ampnews-before-footer-single', [ $this, 'print_sponsors_module' ] );
		add_action( 'ampnews-before-footer', [ $this, 'print_sponsors_module' ] );
		add_action( 'ampnews-before-footer', [ $this, 'print_popular_module' ], 100 );
		add_action( 'ampnews-before-footer', [ $this, 'print_signup_module' ], 1 );
		add_action( 'ampnews-after-comments', [ $this, 'print_popular_module' ], 100 );
		add_action( 'ampnews-after-comments', [ $this, 'print_signup_module' ] );
		add_action( 'ampnews-before-mp-login', [ $this, 'print_login_module' ] );
		add_action( 'ampnews-after-entry-thumbnail', [ $this, 'print_featured_image_info' ] );
		add_action( 'wp_ajax_email_signup', [ $this, 'ajax_email_signup' ] );
		add_action( 'wp_ajax_nopriv_email_signup', [ $this, 'ajax_email_signup' ] );
		add_shortcode( 'terminal-mailchimp', [ $this, 'mailchimp_print' ] );
		add_shortcode( 'terminal-sponsor', [ $this, 'sponsor_print' ] );
		add_shortcode( 'terminal-ad', [ $this, 'print_ad' ] );
		add_filter( 'wp_kses_allowed_html', [ $this, 'add_amp_ad' ], 10, 1 );
		add_filter( 'show_admin_bar', '__return_false' );
		add_filter( 'ampnews-facebook-app-id', function() {
			return '2375172122533060';
		} );
		add_filter( 'wp_enqueue_scripts', function() {
			wp_enqueue_script( 'amp-bind' );
			wp_enqueue_script( 'amp-img' );
			wp_enqueue_script( 'amp-mustache' );
			wp_enqueue_script( 'amp-live-list' );
			wp_enqueue_script( 'amp-sidebar' );
			global $wp_query;
			$enqueue_amp_form = true;
			if (
				'memberpressproduct' !== $wp_query->get( 'post_type', false ) &&
				! in_array(
					$wp_query->get( 'pagename', false ),
					array(
						'affiliate-login',
						'affiliate-dashboard',
						'affiliate-signup',
						'login',
						'account',
						'cart',
						'checkout'
					),
					true
				)
			) {
				$enqueue_amp_form = false;
			}
			$has_gravity_form = false;
			if ( ! empty( $query->posts ) ) {
				$post = $query->posts[0];
				if (
					! empty( $post->post_content ) &&
					strpos( $post->post_content, 'gravityform' ) !== false
				) {
					$has_gravity_form = true;
				}
			}
			if ( $has_gravity_form ) {
				$enqueue_amp_form = false;
			}
			// if ( $enqueue_amp_form ) {
				wp_enqueue_script( 'amp-form' );
			// }
			wp_enqueue_script( 'amp-analytics' );
			wp_enqueue_script( 'amp-social-share' );
			wp_enqueue_script( 'terminal-broadstreet', 'https://cdn.broadstreetads.com/init-2.min.js' );
			wp_add_inline_script( 'terminal-broadstreet', 'broadstreet.watch()' );
		} );
		add_filter( 'amp_supportable_templates', function( $templates ) {
			if ( is_singular() ) {
				$templates['terminal_single'] = array(
					'label'     => __( 'Terminal Singular', 'example' ),
					'callback'  => function( \WP_Query $query ) {
						if ( 'itineraries' === $query->get( 'post_type', false ) ) {
							return true;
						}
						if ( 'memberpressproduct' === $query->get( 'post_type', false ) ) {
							return true;
						}
						if ( in_array(
							$query->get( 'pagename', false ),
							array(
								'affiliate-login',
								'affiliate-dashboard',
								'affiliate-signup',
								'login',
								'account',
								'cart',
								'checkout'
							),
							true
						) ) {
							return true;
						}
						// If it has a gravity form.
						$has_form = false;
						if ( ! empty( $query->posts ) ) {
							$post = $query->posts[0];
							if (
								! empty( $post->post_content ) &&
								strpos( $post->post_content, 'gravityform' ) !== false
							) {
								$has_form = true;
							}
						}
						if ( $has_form ) {
							return true;
						}
						return false;
					},
					'parent'    => 'is_singular',
					'supported' => false,
				);
			}
			return $templates;
		} );
		add_filter( 'ampnews-signup-link', [ $this, 'get_membership_link' ] );
		add_filter( 'ampnews-signup-text', [ $this, 'get_membership_text' ] );
		add_filter ( 'ampnews-show-single-image', [ $this, 'filter_featured_image_amp_single' ], 10, 1 );
		add_filter( 'mepr-unauthorized-login-link-text', '__return_empty_string' );
		add_filter( 'one-time-login-logged-in', [ $this, 'filter_login_logged_in' ] );
		add_filter( 'mepr-mailchimptags-add-subscriber-args', [ $this, 'filter_member_press_tags' ], 11, 2 );
		add_filter( 'ampnews-index-template', [ $this, 'filter_ampnews_index_template' ] );
	}

	public function sponsor_print( $atts ) {
		if ( ! empty( $atts ) && ! empty( $atts['title'] ) ) {
			$title = $atts['title'];
		} else {
			$title = false;
		}
		if ( ! empty( $atts ) && ! empty( $atts['heading_tag'] ) ) {
			$heading_tag = $atts['heading_tag'];
		} else {
			$heading_tag = false;
		}
		return terminal_get_template_part( 'sponsors', array(
			'title' => $title,
			'heading_tag' => $heading_tag
		) );
	}

	public function filter_ampnews_index_template( $template ) {
		$post_type = get_post_type();
		if ( 'post' !== $post_type ) {
			return 'templates/entry/slim-minimal';
		}
		return $template;
	}

	public function filter_member_press_tags( $args, $member ) {
		$planning = get_user_meta( $member->ID, 'mepr_send_planning_calendar', true );
		if ( ! empty( $planning ) ) {
			$args['merge_fields']['MERGE15'] = $planning;
		}
		return $args;
	}

	/**
	 * Filter login logged in.
	 *
	 * @param string $current Current markup.
	 * @return string Filtered markup.
	 */
	public function filter_login_logged_in() {
		global $wp;
		return sprintf(
				'<p>%s <a href="%s">%s</a></p><p>%s <a href="%s">%s</a></p>',
				__( 'Already logged in.', 'one-time-login' ),
				esc_url( wp_logout_url( home_url( $wp->request ) ) ),
				__( 'Logout.', 'one-time-login' ),
				__( 'Thank you for sponsoring LebTown.', 'one-time-login' ),
				esc_url( home_url( '/account/' ) ),
				__( 'Access account settings.', 'one-time-login' )
		);
	}
	/**
	 * Filter featured image on single in AMP theme.
	 */
	public function filter_featured_image_amp_single( $show_featured ) {
		$data = Data::instance();
		$meta = $data->get_post_featured_meta();
		return empty( $meta['hide_featured_image'] );
	}

	/**
	 * Get membership text.
	 *
	 * @param string $default Current text.
	 * @return string Membership text or false.
	 */
	public function get_membership_text( $default ) {
		$data = Data::instance();
		$text = $data->get_membership_text();
		if ( empty( $text ) ) {
			return $default;
		}
		return $text;
	}

	/**
	 * Get membership link.
	 *
	 * @return string Membership link or false.
	 */
	public function get_membership_link() {
		$data = Data::instance();
		$link = $data->get_membership_page();
		if ( empty( $link ) ) {
			return false;
		}
		return $link;
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
		$tags['broadstreet-zone'] = array(
			'zone-id' => true,
		);
		return $tags;
	}

	/**
	 * Print GTM container.
	 */
	public function print_gtm_container() {
		$data         = Data::instance();
		$disable      = $data->user_has_no_ad_id();
		$do_not_track = $data->is_do_not_track();
		if ( $disable || $do_not_track ) {
			return;
		}
		echo '<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-WSHC4JR&gtm.url=SOURCE_URL" data-credentials="include">';
		printf(
			'<script type="application/json">{ "vars": %s }</script>',
			// phpcs:ignore
			terminal_print_data_layer_json( false )
		);
		echo '</amp-analytics>';
	}

	/**
	 * Print extra ads.
	 */
	public function print_extra_ads() {
		$ad_data = Ad_Data::instance();
		$extra   = $ad_data->get_extra_ads();
		if ( empty( $extra ) ) {
			return;
		}
		// phpcs:ignore
		echo $extra;
	}

	/**
	 * Print one time login.
	 */
	public function print_login_module() {
		printf(
			'<h3>%s</h3>',
			__( 'Get a login link sent to you', 'terminal' )
		);
		echo do_shortcode( '[one-time-login]' );
		printf(
			'<h3>%s</h3>',
			__( 'Login with a password', 'terminal' )
		);
	}

	/**
	 * Print app banner tag.
	 */
	public function print_app_tag() {
		$app_data = Apps_Data::instance();
		if ( ! $app_data->apps_enabled() ) {
			return;
		}
		$data    = Data::instance();
		$disable = $data->user_has_no_ad_id();
		if ( $disable ) {
			return;
		}
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
			'<span class="terminal-analytics">%s %s %s %s %s</span> ',
			esc_html( number_format( $views ) ),
			esc_html( __( 'views ', 'terminal' ) ),
			esc_html( __( 'and ', 'terminal' ) ),
			esc_html( number_format( $shares ) ),
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
	 * Print ad.
	 */
	public function print_ad( $atts ) {
		$data    = Data::instance();
		$disable = $data->user_has_no_ad_id();
		if ( $disable ) {
			return;
		}
		if ( ! empty( $atts['zone'] ) ) {
			$ad_unit = $atts['zone'];
		} else {
			$ad_unit = '70108';
		}
		if ( ! empty( $atts['amp_unit'] ) ) {
			$amp_unit = $atts['unit'];
		} else {
			$amp_unit = '70108';
		}
		if ( ! empty( $atts['amp_disable'] ) ) {
			$amp_disable = true;
		} else {
			$amp_disable = false;
		}
		if ( ! empty( $atts['width'] ) ) {
			$ad_width = $atts['width'];
		} else {
			$ad_width = '300';
		}
		if ( ! empty( $atts['height'] ) ) {
			$ad_height = $atts['height'];
		} else {
			$ad_height = '250';
		}
		if ( ! empty( $atts['disable_header'] ) ) {
			$header = false;
		} else {
			$header = __( 'Advertisement', 'terminal' );
		}
		return terminal_broadstreet_ad( $ad_height, $ad_width, $ad_unit, $amp_unit, $header, $amp_disable );
	}

	/**
	 * Print signup module.
	 */
	public function print_signup_module() {
		$data    = Data::instance();
		$disable = $data->user_has_no_ad_id();
		if ( $disable ) {
			return;
		}
		echo '<div class="wrap">';
		terminal_print_template_part( 'signup' );
		echo '</div>';
	}

	/**
	 * Print popular module.
	 */
	public function print_popular_module() {
		$api_key = getenv( 'TERMINAL_PARSELY_API_KEY' );
		$api_secret = getenv( 'TERMINAL_PARSELY_API_SECRET' );
		if ( empty( $api_key ) || empty( $api_secret ) ) {
			return;
		}
		echo '<div class="wrap">';
		terminal_print_template_part( 'popular' );
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

	/**
	 *
	 * Print example searches.
	 */
	public function print_example_searches() {
		$header_data = terminal_get_header_data( array(
			'example_searches'       => '',
			'example_searches_title' => '',
		) );
		if ( ! empty( $header_data['example_searches'] ) ) {
			echo '<div class="terminal-example-searches-container">';
			$example_searches_title = ! empty( $header_data['example_searches_title'] ) ?
				$header_data['example_searches_title'] :
				__( 'Example Searches', 'terminal' );
			printf(
				'<h3>%s</h3>',
				esc_html( $example_searches_title )
			);
			echo '<ul class="terminal-example-searches">';
			foreach ( $header_data['example_searches'] as $search ) {
				printf(
					'<li class="terminal-example-search">%s</li>',
					wp_kses_post( $search['search'] )
				);
			}
			echo '</ul>';
			echo '</div>';
		}
	}

}

add_action( 'after_setup_theme', [ '\Terminal\AMP', 'instance' ] );
