<?php
/**
 * Community post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for community.
 */
class Community {

	use Singleton;

	/**
	 * Community post type.
	 *
	 * @var string $community_post_type Post type slug.
	 */
	private $community_post_type = 'community';

	/**
	 * Community post type meta key.
	 *
	 * @var string $community_post_type_community_key Post meta key slug.
	 */
	private $community_post_type_community_key = 'context';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_COMMUNITY_POST_TYPE' ) ) {
			$this->community_post_type = getenv( 'TERMINAL_COMMUNITY_POST_TYPE' );
		}
		if ( getenv( 'TERMINAL_COMMUNITY_POST_TYPE_BIO_KEY' ) ) {
			$this->community_post_type_community_key = getenv( 'TERMINAL_COMMUNITY_POST_TYPE_BIO_KEY' );
		}
		add_action( 'init', [ $this, 'register_community_post_type' ] );
		add_filter( 'pre_get_posts', array( $this, 'include_community_post_type_in_rss' ) );
		add_action( 'init', [ $this, 'register_community_fields' ] );
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
		if ( get_post_type( $id ) === $this->community_post_type ) {
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
		if ( get_post_type( $id ) === $this->community_post_type ) {
			return __( 'Contributed by', 'terminal' );
		}
		return $prefix;
	}

	/**
	 * Get book post type.
	 */
	public function get_community_post_type() {
		return $this->community_post_type;
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
		if ( is_feed() && get_post_type( $id ) === $this->community_post_type ) {
			return "[COMMUNITY] ${title}";
		}
		return $title;
	}

	/**
	 * Register community fields.
	 */
	public function register_community_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_TextField( array(
				'name'           => $this->community_post_type_community_key,
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Contributor', $this->community_post_type );
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
		return get_post_meta( $post_id, $this->community_post_type_community_key, true );
	}

	/**
	 * Include community post type.
	 *
	 * @param object $query Query.
	 * @return object Filtered query
	 */
	public function include_community_post_type_in_rss( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() && ! is_post_type_archive() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->community_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->community_post_type, 'post' ) );
			}
		}
		return $query;
	}

	/**
	 * Register community post type.
	 */
	public function register_community_post_type() {
		$community_labels = array(
			'name'               => __( 'Community Content', 'terminal' ),
			'singular_name'      => __( 'Community Content', 'terminal' ),
			'menu_name'          => __( 'Community Content', 'terminal' ),
			'all_items'          => __( 'All Community Content', 'terminal' ),
			'view_item'          => __( 'View Community Content', 'terminal' ),
			'add_new_item'       => __( 'Add New Community Content', 'terminal' ),
			'add_new'            => __( 'Add New', 'terminal' ),
			'edit_item'          => __( 'Edit Community Content', 'terminal' ),
			'update_item'        => __( 'Update Community Content', 'terminal' ),
			'search_items'       => __( 'Search Community Content', 'terminal' ),
			'not_found'          => __( 'Not found', 'terminal' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'terminal' ),
		);
		$community_args   = array(
			'label'               => __( 'community', 'terminal' ),
			'description'         => __( 'Community Content', 'terminal' ),
			'labels'              => $community_labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-groups',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( $this->community_post_type, $community_args );
	}

}

Community::instance();
