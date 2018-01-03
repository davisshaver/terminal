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
		add_filter( 'rest_page_query', [ $this, 'terminal_add_path_to_page_query' ], 10, 2 );
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
			wp_enqueue_script( TERMINAL_APP, get_template_directory_uri() . '/client/build/single.bundle.js', array(), TERMINAL_VERSION, true );
		} else {
			wp_enqueue_script( TERMINAL_APP, get_template_directory_uri() . '/client/build/homepage.bundle.js', array(), TERMINAL_VERSION, true );
		}
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_styles() {
		if ( is_single() ) {
			wp_enqueue_style( 'homepage', get_template_directory_uri() . '/client/build/single.css', array(), TERMINAL_VERSION );
		} else {
			wp_enqueue_style( 'homepage', get_template_directory_uri() . '/client/build/homepage.css', array(), TERMINAL_VERSION );
		}
		$fonts_url = $this->terminal_fonts_url();
		if ( ! empty( $fonts_url ) ) {
			wp_enqueue_style( 'terminal-fonts', esc_url_raw( $fonts_url ), array(), null );
		}
	}

	/**
	 * Generate fonts URL.
	 */
	private function terminal_fonts_url() {
		$fonts_url = '';

		/*
			* Translators: If there are characters in your language that are not
			* supported by Alegreya, translate this to 'off'. Do not translate
			* into your own language.
			*/
		$alegreya = _x( 'on', 'Alegreya font: on or off', 'terminal' );

		/*
			* Translators: If there are characters in your language that are not
			* supported by Alegreya Sans, translate this to 'off'. Do not translate into
			* your own language.
			*/
		$alegreya_sans = _x( 'on', 'Alegreya Sans font: on or off', 'terminal' );

		/*
			* Translators: If there are characters in your language that are not
			* supported by Alegreya SC, translate this to 'off'. Do not translate into
			* your own language.
			*/
		$alegreya_sc = _x( 'on', 'Alegreya SC (smallcaps) font: on or off', 'terminal' );

		if ( 'off' !== $alegreya || 'off' !== $alegreya_sans || 'off' !== $alegreya_sc ) {
			$font_families = array();

			if ( 'off' !== $alegreya ) {
				$font_families[] = rawurlencode( 'Alegreya:400,400italic,700,700italic,900italic' );
			}

			if ( 'off' !== $alegreya_sans ) {
				$font_families[] = rawurlencode( 'Alegreya Sans:700' );
			}

			if ( 'off' !== $alegreya_sc ) {
				$font_families[] = rawurlencode( 'Alegreya SC:700' );
			}

			$protocol = is_ssl() ? 'https' : 'http';

			$query_args = array(
				'family' => implode( '|', $font_families ),
				'subset' => rawurlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
		}

		return $fonts_url;
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Frontend', 'instance' ] );
