<?php
/**
 * Deals post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for deals.
 */
class Deals {

	use Singleton;

	private $deal_post_type = 'deals';
	private $deal_post_type_link_key = 'link';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_DEAL_POST_TYPE' ) ) {
			$this->deal_post_type = getenv( 'TERMINAL_DEAL_POST_TYPE' );
		}
		if ( getenv( 'TERMINAL_DEAL_POST_TYPE_LINK_KEY' ) ) {
			$this->deal_post_type_link_key = getenv( 'TERMINAL_DEAL_POST_TYPE_LINK_KEY' );
		}
		add_action( 'init', [ $this, 'register_deal_post_type' ] );
		add_filter( 'the_title', [ $this, 'filter_feed_title' ], 10, 2 );
 		add_filter( 'pre_get_posts', array( $this, 'include_deal_post_type_in_rss' ) );
		add_filter( 'enter_title_here', [ $this, 'change_headline_to_deal_title' ] );
		add_action( 'init', [ $this, 'register_deal_fields' ] );
		add_filter( 'post_type_link', [ $this, 'forward_to_deal_site' ], 10, 3 );
		add_action('add_meta_boxes', [ $this, 'remove_yoast' ], 100);
	}

	public function remove_yoast() {
		remove_meta_box('wpseo_meta', $this->deal_post_type, 'normal');
	}

	/**
	 * Filter feed title.
	 *
	 * @param string $title Current title
	 * @param int    $id Current post ID.
	 * @return string Filtered title
	 */
	public function filter_feed_title( $title, $id = null ) {
		if ( ! $id ) {
			$id = get_the_id();
		}
		if ( is_feed() && $this->deal_post_type === get_post_type( $id ) ) {
			return "[DEAL] ${title}";
		}
		return $title;
	}

	/**
	 * Get link post type.
	 */
	public function get_sponsor( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_id();
		}
		$sponsor = get_post_meta( $post_id, $this->deal_post_type_link_key . '_sponsor', true );
		if ( ! empty( $sponsor ) ) {
			return $sponsor;
		}
		return parse_url( get_the_permalink( $post_id ), PHP_URL_HOST );
	}

	/**
	 * Get link post type.
	 */
	public function get_deal_post_type() {
		return $this->deal_post_type;
	}

	/**
	 * Forward link post type to linked site.
	 * @param string  $permalink The post's permalink.
	 * @param WP_Post $post      The post in question.
	 * @param bool    $leavename Whether to keep the post name.
	 */
	public function forward_to_deal_site( $url, $post, $leavename = false ) {
		if ( $this->deal_post_type === $post->post_type ) {
			$url = get_post_meta( $post->ID, $this->deal_post_type_link_key, true );
		}
		return $url;
	}

	/**
	 * Register deal fields.
	 */
	public function register_deal_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_Link( array(
				'name'           => $this->deal_post_type_link_key,
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Link', $this->deal_post_type );
			$fm = new \Fieldmanager_TextField( array(
				'name'           => $this->deal_post_type_link_key . '_sponsor',
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Sponsor', $this->deal_post_type );
		}
	}

	/**
	 * Change headline verbiage for deal.
	 */
	public function change_headline_to_deal_title( $title ) {
		$screen = get_current_screen();
		if ( $this->deal === $screen->post_type ) {
			$title = __( 'Enter deal title here', 'terminal' );
		}
		return $title;
	}

	/**
	 * Include deal post type.
	 *
	 * @param object $query Query
	 * @return object Filtered query
	 */
	public function include_deal_post_type_in_rss( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() && ! is_post_type_archive() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->deal_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->deal_post_type, 'post' ) );
			}
		}
		return $query;
	}


	/**
	 * Register link post type.
	 */
	public function register_deal_post_type() {
		$deal_labels = array(
			'name'                	=> __( 'Deals', 'terminal' ),
			'singular_name'       	=> __( 'Deal', 'terminal' ),
			'menu_name'           	=> __( 'Deals', 'terminal' ),
			'parent_item_colon'   	=> __( 'Parent Deal', 'terminal' ),
			'all_items'           	=> __( 'All Deals', 'terminal' ),
			'view_item'           	=> __( 'View Deal', 'terminal' ),
			'add_new_item'        	=> __( 'Add New Deal', 'terminal' ),
			'add_new'             	=> __( 'Add New', 'terminal' ),
			'edit_item'           	=> __( 'Edit Deal', 'terminal' ),
			'update_item'         	=> __( 'Update Deal', 'terminal' ),
			'search_items'        	=> __( 'Search Deals', 'terminal' ),
			'not_found'           	=> __( 'Not found', 'terminal' ),
			'not_found_in_trash'  	=> __( 'Not found in Trash', 'terminal' ),
		);
		$deal_args = array(
			'label'               => __( 'deal', 'terminal' ),
			'description'         => __( 'Deal', 'terminal' ),
			'labels'              => $deal_labels,
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
		register_post_type( $this->deal_post_type, $deal_args );
	}

}

Deals::instance();
