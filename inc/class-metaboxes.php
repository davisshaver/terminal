<?php
/**
 * Metaboxes.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Metaboxes.
 */
class Metaboxes {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		if ( defined( 'FM_VERSION' ) ) {
			add_filter( 'option_show_avatars', '__return_false' );
			add_action( 'fm_user', array( $this, 'register_author_image' ) );
			require_once __DIR__ . '/metaboxes/class-fm-featured-image-credit.php';
		}
	}

	/**
	 * Register featured image credit.
	 */
	public function register_author_image() {
		$fm = new \Fieldmanager_Group( array(
			'name'           => 'terminal_author_fields',
			'serialize_data' => false,
			'children'       => array(
				'terminal_author_image' => new \Fieldmanager_Media( 'Headshot' ),
			),
		) );
		$fm->add_user_form( 'Author' );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Metaboxes', 'instance' ] );
