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

		load_theme_textdomain( 'terminal', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'title-tag' );

		add_theme_support( 'post-thumbnails' );

		register_default_headers( array(
			'terminal' => array(
				'url'           => '%s/client/static/images/banner.png',
				'thumbnail_url' => '%s/client/static/images/banner-thumbnail.png',
			),
		) );
		$custom_header_args = array(
			'flex-height'    => true,
			'flex-width'     => true,
			'height'         => 54,
			'random-default' => true,
			'width'          => 455,
		);
		add_theme_support( 'custom-header', $custom_header_args );
		add_image_size( 'terminal-uncut-thumbnail-small', 50, 50, false );
		add_image_size( 'terminal-uncut-thumbnail-logo', 100, 100, false );
		add_image_size( 'terminal-uncut-thumbnail', 500, 500, false );
		add_image_size( 'terminal-uncut-thumbnail-large', 775, 500, false );
		add_image_size( 'terminal-primary-thumbnail', 702, 370, true );
		add_image_size( 'terminal-thumbnail', 500, 500, true );
		add_image_size( 'terminal-thumbnail-small', 100, 100, true );
		add_image_size( 'terminal-featured', 1404, 740, false );
		add_filter( 'image_size_names_choose', [ $this, 'filter_image_size_names_choose' ] );

		$custom_background_args = array(
			'default-color' => '#f4f4f4',
		);
		add_theme_support( 'custom-background', $custom_background_args );

		register_nav_menus( array(
			'terminal-header'      => esc_html__( 'Header Menu', 'terminal' ),
			'terminal-header-more' => esc_html__( 'Header More Menu - Content', 'terminal' ),
			'terminal-header-more-meta' => esc_html__( 'Header More Menu - Meta', 'terminal' ),
			'terminal-footer-more' => esc_html__( 'Footer More Menu', 'terminal' ),
			'terminal-footer'      => esc_html__( 'Footer Menu', 'terminal' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'gallery',
			'caption',
		) );
		add_theme_support( 'infinite-scroll', array(
			'container'      => 'terminal-container',
			'render'         => 'terminal_print_stories_loop',
			'footer'         => false,
			'wrapper'        => false,
			'posts_per_page' => 6,
		) );
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
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
		add_filter( 'unipress_push_taxonomies_post_types', [ $this, 'remove_unipress_buggy_tax' ] );
		add_filter( 'filter_gutenberg_meta_boxes', [ $this, 'remove_custom_tax_from_gutenberg' ], 999 );
		add_filter( 'essb_is_theme_integrated', '__return_true' );
	}

	/**
	 * Removes placements. Filter docs via Gutenberg...
	 *
	 * Fires right before the meta boxes are rendered.
	 *
	 * This allows for the filtering of meta box data, that should already be
	 * present by this point. Do not use as a means of adding meta box data.
	 *
	 * By default gutenberg_filter_meta_boxes() is hooked in and can be
	 * unhooked to restore core meta boxes.
	 *
	 * @param array $wp_meta_boxes Global meta box state.
	 */
	public function remove_custom_tax_from_gutenberg( $wp_meta_boxes ) {
		unset( $wp_meta_boxes['post']['side']['core']['terminal-placementdiv'] );
		unset( $wp_meta_boxes['post']['side']['core']['fm_meta_box_ad_layer'] );
		return $wp_meta_boxes;
	}

	/**
	 * Remove buggy taxo
	 */
	public function remove_unipress_buggy_tax() {
		return array();
	}

	/**
	 * Register sidebars.
	 */
	public function register_sidebars() {
		register_sidebar( array(
			'name'          => __( 'First page stream, end', 'terminal' ),
			'id'            => 'terminal-primary-sidebar',
			'description'   => __( 'Goes after the first set of posts in the stream.', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="terminal-sidebar-card terminal-card terminal-card-single terminal-sidebar-body-font %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-card-title terminal-no-select terminal-sidebar-header-font">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'First page stream, start', 'terminal' ),
			'id'            => 'terminal-stream-start',
			'description'   => __( 'Goes before the first set of posts in the stream.', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="terminal-sidebar-card terminal-card terminal-card-single terminal-sidebar-body-font %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-card-title terminal-no-select terminal-sidebar-header-font">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Header ', 'terminal' ),
			'id'            => 'terminal-header',
			'description'   => __( 'Header', 'terminal' ),
			'before_widget' => '<div id="%1$s" class=terminal-header-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-header-header">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Footer ', 'terminal' ),
			'id'            => 'terminal-footer',
			'description'   => __( 'Footer', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="terminal-footer-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-footer-header">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Before Article ', 'terminal' ),
			'id'            => 'terminal-before-article',
			'description'   => __( 'Before Article', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="terminal-before-article-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-before-article-header">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'After Article ', 'terminal' ),
			'id'            => 'terminal-after-article',
			'description'   => __( 'After Article', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="terminal-after-article-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-after-article-header">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Above Comments ', 'terminal' ),
			'id'            => 'terminal-above-comments',
			'description'   => __( 'Above Comments', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="terminal-comments-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-comments-header">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Featured ', 'terminal' ),
			'id'            => 'terminal-featured',
			'description'   => __( 'Featured', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="terminal-featured-section terminal-card terminal-card-single %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-featured-header terminal-sidebar-header-font">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Recirculation ', 'terminal' ),
			'id'            => 'terminal-recirc',
			'description'   => __( 'Recirculation', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="terminal-recirc-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="terminal-recirc-header terminal-sidebar-header-font">',
			'after_title'   => '</div>',
		) );
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
