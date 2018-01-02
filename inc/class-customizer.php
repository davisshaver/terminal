<?php
/**
 * Customizer.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Customizer.
 */
class Customizer {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'disable_customize_posts_slug_control' ], 20 );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'disable_customize_post_creation' ], 20 );
		if ( is_customize_preview() ) {
			$this->enqueue_customize_scripts();
		}
	}

	/**
	 * Enqueue customize scripts.
	 */
	public function enqueue_customize_scripts() {
		$deps = array( 'customize-preview', 'customize-preview-nav-menus', 'wp-api', TERMINAL_APP );
		if ( wp_script_is( 'customize-post-field-partial', 'registered' ) ) {
			$deps[] = 'customize-post-field-partial';
		}
		if ( wp_script_is( 'customize-preview-featured-image', 'registered' ) ) {
			$deps[] = 'customize-preview-featured-image';
		}
		wp_enqueue_script( 'terminal-customize-preview', get_template_directory_uri() . '/build/customize-preview.js', $deps, TERMINAL_VERSION, true );
	}

	/**
	 * Register customizer settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customize manager.
	 */
	public function customize_register( \WP_Customize_Manager $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		add_filter( 'wp_get_nav_menu_items', [ $this, 'filter_wp_api_nav_menu_items_workaround' ], 20 );
	}

	/**
	 * Workaround issue in WP API Menus plugin to force nav menu item classes to be arrays instead of strings.
	 *
	 * @see \WP_REST_Menus::get_menu_location()
	 *
	 * @param array $items Nav menu items.
	 * @return array Items.
	 */
	public function filter_wp_api_nav_menu_items_workaround( $items ) {
		foreach ( $items as &$item ) {
			if ( is_string( $item->classes ) ) {
				$item->classes = explode( ' ', $item->classes );
			}
		}
		return $items;
	}

	/**
	 * Prevent Customize Posts plugin from forcing preview URLs for post links (e.g. ?preview=true&p=123) since frontend router doesn't understand them yet.
	 *
	 * Note that this means that posts and pages cannot be added in the Customizer at the moment with Foxhound.
	 *
	 * @todo Update React Router integration to be able to properly route URLs like /?preview=true&page_id=123.
	 *
	 * @see WP_Customize_Posts::post_link_draft()
	 * @see WP_Customize_Posts::__construct()
	 * @param WP_Customize_Manager $wp_customize Customize manager.
	 */
	public function disable_customize_post_link_draft( $wp_customize ) {
		if ( isset( $wp_customize->posts ) ) {
			remove_filter( 'post_link', array( $wp_customize->posts, 'post_link_draft' ) );
			remove_filter( 'post_type_link', array( $wp_customize->posts, 'post_link_draft' ) );
			remove_filter( 'page_link', array( $wp_customize->posts, 'post_link_draft' ) );
		}
	}

	/**
	 * Prevent Customize Posts from adding a slug field since post preview URLs aren't yet recognized.
	 *
	 * @see disable_customize_post_link_draft()
	 */
	public function disable_customize_posts_slug_control() {
		if ( wp_script_is( 'customize-post-section' ) ) {
			wp_add_inline_script( 'customize-post-section', 'wp.customize.Posts.PostSection.prototype.addSlugControl = function() {};' );
		}
		if ( ! wp_script_is( 'customize-preview-fetch-api', 'registered' ) ) {
			wp_die( 'Customizer with Terminal depends on having the <a href="https://github.com/xwp/wp-customize-preview-fetch-api">Customize Preview Fetch API</a> plugin installed.' );
		}
	}

	/**
	 * Prevent users from being able to create posts in the Customizer via Customize Posts since post preview URLs aren't yet recognized.
	 *
	 * @see disable_customize_post_link_draft()
	 */
	public function disable_customize_post_creation() {
		if ( wp_script_is( 'customize-posts' ) ) {
			wp_add_inline_script( 'customize-posts', sprintf( '_.each( _wpCustomizePostsExports.postTypes, function( postType ) { postType.current_user_can.create_posts = false; } )' ) );
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Customizer', 'instance' ] );
