<?php
/**
 * Pre-load the first page's query response as a JSON object
 * Skips the need for an API query on the initial load of a page
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for API.
 */
class API {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		// Allow anon comments via API when using this theme.
		add_filter( 'rest_allow_anonymous_comments', '__return_true' );

		$this->disable_xmlrpc();

		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );

		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'wp_generator' );
	}

	/**
	 * Drop XML-RPC links.
	 */
	private function disable_xmlrpc() {
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
	}
	/**
	 * Add endpoint for the site title.
	 *
	 * This is needed because rendered settings are not returned in the WP REST API settings endpoint.
	 */
	public function rest_api_init() {
		register_rest_route( 'terminal/v1', 'title', array(
			'callback' => 'terminal_rest_api_title_endpoint',
		) );
	}

	/**
	 * Get the raw and rendered title.
	 *
	 * @return array Raw and rendered title.
	 */
	public function terminal_rest_api_title_endpoint() {
		return array(
			'raw'      => get_bloginfo( 'blogname', 'raw' ),
			'rendered' => get_bloginfo( 'blogname', 'display' ),
		);
	}
}

add_action( 'after_setup_theme', [ '\Terminal\API', 'instance' ] );
