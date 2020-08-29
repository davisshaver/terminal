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
        if ( defined( 'FM_BETA_CUSTOMIZE_VERSION' ) ) {
            require_once __DIR__ . '/customizer/class-fm-sponsors.php';
        }
    }
}

add_action( 'after_setup_theme', [ '\Terminal\Customizer', 'instance' ] );
