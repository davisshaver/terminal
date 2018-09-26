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
class Sponsors_Data {

	use Singleton;

	/**
	 * Sponsors data.
	 *
	 * @var array $sponsors_data Sponsors data.
	 */
	private $sponsors_data = array();


	/**
	 * Get sponsor data.
	 *
	 * @return array Sponsor data.
	 */
	public function get_all_sponsor_data() {
		if ( empty( $this->sponsor_data ) ) {
			$this->sponsor_data = get_option( 'terminal_sponsor_options' );
		}
		return $this->sponsor_data;
	}

	/**
	 * Get a sponsor data.
	 *
	 * @param string $key Optional key.
	 * @return string Sponsor data.
	 */
	public function get_sponsor_data( $key ) {
		if ( empty( $this->sponsor_data ) ) {
			$this->sponsor_data = get_option( 'terminal_sponsor_options' );
		}
		if ( ! empty( $this->sponsor_data[ $key ] ) ) {
			return $this->sponsor_data[ $key ];
		}
		return null;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Sponsors_Data', 'instance' ] );
