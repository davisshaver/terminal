<?php
/**
 * Pre-load the first page's query response as a JSON object
 * Skips the need for an API query on the initial load of a page
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for data loading.
 */
class Data {

	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_action( 'pre_get_posts', [ $this, 'unstick_stickies' ] );
		add_filter( 'wp_enqueue_scripts', [ $this, 'print_data' ] );
	}

	/**
	 * Unstick sticky posts to mirror the behavior of the REST API
	 *
	 * @param WP_Query $query The WP_Query object.
	 */
	public function unstick_stickies( $query ) {
		if ( $query->is_main_query() ) {
			$query->set( 'ignore_sticky_posts', true );
			$query->set( 'posts_per_page', 10 );
		}
	}

	/**
	 * Adds the json-string data to the react app script
	 */
	public function print_data() {
		$data_layer = sprintf(
			'var TerminalData = %s;',
			$this->add_json_data()
		);
		wp_add_inline_script( TERMINAL_APP, $data_layer, 'before' );
	}

	/**
	 * Dumps the current query response as a JSON-encoded string
	 */
	public function add_json_data() {
		return wp_json_encode( array(
			'user' => $this->get_user_data(),
			'site' => $this->get_site_data(),
			'page' => array(
				'data'   => $this->get_post_data(),
				'paging' => $this->get_total_pages(),
			),
		) );
	}

	/**
	 * Gets user data for initial page load.
	 *
	 * @return array
	 */
	private function get_user_data() {
		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );
		return array(
			'userID'      => $user_id,
			'userDisplay' => $user ? $user->display_name : '',
		);
	}

	/**
	 * Gets site data for initial page load.
	 *
	 * @return array
	 */
	private function get_site_data() {
		$url  = trailingslashit( home_url() );
		$path = trailingslashit( wp_parse_url( $url )['path'] );
		return array(
			'URL'      => array(
				'base' => esc_url_raw( $url ),
				'path' => $path,
			),
			'endpoint' => esc_url_raw( $url ),
			'nonce'    => wp_create_nonce( 'wp_rest' ),
			'meta'     => array(
				'title'       => get_bloginfo( 'name', 'display' ),
				'description' => get_bloginfo( 'description', 'display' ),
			),
		);
	}

	/**
	 * Gets current posts data from the JSON API server
	 *
	 * @return array
	 */
	public function get_post_data() {
		if ( ! ( ( is_home() && ! is_paged() ) || is_page() || is_singular() ) ) {
			return array();
		}

		$posts = $GLOBALS['wp_query']->posts;

		$rest_server        = rest_get_server();
		$data               = array();
		$request            = new \WP_REST_Request();
		$request['context'] = 'view';

		foreach ( (array) $posts as $post ) {
			$controller = new \WP_REST_Posts_Controller( $post->post_type );
			$data[]     = $rest_server->response_to_data( $controller->prepare_item_for_response( $post, $request ), true );
		}

		return $data;
	}

	/**
	 * Gets current posts data from the JSON API server
	 *
	 * @return int
	 */
	public function get_total_pages() {
		if ( is_404() ) {
			return 0;
		}

		return intval( $GLOBALS['wp_query']->max_num_pages );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Data', 'instance' ] );
