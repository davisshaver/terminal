<?php
/**
 * Pre-load the navigation menu as a JSON object
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for menu loading
 */
class Menu {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_filter( 'wp_enqueue_scripts', array( $this, 'print_data' ) );
	}

	/**
	 * Adds the json-string data to the react app script
	 */
	public function print_data() {
		$menu_data = sprintf(
			'var TerminalMenu = %s;',
			$this->add_json_data()
		);
		wp_add_inline_script( TERMINAL_APP, $menu_data, 'before' );
	}

	/**
	 * Dumps the current query response as a JSON-encoded string
	 */
	public function add_json_data() {
		return wp_json_encode( array(
			'enabled' => class_exists( 'WP_REST_Menus' ),
			'data'    => $this->get_menu_data(),
		) );
	}

	/**
	 * Gets menu data from the JSON API server
	 *
	 * @return array
	 */
	public function get_menu_data() {
		$menu = array();

		$request             = new \WP_REST_Request();
		$request['context']  = 'view';
		$request['location'] = 'primary';

		if ( class_exists( 'WP_REST_Menus' ) ) {
			$menu_api = new WP_REST_Menus();
			$menu     = $menu_api->get_menu_location( $request );
		}

		return $menu;
	}
}


add_action( 'after_setup_theme', [ '\Terminal\Menu', 'instance' ] );
