<?php
/**
 * Frontend.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Frontend.
 */
class Frontend {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		if ( ! is_admin() ) {
			remove_action( 'wp_head', 'wp_generator' );
			remove_action( 'wp_head', 'wp_shortlink_wp_head' );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
			add_filter( 'feed_links_show_comments_feed', '__return_false' );
			add_action( 'wp_enqueue_scripts', [ $this, 'disable_unipress_styles' ], 100 );
			add_action( 'wp_print_scripts', [ $this, 'disable_unipress_scripts' ], 100 );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
			add_filter( 'query_vars', [ $this, 'add_query_vars_filter' ] );
			add_filter( 'simple_local_avatar', [ $this, 'hack_for_ssl' ] );
			add_action( 'wp_head', [ $this, 'maybe_print_app_meta' ] );
			add_filter( 'dynamic_sidebar_params', [ $this, 'filter_sidebar_params' ] );
			add_filter( 'wpseo_breadcrumb_single_link', [ $this, 'adjust_single_breadcrumb' ] );
			add_filter( 'jetpack_implode_frontend_css', '__return_false' );
			add_action( 'wp_head', [ $this, 'remove_jetpack_crap' ], 130 );
			add_filter( 'option_coral_talk_container_classes', [ $this, 'filter_talk_classes' ] );
			add_filter('essb4_get_cached_counters', [ $this, 'filter_essb4_counters' ] );
		}
		$this->disable_emojis();
	}

	public function filter_essb4_counters( $current ) {
		$id = get_the_ID();
		if ( empty( $id ) ) {
			return;
		}
		$api_key = getenv( 'TERMINAL_PARSELY_API_KEY' );
		$api_secret = getenv( 'TERMINAL_PARSELY_API_SECRET' );

		if ( empty( $api_key ) || empty( $api_secret ) ) {
			return $current;
		}
		$url = get_the_permalink();
		$result = wp_cache_get( 'terminal-social-' . $id );
		if ( false === $result ) {
			$time_since_publish = current_time( 'timestamp' ) - get_the_time( 'U' );
			// If published more than a week ago, cache longer.
			if ( $time_since_publish > 6048000) {
				$cache_length = 864000;
			} elseif ( $time_since_publish > 604800) {
				$cache_length = 86400;
			} else {
				$cache_length = 8640;
			}
			$result = wp_remote_get(
				"https://api.parsely.com/v2/shares/post/detail?apikey=${api_key}&secret=${api_secret}&url=${url}"
			);
			wp_cache_set( 'terminal-social-' . $id, $result, '', $cache_length );
		}
		if ( empty( $result ) ) {
			return $current;
		}
		$json = json_decode( wp_remote_retrieve_body( $result ) );
		$updated = $current;
		$updated['facebook'] = ! empty( $json->data[0]->fb ) ? $json->data[0]->fb : $current['facebook'];
		$updated['twitter'] = ! empty( $json->data[0]->tw ) ? $json->data[0]->tw : $current['twitter'];
		$updated['linkedin'] = ! empty( $json->data[0]->li ) ? $json->data[0]->li : $current['linkedin'];
		$updated['pinterest'] = ! empty( $json->data[0]->pi ) ? $json->data[0]->pi : $current['pinterest'];
		$updated['total'] = ! empty( $json->data[0]->total ) ?  $json->data[0]->total : $current['total'];
		return $updated;
	}

	/**
	 * Filter sidebar params.
	 * @param array $params {
	 *     @type array $args  {
	 *         An array of widget display arguments.
	 *
	 *         @type string $name          Name of the sidebar the widget is assigned to.
	 *         @type string $id            ID of the sidebar the widget is assigned to.
	 *         @type string $description   The sidebar description.
	 *         @type string $class         CSS class applied to the sidebar container.
	 *         @type string $before_widget HTML markup to prepend to each widget in the sidebar.
	 *         @type string $after_widget  HTML markup to append to each widget in the sidebar.
	 *         @type string $before_title  HTML markup to prepend to the widget title when displayed.
	 *         @type string $after_title   HTML markup to append to the widget title when displayed.
	 *         @type string $widget_id     ID of the widget.
	 *         @type string $widget_name   Name of the widget.
	 *     }
	 *     @type array $widget_args {
	 *         An array of multi-widget arguments.
	 *
	 *         @type int $number Number increment used for multiples of the same widget.
	 *     }
	 * }
	 * @return aray filtered params
	 */
	public function filter_sidebar_params( $params ) {
		if ( ! empty( $params[0] ) && ! empty( $params[0]['id'] ) && in_array( $params[0]['id'], array(
			'terminal-header',
			'terminal-featured',
			'terminal-primary-sidebar',
			'terminal-stream-start',
			'terminal-stream-end',
			'terminal-footer'
		) ) ) {
			$new_params = $params;
			if ( 0 !== strpos( $params[0]['before_widget'], 'widget_ad_layers_ad_widget' ) ){
				$new_params[0]['before_widget'] = str_replace( 'widget_ad_layers_ad_widget', 'widget_ad_layers_ad_widget covered-target', $params[0]['before_widget'] );
			}
			return $new_params;
		}
		return $params;
	}

	/**
	 * Filter talk classes.
	 *
	 * @param $classes string existing classes.
	 * @return string Filtered classes.
	 */
	public function filter_talk_classes( $classes ) {
		return $classes . ' terminal-comments terminal-limit-max-content-width';
	}

	/**
	 * Remove Jetpack crap.
	 */
	public function remove_jetpack_crap() {
		wp_dequeue_style( 'wpcom-notes-admin-bar' );
		wp_deregister_script( 'wpcom-notes-common' );
		wp_deregister_script( 'wpcom-notes-admin-bar' );
		wp_deregister_style( 'tiled-gallery' );
		wp_deregister_style( 'noticons' );
	}
	/**
	 * Don't let Yoast duplicate title.
	 *
	 * @param string $link_output Current link output.
	 * @return string filtered output
	 */
	public function adjust_single_breadcrumb( $link_output ) {
		if ( strpos( $link_output, 'breadcrumb_last' ) !== false ) {
			$link_output = '';
		}
		return $link_output;
	}

	/**
	 * Maybe print iOS install bug.
	 */
	public function maybe_print_app_meta() {
		$data = Data::instance();
		$ios  = $data->get_ad_data( 'ios_install' );
		if ( ! empty( $ios ) ) {
			printf(
				'<meta name="apple-itunes-app" content="app-id=%s">',
				esc_attr( $ios )
			);
		}
	}

	/**
	 * Don't let http image URL be used.
	 *
	 * @param string $avatar Avatar URL.
	 * @return string Avatar URL.
	 */
	public function hack_for_ssl( $avatar ) {
		return str_replace( 'http://', 'https://', $avatar );
	}

	/**
	 * Okay 'filter' as query var.
	 *
	 * @param array $vars Query vars.
	 *
	 * @return array Filtered query vars.
	 */
	public function add_query_vars_filter( $vars ) {
			$vars[] = 'filter';
			return $vars;
	}

	/**
	 * Disable Unipress garbage.
	 */
	public function disable_unipress_styles() {
		wp_dequeue_style( 'unipress-api' );
		if ( ! is_singular() || ! has_shortcode( get_the_content(), 'logo-carousel-pro' ) ) {
			wp_dequeue_style( 'slick' );
			wp_dequeue_style( 'tooltipster-min-css' );
			wp_dequeue_style( 'logo-carousel-pro-style' );
			wp_dequeue_style( 'logo-carousel-pro-responsive');
		}
	}

	/**
	 * Disable Unipress garbage.
	 */
	public function disable_unipress_scripts() {
		wp_dequeue_script( 'unipress-api' );
		wp_dequeue_script( 'jquery-isotope-min-js' );
		wp_dequeue_script( 'jquery-masonry' );
		if ( ! is_singular() || ! has_shortcode( get_the_content(), 'logo-carousel-pro' ) ) {
			wp_dequeue_script( 'slick-min-js' );
			wp_dequeue_script( 'tooltipstermin-js' );
			wp_dequeue_script( 'logo-carousel-pro-scripts-js' );
		}
	}

	/**
	 * Disable emojis for native.
	 */
	public function disable_emojis() {
		add_filter( 'emoji_svg_url', '__return_false' );
		add_action( 'init', function() {
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			add_filter( 'tiny_mce_plugins', function( $plugins ) {
				if ( is_array( $plugins ) ) {
					return array_diff( $plugins, array( 'wpemoji' ) );
				} else {
					return array();
				}
			} );
		});
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( TERMINAL_APP, get_template_directory_uri() . '/client/build/index.bundle.js', array(), TERMINAL_VERSION, false );
		wp_deregister_style( 'ad-layers' );
		wp_deregister_style( 'ad-layers-dfp' );
		wp_deregister_script( 'wp-mediaelement' );
		wp_deregister_script( 'mediaelement-core' );
		wp_deregister_script( 'mediaelement-migrate' );
		wp_dequeue_style( 'wp-mediaelement' );
		wp_deregister_style( 'wp-parsely-style' );
		wp_dequeue_script( 'devicepx' );
		wp_deregister_script( 'wp-embed' );
		wp_deregister_style( 'the-neverending-homepage' );
		wp_deregister_style( 'tiled-gallery' );
		wp_deregister_script( 'tiled-gallery' );
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( TERMINAL_APP, get_template_directory_uri() . '/client/build/index.css', array(), TERMINAL_VERSION );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Frontend', 'instance' ] );
