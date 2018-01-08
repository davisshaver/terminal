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

	}

	/**
	 * Get Twitter count for post.
	 *
	 * @return int
	 */
	public function get_twitter_count_for_post() {
		return 0;
	}

	/**
	 * Get comment count for post.
	 *
	 * @return int
	 */
	public function get_comment_count_for_post() {
		return 0;
	}

	/**
	 * Get Facebook count for post.
	 *
	 * @return int
	 */
	public function get_facebook_count_for_post() {
		return 0;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Data', 'instance' ] );
