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
			add_action( 'wp_head', [ $this, 'remove_jetpack_crap' ], 130 );
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
			remove_action( 'wp_head', 'wp_shortlink_wp_head' );
			add_filter( 'web_app_manifest', [ $this, 'filter_web_app_manifest' ] );
			add_filter( 'filter_ampnews_amp_plugin_dependency', '__return_true' );
		}
	}

	public function filter_web_app_manifest( $manifest ) {
		// Nothing to see here yet.
		return $manifest;
	}

	public function wpseo_remove_home_breadcrumb( $links ) {
		if ( $links[0]['url'] == home_url('/') ) {
			array_shift($links);
		}
		return $links;
	}

	/**
	 * Remove Jetpack crap.
	 */
	public function remove_jetpack_crap() {
		wp_deregister_script( 'wpcom-notes-admin-bar' );
		wp_deregister_script( 'wpcom-notes-common' );
		wp_deregister_style( 'dashicons' );
		wp_deregister_style( 'jetpack-widget-social-icons-admin' );
		wp_deregister_style( 'noticons' );
		wp_deregister_style( 'the-neverending-homepage' );
		wp_deregister_style( 'tiled-gallery' );
		wp_deregister_style( 'tiled-gallery' );
		wp_deregister_style( 'wp-parsely-style' );
		wp_deregister_style( 'wpcom-notes-admin-bar' );
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
