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
	 * Release post type link key
	 *
	 * @var string $release_post_type_link_key Link slug.
	 */

	private $release_post_type_link_key = 'link';
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
		add_action( 'init', [ $this, 'register_release_fields' ] );
		add_filter( 'ampnews_filter_author_prefix', [ $this, 'filter_ampnews_author_prefix' ] );
		// add_filter( 'ampnews_filter_author_suffix', [ $this, 'filter_ampnews_author_suffix' ] );
		if ( getenv( 'TERMINAL_ENABLE_RELEASES_POST_TYPE_ON_AUTHOR' ) ) {
			add_filter( 'pre_get_posts', array( $this, 'include_release_post_type_on_author' ) );
		}
		add_filter( 'the_author', [ $this, 'filter_feed_author' ], 10, 2 );
		add_filter( 'author_link', [ $this, 'filter_feed_author_link' ], 10 );
		add_filter( 'pre_get_posts', array( $this, 'include_release_post_type_in_loop' ) );
	}

	// public function filter_ampnews_author_suffix( $default ) {
	// 	$id = get_the_id();
	// 	if ( get_post_type( $id ) === $this->releases_post_type ) {
	// 		return __( 'ℹ️', 'terminal' );
	// 	}
	// 	return $default;
	// }

	/**
	 * Include release post type.
	 *
	 * @param object $query Query.
	 * @return object Filtered query
	 */
	public function include_release_post_type_in_loop( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() && ! is_post_type_archive() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->releases_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->releases_post_type, 'post' ) );
			}
		}
		return $query;
	}

	/**
	 * Filter feed author.
	 *
	 * @param string $link Current author.
	 * @return string Filtered author
	 */
	public function filter_feed_author_link( $link ) {
		$id = get_the_id();
		if ( get_post_type( $id ) === $this->releases_post_type ) {
			$url = get_post_meta( $id, $this->release_post_type_link_key, true );
			if ( ! empty( $url ) ) {
				return $url;
			}
		}
		return $link;
	}

	/**
	 * Filter feed author.
	 *
	 * @param string $author Current author.
	 * @return string Filtered author
	 */
	public function filter_feed_author( $author ) {
		$id = get_the_id();
		if ( get_post_type( $id ) === $this->releases_post_type ) {
			$sponsor = $this->get_org( $id );
			if ( ! empty( $sponsor ) ) {
				return $sponsor;
			}
		}
		return $author;
	}

	/**
	 * Get link post type.
	 */
	public function get_org( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_id();
		}
		$sponsor = get_post_meta( $post_id, $this->release_post_type_link_key . '_org', true );
		if ( ! empty( $sponsor ) ) {
			return $sponsor;
		}
		return false;
	}

	/**
	 * Register release fields.
	 */
	public function register_release_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_Link( array(
				'name'           => $this->release_post_type_link_key,
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Org Link', $this->releases_post_type );
			$fm = new \Fieldmanager_TextField( array(
				'name'           => $this->release_post_type_link_key . '_org',
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Releasing Org', $this->releases_post_type );
		}
	}

	/**
	 * Include release post type.
	 *
	 * @param object $query Query.
	 * @return object Filtered query
	 */
	public function include_release_post_type_on_author( $query ) {
		if (
			( ! is_singular() && ! is_admin() ) &&
			$query->is_main_query() &&
			is_author()
		) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->releases_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->releases_post_type, 'post' ) );
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
		if ( get_post_type( $id ) === $this->releases_post_type ) {
			return __( 'Press release by', 'terminal' );
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
