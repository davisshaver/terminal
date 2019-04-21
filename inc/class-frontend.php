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
		add_action( 'wp_head', [ $this, 'remove_more_jetpack_crap' ], 130 );
		add_action( 'admin_init', [ $this, 'wp_api' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'remove_jetpack_crap' ], 130 );
		add_filter( 'feed_links_show_comments_feed', '__return_false' );
		add_filter( 'jetpack_implode_frontend_css', '__return_false' );
		add_filter( 'query_vars', [ $this, 'add_query_vars_filter' ] );
		add_filter( 'simple_local_avatar', [ $this, 'hack_for_ssl' ] );
		add_filter( 'wpseo_breadcrumb_links', [ $this, 'wpseo_remove_home_breadcrumb' ] );
		add_filter( 'wpseo_breadcrumb_single_link', [ $this, 'adjust_single_breadcrumb' ] );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		add_filter( 'sidebars_widgets', [ $this, 'maybe_disable_ads' ] );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		add_filter( 'filter_ampnews_amp_plugin_dependency', '__return_true' );
		add_filter( 'wp_travel_checkout_fields', [ $this, 'wp_travel_customize_booking_option' ] );
		add_filter('wpseo_opengraph_title', [ $this, 'filter_yoast_og_title' ], 999);
		add_filter( 'body_class', [ $this, 'filter_body_class' ] );
	}

	/**
	 * Filter body class.
	 */
	public function filter_body_class( $classes ) {
		$classes[] = is_amp_endpoint() ? 'terminal-amp-active' : 'terminal-amp-inactive';
		return $classes;
	}
	/**
	 * Filter Yoast SEO OG title to remove site name.
	 *
	 * @param string $title Title
	 * @return string Filtered title
	 */
	public function filter_yoast_og_title( $title ) {
		$separator = " - ";
		$site_name = $separator . get_bloginfo( 'name' );
		return str_replace( $site_name, '', $title ) ;
	}

	/**
	 * Filter WP Travel booking options.
	 */
	public function wp_travel_customize_booking_option( $fields ) {
		$fields['payment_fields']['booking_option'] = array(
			'type' => 'select',
			'label' => __( 'Booking Options', 'terminal' ),
			'name' => 'wp_travel_booking_option',
			'id' => 'wp-travel-option',
			'validations' => array(
				'required' => 1
			),
			'options' => array(
				'booking_with_payment' => 'Booking with payment'
			),
			'default' => 'booking_with_payment',
			'priority' => 100
		);
		return $fields;
	}

	/**
	 * Filter widgets to disable ads.
	 *
	 * @param array $widgets Existing widgets.
	 * @return array Filtered widgets.
	 */
	public function maybe_disable_ads( $widgets ) {
		if ( is_customize_preview() || is_admin() ) {
			return $widgets;
		}
		$use_widgets = $widgets;
		array_shift( $widgets );
		$data    = Data::instance();
		$disable = $data->user_has_no_ad_id();
		if ( $disable ) {
			$disable_ads = false;
			foreach ( $widgets as $sidebar_name => $sidebar_widgets ) {
				foreach ( $sidebar_widgets as $key => $sidebar_widget ) {
					if (
						false !== strpos( $sidebar_widget, 'ad_layers' ) ||
						false !== strpos( $sidebar_widget, 'terminal-adsense-widget' ) ||
						false !== strpos( $sidebar_widget, 'terminal-broadstreet-widget' )
					) {
						unset( $use_widgets[ $sidebar_name ][ $key ] );
					}
				}
			}
		}
		return $use_widgets;
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( TERMINAL_APP, plugin_dir_url( dirname( __FILE__ ) ) . '/client/build/index.css', array(), TERMINAL_VERSION );
	}

	/**
	 * Filter Web App manifest
	 *
	 * @param array $links Manifest object.
	 * @return array Filtered manifest object.
	 */
	public function wpseo_remove_home_breadcrumb( $links ) {
		if ( ! empty( $links[0]['url'] ) && home_url( '/' ) === $links[0]['url'] ) {
			array_shift( $links );
		}
		return $links;
	}

	/**
	 * Ensure WP API loaded.
	 */
	public function wp_api() {
		wp_enqueue_script( 'wp-api' );
	}

	/**
	 * Remove Jetpack crap.
	 */
	public function remove_jetpack_crap() {
		if ( ! is_customize_preview() && ! is_admin() ) {
			wp_deregister_style( 'dashicons' );
			wp_deregister_style( 'jetpack-widget-social-icons-admin' );
			wp_deregister_style( 'jetpack-widget-social-icons-styles' );
			wp_deregister_style( 'the-neverending-homepage' );
			wp_deregister_style( 'tiled-gallery' );
			wp_deregister_style( 'tiled-gallery' );
			wp_deregister_style( 'wp-parsely-style' );
			wp_deregister_style( 'wpcom-notes-admin-bar' );
			wp_deregister_script( 'wpcom-notes-admin-bar' );
			wp_deregister_script( 'wpcom-notes-common' );
			wp_deregister_style( 'amp-default' );
		}
	}

	/**
	 * Remove Jetpack crap.
	 */
	public function remove_more_jetpack_crap() {
		if ( ! is_customize_preview() && ! is_admin() ) {
			wp_deregister_style( 'noticons' );
			wp_deregister_style( 'wpcom-notes-admin-bar' );
		}
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
}

add_action( 'after_setup_theme', [ '\Terminal\Frontend', 'instance' ] );
