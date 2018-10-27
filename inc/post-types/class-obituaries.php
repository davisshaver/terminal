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
		add_filter( 'the_title', [ $this, 'filter_feed_title' ], 10, 2 );
		add_filter( 'ampnews_filter_author_prefix', [ $this, 'filter_ampnews_author_prefix' ] );
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
			return __( 'Via', 'terminal' );
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
	 * Filter feed title.
	 *
	 * @param string $title Current title.
	 * @param int    $id Current post ID.
	 * @return string Filtered title
	 */
	public function filter_feed_title( $title, $id = null ) {
		if ( ! $id ) {
			$id = get_the_id();
		}
		if ( get_post_type( $id ) === $this->obituary_post_type ) {
			return "[OBITUARY] ${title}";
		}
		return $title;
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
	}

}

Obituaries::instance();
