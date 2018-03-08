<?php
/**
 * Photos post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for community.
 */
class Photos {

	use Singleton;

	private $photo_post_type = 'photos';
	private $photo_post_type_photo_key = 'photographer';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_PHOTO_POST_TYPE' ) ) {
			$this->photo_post_type =  getenv( 'TERMINAL_PHOTO_POST_TYPE' );
		}
		if ( getenv( 'TERMINAL_PHOTO_POST_TYPE_PHOTOG_KEY' ) ) {
			$this->photo_post_type_photo_key = getenv( 'TERMINAL_PHOTO_POST_TYPE_PHOTOG_KEY' );
		}
		add_action( 'init', [ $this, 'register_photo_post_type' ] );
		add_filter( 'pre_get_posts', array( $this, 'include_photo_post_type_in_rss' ) );
		add_action( 'init', [ $this, 'register_photo_fields' ] );
	}

	/**
	 * Get photo post type.
	 */
	public function get_photo_post_type() {
		return $this->photo_post_type;
	}

	/**
	 * Get photo post type.
	 */
	public function get_photographer( $attribute = 'display_name' ) {
		$photographer = get_post_meta( get_the_ID(), $this->photo_post_type_photo_key, true );
		if ( ! empty( $photographer ) ) {
			$photographer = get_userdata( $photographer );
			if ( $photographer ) {
				return $photographer->$attribute;
			}
		}
		return get_the_author_meta( $attribute );
	}

	/**
	 * Register community fields.
	 */
	public function register_photo_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_Autocomplete( array(
				'name' => $this->photo_post_type_photo_key,
				'datasource' => new \Fieldmanager_Datasource_User(),
			) );
			$fm->add_meta_box( 'Photo Credit', $this->photo_post_type );
		}
	}

	/**
	 * Include community post type.
	 *
	 * @param object $query Query
	 * @return object Filtered query
	 */
	public function include_photo_post_type_in_rss( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->photo_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->photo_post_type, 'post' ) );
			}
		}
		return $query;
	}


	/**
	 * Register community post type.
	 */
	public function register_photo_post_type() {
		$photo_labels = array(
			'name'                	=> __( 'Photos', 'terminal' ),
			'singular_name'       	=> __( 'Photos', 'terminal' ),
			'menu_name'           	=> __( 'Photos', 'terminal' ),
			'all_items'           	=> __( 'All Photos', 'terminal' ),
			'view_item'           	=> __( 'View Photo', 'terminal' ),
			'add_new_item'        	=> __( 'Add New Photo', 'terminal' ),
			'add_new'             	=> __( 'Add New', 'terminal' ),
			'edit_item'           	=> __( 'Edit Photo', 'terminal' ),
			'update_item'         	=> __( 'Update Photo', 'terminal' ),
			'search_items'        	=> __( 'Search Photos', 'terminal' ),
			'not_found'           	=> __( 'Not found', 'terminal' ),
			'not_found_in_trash'  	=> __( 'Not found in Trash', 'terminal' ),
		);
		$photo_args = array(
			'label'               => __( 'photo', 'terminal' ),
			'description'         => __( 'Photos', 'terminal' ),
			'labels'              => $photo_labels,
			'supports'            => array( 'title', 'author', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-format-image',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( $this->photo_post_type, $photo_args );
	}

}

Photos::instance();
