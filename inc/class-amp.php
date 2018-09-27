<?php
/**
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for AMP.
 */
class AMP {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
        add_filter( 'filter_ampnews_amp_plugin_path', [ $this, 'terminal_filter_amp_plugin_path' ] );
    }
    
    public function terminal_filter_amp_plugin_path() {
        return 'amp-wp/amp.php';
    }

}

add_action( 'after_setup_theme', [ '\Terminal\AMP', 'instance' ] );
