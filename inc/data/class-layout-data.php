<?php
/**
 * Apps data helper.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for data loading.
 */
class Layout_Data {

	use Singleton;

	/**
	 * Layout data.
	 *
	 * @var array $layout_data Apps data.
	 */
	private $layout_data = array();

	/**
	 * Get layout data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_prepared_layout_data( $default = array() ) {
		$layout_options = get_option( 'terminal_layout_options', array() );
		if ( empty( $layout_options ) ) {
			return $default;
		}
		return array_merge( $default, $layout_options );
	}

	/**
	 * Get byline data.
	 *
	 * @param array $default Default options.
	 * @return array Data.
	 */
	public function get_prepared_byline_data( $default = array() ) {
		$byline_option = get_option( 'terminal_byline_options', array() );
		if ( empty( $byline_option ) ) {
			return $default;
		}
		return array_merge( $default, $byline_option );
	}

	/**
	 * Get prepared data.
	 *
	 * @param array $default Default options.
	 * @return array Prepared data.
	 */
	public function get_prepared_sidebar_data( $default = array() ) {
		$sidebar_options = get_option( 'terminal_sidebar_options', array() );
		if ( empty( $sidebar_options ) ) {
			return $default;
		}
		return array_merge( $default, $sidebar_options );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Layout_Data', 'instance' ] );
