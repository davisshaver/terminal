<?php
/**
 * Releases post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Releases.
 */
class Releases {

	use Singleton;

	/**
	 * Releases post type.
	 *
	 * @var string $releases_post_type Post type slug.
	 */
	private $releases_post_type = 'release';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_RELEASES_POST_TYPE' ) ) {
			$this->releases_post_type = getenv( 'TERMINAL_RELEASES_POST_TYPE' );
		}
		if ( getenv( 'TERMINAL_RELEASES_POST_TYPE_ORG_KEY' ) ) {
			$this->releases_post_type_org = getenv( 'TERMINAL_RELEASES_POST_TYPE_ORG_KEY' );
		}
		add_action( 'init', [ $this, 'register_releases_post_type' ] );
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
		if ( get_post_type( $id ) === $this->releases_post_type ) {
			return __( 'Via', 'terminal' );
		}
		return $prefix;
	}

	/**
	 * Get book post type.
	 */
	public function get_releases_post_type() {
		return $this->releases_post_type;
	}

	/**
	 * Register releases post type.
	 */
	public function register_releases_post_type() {
		$release_labels = array(
			'name'               => __( 'Releases', 'terminal' ),
			'singular_name'      => __( 'Releases', 'terminal' ),
			'menu_name'          => __( 'Releases', 'terminal' ),
			'all_items'          => __( 'All Releases', 'terminal' ),
			'view_item'          => __( 'View Release', 'terminal' ),
			'add_new_item'       => __( 'Add New Release', 'terminal' ),
			'add_new'            => __( 'Add New', 'terminal' ),
			'edit_item'          => __( 'Edit Release', 'terminal' ),
			'update_item'        => __( 'Update Release', 'terminal' ),
			'search_items'       => __( 'Search Releases', 'terminal' ),
			'not_found'          => __( 'Not found', 'terminal' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'terminal' ),
		);
		$release_args   = array(
			'label'               => __( 'release', 'terminal' ),
			'description'         => __( 'Releases', 'terminal' ),
			'labels'              => $release_labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-megaphone',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( $this->releases_post_type, $release_args );
	}

}

Releases::instance();
