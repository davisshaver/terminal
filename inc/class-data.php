<?php
/**
 * Pre-load the first page's query response as a JSON object
 * Skips the need for an API query on the initial load of a page
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for data loading.
 */
class Data {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {

	}

}

add_action( 'after_setup_theme', [ '\Terminal\Data', 'instance' ] );
