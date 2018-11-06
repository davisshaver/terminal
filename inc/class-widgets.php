<?php
/**
 * Widgets integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for widgets.
 */
class Widgets {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}

	/**
	 * Possibly register Broadstreet widget.
	 */
	public function register_widgets() {
		if (
			class_exists( '\FM_Widget' )
		) {
			require_once __DIR__ . '/widgets/class-broadstreet-ad.php';
			require_once __DIR__ . '/widgets/class-category-widget.php';
			require_once __DIR__ . '/widgets/class-post-type-widget.php';
			require_once __DIR__ . '/widgets/class-dfp-ad.php';
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Widgets', 'instance' ] );
