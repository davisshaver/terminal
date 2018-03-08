<?php
/**
 * Links post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for links.
 */
class Links {

	use Singleton;

	private $link_post_type = 'link';
	private $link_post_type_link_key = 'link';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_LINK_POST_TYPE' ) ) {
			$this->link_post_type =  getenv( 'TERMINAL_LINK_POST_TYPE' );
		}
		if ( getenv( 'TERMINAL_LINK_POST_TYPE_LINK_KEY' ) ) {
			$this->link_post_type_link_key = getenv( 'TERMINAL_LINK_POST_TYPE_LINK_KEY' );
		}
		add_action( 'init', [ $this, 'register_link_post_type' ] );
		add_filter( 'pre_get_posts', array( $this, 'include_link_post_type_in_rss' ) );
		add_filter( 'enter_title_here', [ $this, 'change_headline_to_link_title' ] );
		add_action( 'init', [ $this, 'register_link_fields' ] );
		add_filter( 'post_type_link', [ $this, 'forward_to_linked_site' ], 10, 3 );
	}

	/**
	 * Get link post type.
	 */
	public function get_link_post_type() {
		return $this->link_post_type;
	}

	/**
	 * Forward link post type to linked site.
	 * @param string  $permalink The post's permalink.
	 * @param WP_Post $post      The post in question.
	 * @param bool    $leavename Whether to keep the post name.
	 */
	public function forward_to_linked_site( $url, $post, $leavename = false ) {
		if ( $this->link_post_type === $post->post_type ) {
			$url = get_post_meta( $post->ID, $this->link_post_type_link_key, true );
		}
		return $url;
	}

	/**
	 * Register link fields.
	 */
	public function register_link_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_Link( array(
				'name'           => $this->link_post_type_link_key,
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Link', $this->link_post_type );
		}
	}

	/**
	 * Change headline verbiage for links.
	 */
	public function change_headline_to_link_title( $title ) {
		$screen = get_current_screen();
		if ( $this->link_post_type === $screen->post_type ) {
			$title = __( 'Enter link title here', 'terminal' );
		}
		return $title;
	}

	/**
	 * Include link post type.
	 *
	 * @param object $query Query
	 * @return object Filtered query
	 */
	public function include_link_post_type_in_rss( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->link_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->link_post_type, 'post' ) );
			}
		}
		return $query;
	}


	/**
	 * Register link post type.
	 */
	public function register_link_post_type() {
		$link_labels = array(
			'name'                	=> __( 'Links', 'terminal' ),
			'singular_name'       	=> __( 'Links', 'terminal' ),
			'menu_name'           	=> __( 'Links', 'terminal' ),
			'parent_item_colon'   	=> __( 'Parent Link', 'terminal' ),
			'all_items'           	=> __( 'All Links', 'terminal' ),
			'view_item'           	=> __( 'View Link', 'terminal' ),
			'add_new_item'        	=> __( 'Add New Link', 'terminal' ),
			'add_new'             	=> __( 'Add New', 'terminal' ),
			'edit_item'           	=> __( 'Edit Link', 'terminal' ),
			'update_item'         	=> __( 'Update Link', 'terminal' ),
			'search_items'        	=> __( 'Search Link', 'terminal' ),
			'not_found'           	=> __( 'Not found', 'terminal' ),
			'not_found_in_trash'  	=> __( 'Not found in Trash', 'terminal' ),
		);
		$link_args = array(
			'label'               => __( 'link', 'terminal' ),
			'description'         => __( 'Links', 'terminal' ),
			'labels'              => $link_labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-links',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( $this->link_post_type, $link_args );
	}

}

Links::instance();
