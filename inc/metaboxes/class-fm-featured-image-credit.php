<?php
/**
 * FM featured image credit integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for featured image credit/caption.
 */
class FM_Featured_Image_Credit {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'fm_post_post', array( $this, 'register_featured_image_credit' ) );
	}

	/**
	 * Register featured image credit.
	 */
	public function register_featured_image_credit() {
		$fm = new \Fieldmanager_Group( array(
			'name'           => 'terminal_featured_meta',
			'serialize_data' => false,
			'children'       => array(
				'credit'  => new \Fieldmanager_TextField( array(
					'label' => __( 'Credit', 'terminal' ),
				) ),
				'caption' => new \Fieldmanager_RichTextArea( array(
					'label'           => __( 'Caption', 'terminal' ),
					'editor_settings' => array(
						'media_buttons' => false,
					),
				) ),
			),
		) );
		$fm->add_meta_box( 'Featured Image', 'post' );
	}
}

FM_Featured_Image_Credit::instance();
