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
		$this->disable_xmlrpc();

		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'wp_generator' );
	}

	/**
	 * Drop XML-RPC links.
	 */
	private function disable_xmlrpc() {
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\API', 'instance' ] );
