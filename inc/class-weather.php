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
		$this->api_key        = getenv( 'TERMINAL_DARKSKY' );
		$this->lat            = getenv( 'TERMINAL_LAT' );
		$this->long           = getenv( 'TERMINAL_LONG' );
		$this->transient_name = 'terminal-forecast-weather';
		if ( empty( $this->api_key ) || empty( $this->lat ) || empty( $this->long ) ) {
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
		if ( empty( $this->weather->daily->summary ) ) {
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
		if ( empty( $this->weather->hourly->summary ) ) {
			return;
		}
		return $this->weather->hourly->summary;
	}

	/**
	 * Retrieve and store weather.
	 */
	public function retrieve_and_store_weather() {
		$url      = sprintf(
			'https://api.darksky.net/forecast/%s/%s,%s?exclude=currently,alerts',
			esc_attr( $this->api_key ),
			esc_attr( $this->lat ),
			esc_attr( $this->long )
		);
		$response = wp_remote_get( $url, array( 'timeout' => 10 ) );
		if ( is_wp_error( $response ) ) {
			$this->weather = array();
			return;
		} else {
			$parsed = json_decode( wp_remote_retrieve_body( $response ) ); 
			set_transient( $this->transient_name, $parsed, HOUR_IN_SECONDS / 2 );
			$this->weather = $parsed;
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Weather', 'instance' ] );
