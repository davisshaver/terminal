<?php
/**
 * Frontend.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Frontend.
 */
class Frontend {
	use Singleton;

	/**
	 * Setup actions.
	 */
	public function setup() {
		$this->enqueue_scripts();
		$this->enqueue_styles();
		$this->disable_emojis();

		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		add_action( 'wp_footer', [ $this, 'disable_wp_embed' ] );
	}

	/**
	 * Disable WP Embed script.
	 */
	public function disable_wp_embed() {
		wp_dequeue_script( 'wp-embed' );
	}

	/**
	 * Disable emojis for native.
	 */
	public function disable_emojis() {
		add_filter( 'emoji_svg_url', '__return_false' );
		add_action( 'init', function() {
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			add_filter( 'tiny_mce_plugins', function( $plugins ) {
				if ( is_array( $plugins ) ) {
					return array_diff( $plugins, array( 'wpemoji' ) );
				} else {
					return array();
				}
			} );
		});
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		if ( is_single() ) {
			wp_enqueue_script( TERMINAL_APP . '-single', get_template_directory_uri() . '/client/build/single.bundle.js', array(), TERMINAL_VERSION, true );
		} else {
			wp_enqueue_script( TERMINAL_APP, get_template_directory_uri() . '/client/build/index.bundle.js', array(), TERMINAL_VERSION, true );
		}
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_styles() {
		if ( is_single() ) {
			wp_enqueue_style( TERMINAL_APP . '-single', get_template_directory_uri() . '/client/build/single.css', array(), TERMINAL_VERSION );
		} else {
			wp_enqueue_style( TERMINAL_APP, get_template_directory_uri() . '/client/build/index.css', array(), TERMINAL_VERSION );
		}
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Frontend', 'instance' ] );
