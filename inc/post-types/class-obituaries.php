<?php
/**
 * Obituaries post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for obituaries.
 */
class Obituaries {

	use Singleton;

	/**
	 * Obituaries post type.
	 *
	 * @var string $obituary_post_type Post type slug.
	 */
	private $obituary_post_type = 'obituary';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_OBITUARY_POST_TYPE' ) ) {
			$this->obituary_post_type = getenv( 'TERMINAL_OBITUARY_POST_TYPE' );
		}
		if ( getenv( 'TERMINAL_OBITUARY_POST_TYPE_FUNERAL_HOME_KEY' ) ) {
			$this->obituary_post_type_funeral_home = getenv( 'TERMINAL_OBITUARY_POST_TYPE_FUNERAL_HOME_KEY' );
		}
		add_action( 'init', [ $this, 'register_obituary_post_type' ] );
		add_filter( 'ampnews_filter_author_prefix', [ $this, 'filter_ampnews_author_prefix' ] );
		if ( getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE_ON_AUTHOR' ) ) {
			add_filter( 'pre_get_posts', array( $this, 'include_obituary_post_type_on_author' ) );
		}
		add_filter( 'pre_get_posts', array( $this, 'include_obituary_post_type_in_loop' ) );
		add_action( 'init', [ $this, 'register_exclude_from_loop_fields' ] );
	}

	/**
	 * Register exclude fields.
	 */
	public function register_exclude_from_loop_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_Checkbox( array(
				'name'           => 'exclude_from_loop',
				'serialize_data' => false,
				'default_value'  => true,
			) );
			$fm->add_meta_box( 'Exclude obituary from main index', $this->obituary_post_type );
		}
	}

	/**
	 * Include obituary post type.
	 *
	 * @param object $query Query.
	 * @return object Filtered query
	 */
	public function include_obituary_post_type_in_loop( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() && ! is_post_type_archive() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->obituary_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->obituary_post_type, 'post' ) );
			}
		}
		return $query;
	}

	/**
	 * Include obituary post type.
	 *
	 * @param object $query Query.
	 * @return object Filtered query
	 */
	public function include_obituary_post_type_on_author( $query ) {
		if (
			( ! is_singular() && ! is_admin() ) &&
			$query->is_main_query() &&
			is_author()
		) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->obituary_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->obituary_post_type, 'post' ) );
			}
		}
		return $query;
	}

	/**
	 * Filter AMP News theme author prefix.
	 *
	 * @param string $prefix Current prefix.
	 * @return string Filtered prefix
	 */
	public function filter_ampnews_author_prefix( $prefix ) {
		$id = get_the_id();
		if ( get_post_type( $id ) === $this->obituary_post_type ) {
			return __( 'Obituary via', 'terminal' );
		}
		return $prefix;
	}

	/**
	 * Get book post type.
	 */
	public function get_obituary_post_type() {
		return $this->obituary_post_type;
	}

	/**
	 * Register obituary post type.
	 */
	public function register_obituary_post_type() {
		$obituary_labels = array(
			'name'               => __( 'Obituaries', 'terminal' ),
			'singular_name'      => __( 'Obituaries', 'terminal' ),
			'menu_name'          => __( 'Obituaries', 'terminal' ),
			'all_items'          => __( 'All Obituaries', 'terminal' ),
			'view_item'          => __( 'View Obituary', 'terminal' ),
			'add_new_item'       => __( 'Add New Obituary', 'terminal' ),
			'add_new'            => __( 'Add New', 'terminal' ),
			'edit_item'          => __( 'Edit Obituary', 'terminal' ),
			'update_item'        => __( 'Update Obituary', 'terminal' ),
			'search_items'       => __( 'Search Obituaries', 'terminal' ),
			'not_found'          => __( 'Not found', 'terminal' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'terminal' ),
		);
		$obituary_args   = array(
			'label'               => __( 'obituary', 'terminal' ),
			'description'         => __( 'Obituaries', 'terminal' ),
			'labels'              => $obituary_labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-users',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( $this->obituary_post_type, $obituary_args );
		add_post_type_support( $this->obituary_post_type, 'newspack_blocks' );
	}

}

Obituaries::instance();
