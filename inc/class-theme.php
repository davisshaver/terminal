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

		add_image_size( 'terminal-primary-thumbnail', 1600, 850, false );
		add_image_size( 'terminal-widget-thumbnail', 0, 50, false );
		add_image_size( 'terminal-widget-featured', 1400, 1000, true );

		$custom_background_args = array(
			'default-color' => '#f4f4f4',
		);
		add_theme_support( 'custom-background', $custom_background_args );

		register_nav_menus( array(
			'header'      => esc_html__( 'Header Menu', 'terminal' ),
			'header-more' => esc_html__( 'Header More Menu - Content', 'terminal' ),
			'header-more-meta' => esc_html__( 'Header More Menu - Meta', 'terminal' ),
			'footer-more' => esc_html__( 'Footer More Menu', 'terminal' ),
			'footer'      => esc_html__( 'Footer Menu', 'terminal' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'gallery',
			'caption',
		) );
		add_theme_support( 'infinite-scroll', array(
			'container'      => 'stories',
			'render'         => 'terminal_print_stories_loop',
			'footer'         => false,
			'footer_widgets' => 'sidebar',
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
			'name'          => __( 'Primary Sidebar', 'terminal' ),
			'id'            => 'primary-sidebar',
			'description'   => __( 'Homepage sidebar.', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="sidebar-section terminal-sidebar-body-font %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="sidebar-header terminal-sidebar-header-font">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Header ', 'terminal' ),
			'id'            => 'header',
			'description'   => __( 'Header', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="header-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="header-header">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Footer ', 'terminal' ),
			'id'            => 'footer',
			'description'   => __( 'Footer', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="footer-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="footer-header">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Featured ', 'terminal' ),
			'id'            => 'featured',
			'description'   => __( 'Featured', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="featured-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="featured-header terminal-sidebar-header-font">',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Recirculation ', 'terminal' ),
			'id'            => 'recirc',
			'description'   => __( 'Recirculation', 'terminal' ),
			'before_widget' => '<div id="%1$s" class="featured-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="featured-header terminal-sidebar-header-font">',
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
}

add_action( 'after_setup_theme', [ '\Terminal\Theme', 'instance' ] );
