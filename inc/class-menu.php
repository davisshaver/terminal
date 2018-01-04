<?php
/**
 * Pre-load the navigation menu as a JSON object
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for menu loading
 */
class Menu {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
	}

}


add_action( 'after_setup_theme', [ '\Terminal\Menu', 'instance' ] );
