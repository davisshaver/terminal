<?php
/**
 * Theme customization.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Theme.
 */
class Theme {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_filter( 'image_size_names_choose', [ $this, 'filter_image_size_names_choose' ] );
		add_filter( 'parsely_filter_image_size', [ $this, 'filter_parsely_image_size' ] );
		add_filter( 'parsely_filter_insert_javascript', [ $this, 'filter_parsely_filter_insert_javascript' ] );
		register_taxonomy(
			'terminal-placement',
			'post',
			array(
				'label' => __( 'Placements', 'terminal' ),
				'public' => true,
				'rewrite' => false,
				'hierarchical' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_rest' => true,
				'publicly_queryable' => true,
			)
		);
		add_action( 'admin_init', [ $this, 'remove_unused_meta_box' ] );
		add_action( 'admin_menu', [ $this, 'remove_unused_admin_menu' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'admin_bar_disable_comments' ] );
		add_filter( 'pings_open', '__return_false', 20, 2 );
		add_filter( 'wpseo_canonical', [ $this, 'ensure_no_www_in_canonical' ] );
		add_filter ('wp_parsely_page_url', [ $this, 'ensure_no_www_in_canonical' ] );
		add_filter ('wpseo_json_ld_output', [ $this, 'ensure_no_www_in_canonical' ] );
		add_filter ( 'post_link', [ $this, 'post_link_www'] );
		add_filter ( 'post_link', [ $this, 'post_link_www'] );
		add_filter( 'essb_is_theme_integrated', '__return_true' );
		add_filter( 'wp_kses_allowed_html', [ $this, 'add_amp_ad' ], 10, 2 );
		
		if ( is_customize_preview() ) {
			add_filter( 'user_can_richedit', '__return_false' );
		}
		add_filter( 'wp_parsely_post_tags', [ $this, 'filter_parsely_post_tags' ], 10, 2 );
		add_filter( 'publishpress_disable_timepicker', '__return_true' );
		add_action( 'admin_init', [ $this, 'enqueue_wpapi'] );
		add_filter( 'amp-news-post-type', [ $this, 'filter_amp_news_post_type'] );
	}

	public function filter_amp_news_post_type() {
		return terminal_get_post_types();
	}

	public function enqueue_wpapi() {
		wp_enqueue_script( 'wp-api' );
	}

	/**
	 * Filter whether parsely inserts JS.
	 *
	 * @param bool $insert Whether to insert JS.
	 * @return bool Filtered value.
	 */
	public function filter_parsely_filter_insert_javascript( $insert ) {
		if ( ! defined( 'NOT_PROD' ) || ! NOT_PROD ) {
			return $insert;
		}
		return false;
	}

	/**
	 * Add amp-ad to allowed wp_kses_post tags
	 *
	 * @param string $tags Allowed tags, attributes, and/or entities.
	 * @param string $context Context to judge allowed tags by. Allowed values are 'post',
	 *
	 * @return mixed
	 */
	public function add_amp_ad( $tags, $context ) {
		if ( 'post' === $context ) {
			$tags['amp-ad'] = array(
				'width' => true,
				'height' => true,
				'type' => true,
				'data-ad-client' => true,
				'data-ad-slot' => true,
				'layout' => true,
			);
		}
		return $tags;
	}

	/**
	 * Filter WP SEO Canonical
	 *
	 * @param $link string Canonical
	 * @return string Filtered canonical
	 */
	public function post_link_www( $link ) {
		return str_replace( 'www.', '', $link );
	}

	/**
	 * Filter WP SEO Canonical
	 *
	 * @param $value string Canonical
	 * @return string Filtered canonical
	 */
	public function ensure_no_www_in_canonical( $value ) {
		$link = get_permalink();
		if ( ! $link ) {
			return $value;
		}
		$parsed_link = parse_url( $link );
		if ( ! empty( $parsed_link['host'] ) ) {
			return str_replace( $parsed_link['host'], str_replace( 'www.', '', $parsed_link['host'] ), $value );
		}
		return $value;
	}

	/**
	 * Filter parsely post tags.
	 *
	 * @param $tags array Existing tags.
	 * @param $post_id Post ID
	 * @return array Filtered tags
	 */
	public function filter_parsely_post_tags( $tags, $post_id ) {
		$filtered_tags = $tags;
		$placements = wp_get_post_terms( $post_id, 'terminal-placement');
		foreach( $placements as $placement ) {
			$filtered_tags[] = 'placement|' . $placement->name;
			$filtered_tags[] = 'placement-id|' . $placement->term_id;
		}
		return $filtered_tags;
	}

	/**
	 * Filter parsely image size
	 */
	public function filter_parsely_image_size() {
		return 'full';
	}

	/**
	 * Remove unused metaboxes.
	 */
	public function remove_unused_meta_box() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
	}

	/**
	 * Admin bar disable comments.
	 */
	public function admin_bar_disable_comments() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}

	/**
	 * Remove unused admin menus.
	 */
	public function remove_unused_admin_menu() {
		remove_menu_page( 'edit-comments.php' );
		if ( ! current_user_can( 'manage_options' ) ) {
			remove_menu_page( 'tools.php' );
		}
	}

	/**
	 * Filter image sizes shown.
	 *
	 * @param array $sizes Existing sizes.
	 * @return array Filtered sizes.
	 */
	public function filter_image_size_names_choose( $sizes ) {
		unset( $sizes['thumbnail'] );
		unset( $sizes['large'] );
		return $sizes;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Theme', 'instance' ] );
