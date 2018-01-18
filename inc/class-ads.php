<?php
/**
 * Ads integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for ads.
 */
class Ads {

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
			if ( terminal_has_broadstreet_enabled() ) {
				require_once __DIR__ . '/widgets/class-broadstreet-widget.php';
			}
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Ads', 'instance' ] );
