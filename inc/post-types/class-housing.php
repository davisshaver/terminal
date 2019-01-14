<?php
/**
 * Housing post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for housing.
 */
class Housing {

	use Singleton;

	/**
	 * Housing post type.
	 *
	 * @var string $housing_post_type Post type slug.
	 */

	private $housing_post_type = 'housing';

	/**
	 * Housing post type meta key.
	 *
	 * @var string $housing_post_type_link_key Post meta key slug.
	 */
	private $housing_post_type_link_key = 'link';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_HOUSING_POST_TYPE' ) ) {
			$this->housing_post_type = getenv( 'TERMINAL_HOUSING_POST_TYPE' );
		}
		if ( getenv( 'TERMINAL_HOUSING_POST_TYPE_HOUSING_KEY' ) ) {
			$this->housing_post_type_link_key = getenv( 'TERMINAL_HOUSING_POST_TYPE_HOUSING_KEY' );
		}
		add_action( 'init', [ $this, 'register_housing_post_type' ] );
		add_filter( 'the_title', [ $this, 'filter_feed_title' ], 10, 2 );
		add_filter( 'pre_get_posts', array( $this, 'include_housing_post_type_in_rss' ) );
		add_filter( 'enter_title_here', [ $this, 'change_headline_to_housing_title' ] );
		add_action( 'init', [ $this, 'register_housing_fields' ] );
		add_filter( 'post_type_link', [ $this, 'forward_to_housing_site' ], 10, 3 );
		add_action( 'add_meta_boxes', [ $this, 'remove_yoast' ], 100 );
		add_filter( 'ampnews_filter_author_prefix', [ $this, 'filter_ampnews_author_prefix' ] );
		add_filter( 'the_author', [ $this, 'filter_feed_author' ], 10, 2 );
		add_filter( 'author_link', [ $this, 'filter_feed_author_link' ], 10 );
	}

	/**
	 * Filter feed author.
	 *
	 * @param string $link Current author.
	 * @return string Filtered author
	 */
	public function filter_feed_author_link( $link ) {
		$id = get_the_id();
		if ( get_post_type( $id ) === $this->housing_post_type ) {
			$url = get_post_meta( $id, $this->housing_post_type_link_key, true );
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
		if ( get_post_type( $id ) === $this->housing_post_type ) {
			$realtor = get_post_meta( $id, $this->housing_post_type_link_key . '_realtor', true );
			if ( ! empty( $realtor ) ) {
				return $realtor;
			}
		}
		return $author;
	}

	/**
	 * Filter feed author.
	 *
	 * @param string $author Current author.
	 * @return string Filtered author
	 */
	public function filter_feed_author( $author ) {
		$id = get_the_id();
		if ( get_post_type( $id ) === $this->housing_post_type ) {
			$realtor = get_realtor( $id );
			if ( ! empty( $realtor ) ) {
				return $realtor;
			}
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
		if ( get_post_type( $id ) === $this->housing_post_type ) {
			return __( 'Sponsored by', 'terminal' );
		}
		return $prefix;
	}

	/**
	 * Remove the Yoast metabox.
	 */
	public function remove_yoast() {
		remove_meta_box( 'wpseo_meta', $this->housing_post_type, 'normal' );
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
		if ( is_feed() && get_post_type( $id ) === $this->housing_post_type ) {
			return "[HOUSING] ${title}";
		}
		return $title;
	}

	/**
	 * Get link post type.
	 *
	 * @param int $post_id Post ID for realtor lookup.
	 * @return string Realtor
	 */
	public function get_realtor( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_id();
		}
		$realtor = get_post_meta( $post_id, $this->housing_post_type_link_key . '_realtor', true );
		if ( ! empty( $realtor ) ) {
			return $realtor;
		}
		return wp_parse_url( get_the_permalink( $post_id ), PHP_URL_HOST );
	}

	/**
	 * Get link post type.
	 */
	public function get_housing_post_type() {
		return $this->housing_post_type;
	}

	/**
	 * Forward link post type to linked site.
	 *
	 * @param string  $url The post's permalink.
	 * @param WP_Post $post      The post in question.
	 * @param bool    $leavename Whether to keep the post name.
	 */
	public function forward_to_housing_site( $url, $post, $leavename = false ) {
		if ( $this->housing_post_type === $post->post_type ) {
			$url = get_post_meta( $post->ID, $this->housing_post_type_link_key, true );
		}
		return $url;
	}

	/**
	 * Register housing fields.
	 */
	public function register_housing_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_Link( array(
				'name'           => $this->housing_post_type_link_key,
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Link', $this->housing_post_type );
			$fm = new \Fieldmanager_TextField( array(
				'name'           => $this->housing_post_type_link_key . '_realtor',
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Realtor', $this->housing_post_type );
		}
	}

	/**
	 * Change headline verbiage for housing.
	 *
	 * @param string $title current title.
	 * @return string Filtered title
	 */
	public function change_headline_to_housing_title( $title ) {
		$screen = get_current_screen();
		if ( $this->housing_post_type === $screen->post_type ) {
			$title = __( 'Enter housing title here', 'terminal' );
		}
		return $title;
	}

	/**
	 * Include housing post type.
	 *
	 * @param object $query Query.
	 * @return object Filtered query
	 */
	public function include_housing_post_type_in_rss( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() && ! is_post_type_archive() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->housing_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->housing_post_type, 'post' ) );
			}
		}
		return $query;
	}


	/**
	 * Register link post type.
	 */
	public function register_housing_post_type() {
		$housing_labels = array(
			'name'               => __( 'Housing', 'terminal' ),
			'singular_name'      => __( 'Housing', 'terminal' ),
			'menu_name'          => __( 'Housing', 'terminal' ),
			'parent_item_colon'  => __( 'Parent Housing', 'terminal' ),
			'all_items'          => __( 'All Housing', 'terminal' ),
			'view_item'          => __( 'View Housing', 'terminal' ),
			'add_new_item'       => __( 'Add New Housing', 'terminal' ),
			'add_new'            => __( 'Add New', 'terminal' ),
			'edit_item'          => __( 'Edit Housing', 'terminal' ),
			'update_item'        => __( 'Update Housing', 'terminal' ),
			'search_items'       => __( 'Search Housing', 'terminal' ),
			'not_found'          => __( 'Not found', 'terminal' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'terminal' ),
		);
		$housing_args   = array(
			'label'               => __( 'housing', 'terminal' ),
			'description'         => __( 'Housing', 'terminal' ),
			'labels'              => $housing_labels,
			'supports'            => array( 'title', 'excerpt', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-multisite',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( $this->housing_post_type, $housing_args );
	}

}

Housing::instance();
