<?php
/**
 * Customizer.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Customizer.
 */
class Customizer {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_filter( 'site_icon_meta_tags', [ $this, 'site_icon_meta_tags' ] );
		// Helllllo Fieldmanager!
		// require_once __DIR__ . '/widgets/class-broadstreet-widget.php' // @todo add this back.
		if ( defined( 'FM_BETA_CUSTOMIZE_VERSION' ) ) {
			require_once __DIR__ . '/customizer/class-fm-ads.php';
			// require_once __DIR__ . '/customizer/class-fm-apps.php';
			// require_once __DIR__ . '/customizer/class-fm-bylines.php';
			// require_once __DIR__ . '/customizer/class-fm-fonts.php';
			// require_once __DIR__ . '/customizer/class-fm-footer.php';
			// require_once __DIR__ . '/customizer/class-fm-header.php';
			// require_once __DIR__ . '/customizer/class-fm-layout.php';
			// require_once __DIR__ . '/customizer/class-fm-membership.php';
			// require_once __DIR__ . '/customizer/class-fm-sponsors.php';
		}
	}

	/**
	 * Don't print site icon meta tags in the admin.
	 *
	 * @param array $tags Tags.
	 *
	 * @return array Tags
	 */
	public function site_icon_meta_tags( $tags ) {
		if ( is_admin() ) {
			return array();
		}
		return $tags;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Customizer', 'instance' ] );
