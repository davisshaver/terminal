<?php
/**
 * Newspack Integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Newspack.
 */
class Newspack {

    use Singleton;

    /**
     * Setup actions.
     */
    public function setup() {

    }

}

add_action( 'after_setup_theme', [ '\Terminal\Newspack', 'instance' ] );
