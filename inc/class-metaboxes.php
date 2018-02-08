<?php
/**
 * Metaboxes.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Metaboxes.
 */
class Metaboxes {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		if ( defined( 'FM_VERSION' ) ) {
			require_once __DIR__ . '/metaboxes/class-fm-featured-image-credit.php';
			require_once __DIR__ . '/metaboxes/class-fm-author-image.php';
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Metaboxes', 'instance' ] );
