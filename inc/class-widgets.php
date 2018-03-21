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
		add_action( 'widgets_init', array( $this, 'maybe_register_broadstreet_widget' ) );
	}

	/**
	 * Possibly register Broadstreet widget.
	 */
	public function maybe_register_broadstreet_widget() {
		if (
			terminal_has_ads_enabled() &&
			class_exists( '\FM_Widget' )
		) {
			require_once __DIR__ . '/widgets/class-author-widget.php';
			require_once __DIR__ . '/widgets/class-cta-widget.php';
			require_once __DIR__ . '/widgets/class-category-widget.php';
			require_once __DIR__ . '/widgets/class-placement-widget.php';
			require_once __DIR__ . '/widgets/class-hero-widget.php';
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Widgets', 'instance' ] );
