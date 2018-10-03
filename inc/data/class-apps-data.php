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
class Apps_Data {

	use Singleton;

	/**
	 * Apps data.
	 *
	 * @var array $apps_data Apps data.
	 */
	private $apps_data = array();

	/**
	 * Get prepared data.
	 *
	 * @param string $key Optional key.
	 * @return array Prepared data.
	 */
	public function get_apps_data( $key = false ) {
		if ( empty( $this->apps_data ) ) {
			$this->apps_data = get_option( 'terminal_app_options', array() );
		}
		if ( ! $key ) {
			return $this->apps_data;
		} elseif ( isset( $this->apps_data[ $key ] ) ) {
			return $this->apps_data[ $key ];
		}
		return null;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Apps_Data', 'instance' ] );
