<?php
/**
 * Books post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for community.
 */
class Books {

	use Singleton;

	private $book_post_type = 'books';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_BOOK_POST_TYPE' ) ) {
			$this->book_post_type =  getenv( 'TERMINAL_BOOK_POST_TYPE' );
		}
		add_action( 'init', [ $this, 'register_book_post_type' ] );
		add_filter( 'pre_get_posts', array( $this, 'include_book_post_type_in_rss' ) );
		add_filter( 'the_title', [ $this, 'filter_feed_title' ] );
	}

	/**
	 * Get book post type.
	 */
	public function get_book_post_type() {
		return $this->book_post_type;
	}

	/**
	 * Filter feed title.
	 *
	 * @param string $title Current title
	 * @return string Filtered title
	 */
	public function filter_feed_title( $title ) {
		if ( is_feed() ) {
			return "[BOOK] ${title}";
		}
		return $title;
	}

	/**
	 * Include community post type.
	 *
	 * @param object $query Query
	 * @return object Filtered query
	 */
	public function include_book_post_type_in_rss( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() && ! is_post_type_archive() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->book_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->book_post_type, 'post' ) );
			}
		}
		return $query;
	}


	/**
	 * Register community post type.
	 */
	public function register_book_post_type() {
		$book_labels = array(
			'name'                	=> __( 'Books', 'terminal' ),
			'singular_name'       	=> __( 'Books', 'terminal' ),
			'menu_name'           	=> __( 'Books', 'terminal' ),
			'all_items'           	=> __( 'All Books', 'terminal' ),
			'view_item'           	=> __( 'View Book', 'terminal' ),
			'add_new_item'        	=> __( 'Add New Book', 'terminal' ),
			'add_new'             	=> __( 'Add New', 'terminal' ),
			'edit_item'           	=> __( 'Edit Book', 'terminal' ),
			'update_item'         	=> __( 'Update Books', 'terminal' ),
			'search_items'        	=> __( 'Search Books', 'terminal' ),
			'not_found'           	=> __( 'Not found', 'terminal' ),
			'not_found_in_trash'  	=> __( 'Not found in Trash', 'terminal' ),
		);
		$book_args = array(
			'label'               => __( 'photo', 'terminal' ),
			'description'         => __( 'Books', 'terminal' ),
			'labels'              => $book_labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-book',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( $this->book_post_type, $book_args );
	}

}

Books::instance();
