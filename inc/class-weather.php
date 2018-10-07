<?php
/**
 * Weather Integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for weather.
 */
class Weather {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_shortcode( 'terminal-weather-hourly', [ $this, 'hourly' ] );
		add_shortcode( 'terminal-weather-daily', [ $this, 'hourly' ] );
		if ( ! class_exists( 'Darksky' ) ) {
			return;
		}
		$this->api_key        = getenv( 'TERMINAL_DARKSKY' );
		$this->lat            = getenv( 'TERMINAL_LAT' );
		$this->long           = getenv( 'TERMINAL_LONG' );
		$this->transient_name = 'terminal-weather';
		if ( $this->api_key || $this->lat || $this->long ) {
			return;
		}
		$this->weather = get_transient( $this->transient_name );
		if ( empty( $this->weather ) ) {
			$this->retrieve_and_store_weather();
		}
	}

	/**
	 * Daily summary.
	 *
	 * @return string Daily summary.
	 */
	public function daily() {
		if ( ! empty( $this->weather->daily->summary ) ) {
			return;
		}
		return $this->weather->daily->summary;
	}

	/**
	 * Hourly summary.
	 *
	 * @return string Hourly summary.
	 */
	public function hourly() {
		if ( ! empty( $this->weather->hourly->summary ) ) {
			return;
		}
		return $this->weather->hourly->summary;
	}

	/**
	 * Retrieve and store weather.
	 */
	public function retrieve_and_store_weather() {
		$this->weather = ( new \Darksky( $this->api_key ) )->forecast( $this->lat, $this->long, [
			'minutely',
			'alerts',
		] );
		set_transient( 'terminal_weather', $this->weather, HOUR_IN_SECONDS / 2 );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Weather', 'instance' ] );
