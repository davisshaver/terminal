<?php
/**
 * Jobs post type integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for jobs.
 */
class Jobs {

	use Singleton;

	private $job_post_type = 'jobs';
	private $job_post_type_link_key = 'link';

	/**
	 * Setup.
	 */
	public function setup() {
		if ( getenv( 'TERMINAL_JOB_POST_TYPE' ) ) {
			$this->jobs_post_type = getenv( 'TERMINAL_JOB_POST_TYPE' );
		}
		if ( getenv( 'TERMINAL_JOB_POST_TYPE_LINK_KEY' ) ) {
			$this->job_post_type_link_key = getenv( 'TERMINAL_JOB_POST_TYPE_LINK_KEY' );
		}
		add_action( 'init', [ $this, 'register_job_post_type' ] );
		add_filter( 'the_title', [ $this, 'filter_feed_title' ], 10, 2 );
 		add_filter( 'pre_get_posts', array( $this, 'include_job_post_type_in_rss' ) );
		add_filter( 'enter_title_here', [ $this, 'change_headline_to_job_title' ] );
		add_action( 'init', [ $this, 'register_job_fields' ] );
		add_filter( 'post_type_link', [ $this, 'forward_to_job_site' ], 10, 3 );
		add_action('add_meta_boxes', [ $this, 'remove_yoast' ], 100);
		add_filter( 'the_author', [ $this, 'filter_feed_author' ], 10, 2 );
		add_filter( 'author_link', [ $this, 'filter_feed_author_link' ], 10 );
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
		if ( get_post_type( $id ) === $this->job_post_type ) {
			return __( 'Sponsored by', 'terminal' );
		}
		return $prefix;
	}

	/**
	 * Filter feed author.
	 *
	 * @param string $link Current author.
	 * @return string Filtered author
	 */
	public function filter_feed_author_link( $link ) {
		$id = get_the_id();
		if ( get_post_type( $id ) === $this->job_post_type ) {
			$url = get_post_meta( $id, $this->job_post_type_link_key, true );
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
		if ( get_post_type( $id ) === $this->job_post_type ) {
			$employer = $this->get_employer( $id );
			if ( ! empty( $employer ) ) {
				return $employer;
			}
		}
		return $author;
	}

	public function remove_yoast() {
		remove_meta_box('wpseo_meta', $this->job_post_type, 'normal');
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
		if ( $this->job_post_type === get_post_type( $id ) ) {
			return "[JOB] ${title}";
		}
		return $title;
	}

	/**
	 * Get link post type.
	 */
	public function get_employer( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_id();
		}
		$employer = get_post_meta( $post_id, $this->job_post_type_link_key . '_employer', true );
		if ( ! empty( $employer ) ) {
			return $employer;
		}
		return parse_url( get_the_permalink( $post_id ), PHP_URL_HOST );
	}

	/**
	 * Get link post type.
	 */
	public function get_job_post_type() {
		return $this->job_post_type;
	}

	/**
	 * Forward link post type to linked site.
	 * @param string  $permalink The post's permalink.
	 * @param WP_Post $post      The post in question.
	 * @param bool    $leavename Whether to keep the post name.
	 */
	public function forward_to_job_site( $url, $post, $leavename = false ) {
		if ( $this->job_post_type === $post->post_type ) {
			$url = get_post_meta( $post->ID, $this->job_post_type_link_key, true );
		}
		return $url;
	}

	/**
	 * Register job fields.
	 */
	public function register_job_fields() {
		if ( defined( 'FM_VERSION' ) ) {
			$fm = new \Fieldmanager_Link( array(
				'name'           => $this->job_post_type_link_key,
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Link', $this->job_post_type );
			$fm = new \Fieldmanager_TextField( array(
				'name'           => $this->job_post_type_link_key . '_employer',
				'serialize_data' => false,
			) );
			$fm->add_meta_box( 'Employer', $this->job_post_type );
		}
	}

	/**
	 * Change headline verbiage for job.
	 */
	public function change_headline_to_job_title( $title ) {
		$screen = get_current_screen();
		if ( $this->job_post_type === $screen->post_type ) {
			$title = __( 'Enter job title here', 'terminal' );
		}
		return $title;
	}

	/**
	 * Include job post type.
	 *
	 * @param object $query Query
	 * @return object Filtered query
	 */
	public function include_job_post_type_in_rss( $query ) {
		if ( ( ! is_singular() && ! is_admin() ) && $query->is_main_query() && ! is_post_type_archive() ) {
			$existing_post_types = $query->get( 'post_type' );
			if ( is_array( $existing_post_types ) && ! empty( $existing_post_types ) ) {
				$query->set( 'post_type', array_merge( $existing_post_types, array( $this->job_post_type ) ) );
			} else {
				$query->set( 'post_type', array( $this->job_post_type, 'post' ) );
			}
		}
		return $query;
	}


	/**
	 * Register link post type.
	 */
	public function register_job_post_type() {
		$jobs_labels = array(
			'name'                	=> __( 'Jobs', 'terminal' ),
			'singular_name'       	=> __( 'Job', 'terminal' ),
			'menu_name'           	=> __( 'Jobs', 'terminal' ),
			'parent_item_colon'   	=> __( 'Parent Job', 'terminal' ),
			'all_items'           	=> __( 'All Jobs', 'terminal' ),
			'view_item'           	=> __( 'View Job', 'terminal' ),
			'add_new_item'        	=> __( 'Add New Job', 'terminal' ),
			'add_new'             	=> __( 'Add New', 'terminal' ),
			'edit_item'           	=> __( 'Edit Job', 'terminal' ),
			'update_item'         	=> __( 'Update Job', 'terminal' ),
			'search_items'        	=> __( 'Search Jobs', 'terminal' ),
			'not_found'           	=> __( 'Not found', 'terminal' ),
			'not_found_in_trash'  	=> __( 'Not found in Trash', 'terminal' ),
		);
		$jobs_args = array(
			'label'               => __( 'job', 'terminal' ),
			'description'         => __( 'Jobs', 'terminal' ),
			'labels'              => $jobs_labels,
			'supports'            => array( 'title', 'excerpt', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-businessman',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( $this->job_post_type, $jobs_args );
	}

}

Jobs::instance();
