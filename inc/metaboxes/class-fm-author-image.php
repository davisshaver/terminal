<?php
/**
 * FM author image.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for author image.
 */
class FM_Author_Image {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_filter( 'option_show_avatars', '__return_false' );
		add_action( 'fm_user', array( $this, 'register_author_image' ) );
	}

	/**
	 * Register featured image credit.
	 */
	public function register_author_image() {
		$fm = new \Fieldmanager_Group( array(
			'name'           => 'terminal_author_fields',
			'serialize_data' => false,
			'children' => array(
				'terminal_author_image' => new \Fieldmanager_Media( 'Headshot' ),
			),
		) );
		$fm->add_user_form( 'Author' );
	}
}

FM_Author_Image::instance();
