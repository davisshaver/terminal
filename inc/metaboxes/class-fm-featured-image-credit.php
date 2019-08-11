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
	 * Register featured image credit for post type.
	 *
	 * @param $post_type string Post type.
	 */
	public function register_featured_image_for_post_type( $post_type ) {
		$fm = new \Fieldmanager_Group( array(
			'name'           => 'terminal_featured_meta',
			'serialize_data' => false,
			'children'       => array(
				'credit_user' => new \Fieldmanager_Select( array(
					'label'       => __( 'Credit (User) ', 'terminal' ),
					'first_empty' => true,
					'datasource'  => new \Fieldmanager_Datasource_User( array(
						'query_args' => [ 'role__in' => [ 'contributor', 'author', 'editor', 'administrator' ] ],
					) ),
				) ),
				'credit'  => new \Fieldmanager_TextField( array(
					'label' => __( 'Credit (Custom Override)', 'terminal' ),
				) ),
				'caption' => new \Fieldmanager_RichTextArea( array(
					'label'           => __( 'Caption', 'terminal' ),
					'editor_settings' => array(
						'media_buttons' => false,
					),
				) ),
				'add_featured_embed'  => new \Fieldmanager_Checkbox( 'Add featured embed' ),
				'use_featured_embed_on_landing' => new \Fieldmanager_Checkbox( array(
					'label' => __( 'Use embed on index pages', 'terminal' ),
					'display_if' => array( 'src' => 'add_featured_embed', 'value' => true ),
				)	),
				'use_featured_embed_on_single' => new \Fieldmanager_Checkbox( array(
					'label' => __( 'Use embed on single pages', 'terminal' ),
					'display_if' => array( 'src' => 'add_featured_embed', 'value' => true ),
				)	),
				'featured_embed' => new \Fieldmanager_RichTextArea( array(
					'label'           => __( 'Use embed on landing', 'terminal' ),
					'display_if'      => array( 'src' => 'add_featured_embed', 'value' => true ),
					'editor_settings' => array(
						'media_buttons' => false,
					),
				) ),
				'hide_featured_image'  => new \Fieldmanager_Checkbox( array(
					'label' => __( 'Hide featured image on single', 'terminal' ),
					'display_if' => array( 'src' => 'use_featured_embed_on_single', 'value' => false ),
				)	),
			),
		) );
		$fm->add_meta_box( 'Featured Image', $post_type );
	}

	/**
	 * Register featured image credit.
	 */
	public function register_featured_image_credit() {
		$this->register_featured_image_for_post_type( 'post' );
	}
}

FM_Featured_Image_Credit::instance();
