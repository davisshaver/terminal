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
		if ( ! is_admin() ) {
			remove_action( 'wp_head', 'wp_generator' );
			remove_action( 'wp_head', 'wp_shortlink_wp_head' );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
			add_filter( 'feed_links_show_comments_feed', '__return_false' );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'gform_enqueue_scripts', [ $this, 'enqueue_gravity_form_scripts' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
			add_filter( 'query_vars', [ $this, 'add_query_vars_filter' ] );
			add_filter( 'simple_local_avatar', [ $this, 'hack_for_ssl' ] );
			add_action( 'wp_head', [ $this, 'maybe_print_app_meta' ] );
			add_filter( 'dynamic_sidebar_params', [ $this, 'filter_sidebar_params' ] );
			add_filter( 'wpseo_breadcrumb_single_link', [ $this, 'adjust_single_breadcrumb' ] );
			add_filter( 'jetpack_implode_frontend_css', '__return_false' );
			add_action( 'wp_head', [ $this, 'remove_jetpack_crap' ], 130 );
			add_filter( 'sidebars_widgets', [ $this, 'maybe_disable_ads' ] );
			add_filter( 'option_coral_talk_container_classes', [ $this, 'filter_talk_classes' ] );
			add_filter( 'wpseo_breadcrumb_links', [ $this, 'wpseo_remove_home_breadcrumb' ] );
		}
		$this->disable_emojis();
		add_filter( 'web_app_manifest', [ $this, 'filter_web_app_manifest' ] );
		add_filter('wpseo_opengraph_title', [ $this, 'filter_yoast_og_title' ], 999);
	}

	/**
	 * Filter Yoast SEO OG title to remove site name.
	 *
	 * @param string $title Title
	 * @return string Filtered title
	 */
	public function filter_yoast_og_title( $title ) {
		$separator = apply_filters( 'terminal_yoast_title_filter', ' - ' );
		$site_name = $separator . get_bloginfo( 'name' );
		return str_replace( $site_name, '', $title ) ;
	}

	public function filter_web_app_manifest( $manifest ) {
		// Nothing to see here yet.
		return $manifest;
	}

	public function wpseo_remove_home_breadcrumb( $links ) {
		if ( $links[0]['url'] == home_url('/') ) {
			array_shift($links);
		}
		return $links;
	}

	public function maybe_disable_ads( $widgets ) {
		if ( is_customize_preview() || is_admin() ) {
			return $widgets;
		}
		$use_widgets = $widgets;
		array_shift( $widgets );
		$data = Data::instance();
		$disable = $data->user_has_no_ad_id();
		if ( $disable ) {
			$disable_ads = false;
			foreach( $widgets as $sidebar_name => $sidebar_widgets ) {
				foreach( $sidebar_widgets as $key => $sidebar_widget ) {
					if ( false !== strpos( $sidebar_widget, 'ad_layers' ) ) {
						unset( $use_widgets[ $sidebar_name ][ $key ] );
					}
				}
			}
		}
		return $use_widgets;
	}

	/**
	 * Filter sidebar params.
	 * @param array $params {
	 *     @type array $args  {
	 *         An array of widget display arguments.
	 *
	 *         @type string $name          Name of the sidebar the widget is assigned to.
	 *         @type string $id            ID of the sidebar the widget is assigned to.
	 *         @type string $description   The sidebar description.
	 *         @type string $class         CSS class applied to the sidebar container.
	 *         @type string $before_widget HTML markup to prepend to each widget in the sidebar.
	 *         @type string $after_widget  HTML markup to append to each widget in the sidebar.
	 *         @type string $before_title  HTML markup to prepend to the widget title when displayed.
	 *         @type string $after_title   HTML markup to append to the widget title when displayed.
	 *         @type string $widget_id     ID of the widget.
	 *         @type string $widget_name   Name of the widget.
	 *     }
	 *     @type array $widget_args {
	 *         An array of multi-widget arguments.
	 *
	 *         @type int $number Number increment used for multiples of the same widget.
	 *     }
	 * }
	 * @return aray filtered params
	 */
	public function filter_sidebar_params( $params ) {
		if ( ! empty( $params[0] ) && ! empty( $params[0]['id'] ) && in_array( $params[0]['id'], array(
			'terminal-header',
			'terminal-featured',
			'terminal-primary-sidebar',
			'terminal-stream-start',
			'terminal-stream-end',
			'terminal-footer'
		) ) ) {
			$new_params = $params;
			if ( 0 !== strpos( $params[0]['before_widget'], 'widget_ad_layers_ad_widget' ) ){
				$new_params[0]['before_widget'] = str_replace( 'widget_ad_layers_ad_widget', 'widget_ad_layers_ad_widget covered-target', $params[0]['before_widget'] );
			}
			return $new_params;
		}
		return $params;
	}

	/**
	 * Filter talk classes.
	 *
	 * @param $classes string existing classes.
	 * @return string Filtered classes.
	 */
	public function filter_talk_classes( $classes ) {
		return $classes . ' terminal-comments terminal-limit-max-content-width';
	}

	/**
	 * Remove Jetpack crap.
	 */
	public function remove_jetpack_crap() {
		wp_dequeue_style( 'wpcom-notes-admin-bar' );
		wp_deregister_script( 'wpcom-notes-common' );
		wp_deregister_script( 'wpcom-notes-admin-bar' );
		wp_deregister_style( 'tiled-gallery' );
		wp_deregister_style( 'noticons' );
	}
	/**
	 * Don't let Yoast duplicate title.
	 *
	 * @param string $link_output Current link output.
	 * @return string filtered output
	 */
	public function adjust_single_breadcrumb( $link_output ) {
		if ( strpos( $link_output, 'breadcrumb_last' ) !== false ) {
			$link_output = '';
		}
		return $link_output;
	}

	/**
	 * Maybe print apps meta tags.
	 */
	public function maybe_print_app_meta() {
		$data = Data::instance();
		$icons = array (
			'smart_banner_icon_apple',
			'smart_banner_icon_google'
		);
		$apps = $data->get_apps_data();
		if (
			is_user_logged_in() ||
			(
				empty( $apps['smart_banner_enable_ios'] ) &&
				empty( $apps['smart_banner_enable_google'] )
			)
		) {
			echo '<meta name="smartbanner:enabled-platforms" content="none">';
			return;
		}
		if (
			! empty( $apps['smart_banner_enable_ios'] )
		) {
			$platforms[] = 'ios';
		}
		if (
			! empty( $apps['smart_banner_enable_google'] )
		) {
			$platforms[] = 'android';
		}
		$enabled = sprintf(
			'<meta name="smartbanner:enabled-platforms" content="%s">',
			esc_attr( implode( ', ', $platforms ) )
		);
		echo $enabled . PHP_EOL;
		$targets = array(
			array(
				'key' => 'smart_banner_title',
				'map' => 'smartbanner:title'
			),
			array(
				'key' => 'smart_banner_author',
				'map' => 'smartbanner:author'
			),
			array(
				'key' => 'smart_banner_price',
				'map' => 'smartbanner:price'
			),
			array(
				'key' => 'smart_banner_suffix_apple',
				'map' => 'smartbanner:price-suffix-apple'
			),
			array(
				'key' => 'smart_banner_suffix_google',
				'map' => 'smartbanner:price-suffix-google'
			),
			array(
				'key' => 'smart_banner_icon_apple',
				'map' => 'smartbanner:icon-apple'
			),
			array(
				'key' => 'smart_banner_icon_google',
				'map' => 'smartbanner:icon-google'
			),
			array(
				'key' => 'smart_banner_button_text',
				'map' => 'smartbanner:button'
			),
			array(
				'key' => 'smart_banner_button_link_apple',
				'map' => 'smartbanner:button-url-apple'
			),
			array(
				'key' => 'smart_banner_button_link_google',
				'map' => 'smartbanner:button-url-google'
			),
		);
		foreach( $targets as $value ) {
			$item_value = false;
			if ( ! empty( $apps[ $value['key'] ] ) ) {
				$key = $value['map'];
				if ( in_array( $value['key'], $icons ) ) {
					$img = wp_get_attachment_image_src( $apps[ $value[ 'key' ] ], 'terminal-uncut-thumbnail-small' );
					if ( ! empty( $img ) ) {
						$item_value = $img[0];
					} else {
						$item_value = false;
					}
				} else {
					$item_value = $apps[ $value['key'] ];
				}
			}
			if ( ! empty( $item_value ) ) {
				printf(
					'<meta name="%s" content="%s">',
					esc_attr( $key ),
					esc_attr( $item_value )
				);
				echo PHP_EOL;
			}
		}
	}

	/**
	 * Don't let http image URL be used.
	 *
	 * @param string $avatar Avatar URL.
	 * @return string Avatar URL.
	 */
	public function hack_for_ssl( $avatar ) {
		return str_replace( 'http://', 'https://', $avatar );
	}

	/**
	 * Okay 'filter' as query var.
	 *
	 * @param array $vars Query vars.
	 *
	 * @return array Filtered query vars.
	 */
	public function add_query_vars_filter( $vars ) {
			$vars[] = 'filter';
			return $vars;
	}

	/**
	 * Disable emojis for native.
	 */
	public function disable_emojis() {
		add_action( 'init', function() {
			add_filter( 'the_content', 'wp_staticize_emoji' );
		});
	}

	/**
	 * Enqueue gravity forms.
	 */
	public function enqueue_gravity_form_scripts() {
		wp_enqueue_script(
			'gforms-wordcount',
			get_template_directory_uri() . '/client/wordcount.js',
			array( 'jquery' ),
			'0.1',
			true
		);
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( TERMINAL_APP, get_template_directory_uri() . '/client/build/index.bundle.js', array(), TERMINAL_VERSION, false );
		wp_deregister_style( 'ad-layers' );
		wp_deregister_style( 'ad-layers-dfp' );
		wp_deregister_script( 'wp-mediaelement' );
		wp_deregister_script( 'mediaelement-core' );
		wp_deregister_script( 'mediaelement-migrate' );
		wp_dequeue_style( 'wp-mediaelement' );
		wp_dequeue_script( 'devicepx' );
		wp_deregister_script( 'wp-embed' );
		wp_deregister_style( 'the-neverending-homepage' );
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( TERMINAL_APP, get_template_directory_uri() . '/client/build/index.css', array(), TERMINAL_VERSION );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Frontend', 'instance' ] );
