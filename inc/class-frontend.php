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
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
			add_action( 'wp_enqueue_scripts', [ $this, 'disable_unipress_styles' ], 100 );
			add_action( 'wp_print_scripts', [ $this, 'disable_unipress_scripts' ], 100 );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
			add_filter( 'query_vars', [ $this, 'add_query_vars_filter' ] );
			add_filter( 'simple_local_avatar', [ $this, 'hack_for_ssl' ] );
			add_action( 'wp_head', [ $this, 'maybe_print_app_meta' ] );
			add_filter( 'wpseo_breadcrumb_single_link', [ $this, 'adjust_single_breadcrumb' ] );
			add_filter( 'jetpack_implode_frontend_css', '__return_false' );
			add_action( 'wp_head', [ $this, 'remove_jetpack_crap' ], 130 );
		}
		$this->disable_emojis();
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
	}

	/**
	 * Disable Unipress garbage.
	 */
	public function disable_unipress_scripts() {
		wp_dequeue_script( 'unipress-api' );
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
		if ( terminal_has_broadstreet_enabled() ) {
			wp_enqueue_script( 'broadstreet', 'https://cdn.broadstreetads.com/init-2.min.js', array(), TERMINAL_VERSION, true );
		}
		if ( is_singular() ) {
			wp_enqueue_script( TERMINAL_APP . '-single', get_template_directory_uri() . '/client/build/single.bundle.js', array(), TERMINAL_VERSION, false );
		} else {
			wp_enqueue_script( TERMINAL_APP, get_template_directory_uri() . '/client/build/index.bundle.js', array(), TERMINAL_VERSION, false );
		}
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
		if ( is_singular() ) {
			wp_enqueue_style( TERMINAL_APP . '-single', get_template_directory_uri() . '/client/build/single.css', array(), TERMINAL_VERSION );
		} else {
			wp_enqueue_style( TERMINAL_APP, get_template_directory_uri() . '/client/build/index.css', array(), TERMINAL_VERSION );
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Frontend', 'instance' ] );
