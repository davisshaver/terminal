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
	 * Obituary post type meta key.
	 *
	 * @var string $obituary_post_type_funeral_home Post meta key slug.
	 */
	private $obituary_post_type_funeral_home = 'funeral_home';

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
		add_filter( 'pre_get_posts', array( $this, 'include_obituary_post_type_in_rss' ) );
		add_action( 'init', [ $this, 'register_obituary_fields' ] );
		add_filter( 'the_title', [ $this, 'filter_feed_title' ], 10, 2 );
		add_filter( 'ampnews_filter_author_prefix', [ $this, 'filter_ampnews_author_prefix' ] );
		add_filter( 'the_author', [ $this, 'filter_feed_author' ], 10, 2 );
	}

	/**
	 * Filter feed author.
	 *
	 * @param string $author Current author.
	 * @return string Filtered author
	 */
	public function filter_feed_author( $author ) {
		$id = get_the_id();
		if ( get_post_type( $id ) === $this->obituary_post_type ) {
			return $this->get_author();
		}
		return $author;
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
			return __( 'Contributed by', 'terminal' );
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
		if ( is_feed() && get_post_type( $id ) === $this->obituary_post_type ) {
			return "[OBITUARY] ${title}";
		}
		return $title;
	}

	/**
	 * Register obituary fields.
	 */
	public function register_obituary_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_TextField( array(
				'name'           => $this->obituary_post_type_funeral_home,
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Funeral Home', $this->obituary_post_type );
		}
	}

	/**
	 * Get post author.
	 *
	 * @param int $post_id Post to get author for.
	 * @return string Author
	 */
	public function get_author( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_id();
		}
		return get_post_meta( $post_id, $this->obituary_post_type_funeral_home, true );
	}

	/**
	 * Include community post type.
	 *
	 * @param object $query Query.
	 * @return object Filtered query
	 */
	public function include_obituary_post_type_in_rss( $query ) {
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
