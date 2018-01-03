<?php
/**
 * Settings.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Settings.
 */
class Settings {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		// @todo
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Settings', 'instance' ] );
