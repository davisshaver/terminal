<?php
/**
 * Update permalinks for correct JS-based routing.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for permalink actions.
 */
class Permalinks {

	use Singleton;
	/**
	 * Setup actions
	 */
	public function setup() {
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Permalinks', 'instance' ] );
