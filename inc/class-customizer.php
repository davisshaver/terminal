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
		add_action( 'customize_preview_init', [ $this, 'enqueue_customize_scripts' ] );
		add_filter( 'site_icon_meta_tags', [ $this, 'site_icon_meta_tags' ] );
		add_action( 'wp_head', [ $this, 'customizer_custom_css' ] );

		// Helllllo Fieldmanager!
		// require_once __DIR__ . '/widgets/class-broadstreet-widget.php' // @todo add this back.
		if ( defined( 'FM_BETA_CUSTOMIZE_VERSION' ) ) {
			require_once __DIR__ . '/customizer/class-fm-ads.php';
			require_once __DIR__ . '/customizer/class-fm-bylines.php';
			require_once __DIR__ . '/customizer/class-fm-layout.php';
			require_once __DIR__ . '/customizer/class-fm-sidebar.php';
			require_once __DIR__ . '/customizer/class-fm-fonts.php';
			require_once __DIR__ . '/customizer/class-fm-header.php';
			require_once __DIR__ . '/customizer/class-fm-footer.php';
		}
	}

	/**
	 * Prints CSS from customizer.
	 */
	public function customizer_custom_css() {
		if ( ! function_exists( 'terminal_get_fm_theme_mod' ) ) {
			return;
		}
		/**
		 * Helper font size.
		 *
		 * @param string $key String.
		 * @return string value.
		 */
		function terminal_customizer_font_size( $key ) {
			return ( 'default' !== terminal_get_fm_theme_mod( 'typography', "${key}_size", 'default' ) ) ?
			terminal_get_fm_theme_mod( 'typography', "${key}_size", 'default' ) : false;
		}

		/**
		 * Helper font stylesheet.
		 *
		 * @param string $key String.
		 * @return string font/opt stylesheet.
		 */
		function terminal_customizer_font_stylesheet( $key ) {
			$font = terminal_get_fm_theme_mod( 'typography', "${key}_font", 'none' );
			if ( empty( $font['stylesheet'] || 'default' !== $font['stylesheet'] ) ) {
				return false;
			}
			return $font['stylesheet'];
		}

		/**
		 * Helper font family.
		 *
		 * @param string $key String.
		 * @return string font/opt stylesheet.
		 */
		function terminal_customizer_font_family( $key ) {
			$font = terminal_get_fm_theme_mod( 'typography', "${key}_font", 'none' );
			if ( empty( $font['family'] || 'default' !== $font['family'] ) ) {
				return 'none';
			}
			return $font['family'];
		}

		/**
		 * Helper text_transform.
		 *
		 * @param string $key String.
		 * @return string value.
		 */
		function terminal_customizer_text_transform( $key ) {
			return ( 'default' !== terminal_get_fm_theme_mod( 'typography', "${key}_transform", 'default' ) ) ?
			terminal_get_fm_theme_mod( 'typography', "${key}_transform", 'inherit' ) : false;
		}

		/**
		 * Helper font_style.
		 *
		 * @param string $key String.
		 * @return string value.
		 */
		function terminal_customizer_font_style( $key ) {
			return ( 'default' !== terminal_get_fm_theme_mod( 'typography', "${key}_style", 'default' ) ) ?
			terminal_get_fm_theme_mod( 'typography', "${key}_style", 'inherit' ) : false;
		}

		/**
		 * Helper color.
		 *
		 * @param string $key String.
		 * @return string value.
		 */
		function terminal_customizer_color( $key ) {
			return ( 'default' !== terminal_get_fm_theme_mod( 'typography', "${key}_color", 'default' ) ) ?
			terminal_get_fm_theme_mod( 'typography', "${key}_color", 'inherit' ) : false;
		}

		/**
		 * Helper weight.
		 *
		 * @param string $key String.
		 * @return string value.
		 */
		function terminal_customizer_weight( $key ) {
			return ( 'default' !== terminal_get_fm_theme_mod( 'typography', "${key}_weight", 'default' ) ) ?
			terminal_get_fm_theme_mod( 'typography', "${key}_weight", 'inherit' ) : false;
		}
		$google_stylesheets = array();
		$font_data = array(
			'targets' => array(
				'.terminal-share-button-font' => 'share',
				'.terminal-utility-font' => 'utility',
				'.terminal-headline-font, .terminal-headline-font a' => 'headline',
				'.terminal-sidebar-header-font' => 'sidebar_header',
				'.terminal-sidebar-body-font' => 'sidebar_body',
				'.terminal-index-meta-font' => 'index_meta',
				'.terminal-single-meta-font' => 'single_meta',
				'.terminal-body-font' => 'body',
				'.terminal-cta-tagline-font' => 'tagline',
				'.terminal-cta-button-font' => 'cta_button',
				'.terminal-loop-header-font' => 'loop_header',
			),
		);
		echo '<style type="text/css">';
		foreach ( $font_data['targets'] as $key => $value ) {
			$styles = array(
				'font-size' => terminal_customizer_font_size( $value ),
				'text-transform' => terminal_customizer_text_transform( $value ),
				'font-style' => terminal_customizer_font_style( $value ),
				'font-weight' => terminal_customizer_weight( $value ),
				'color' => terminal_customizer_color( $value ),
				'fill-color' => terminal_customizer_color( $value ),
			);
			printf(
				'%s { ',
				esc_js( $key )
			);
			foreach ( $styles as $style_key => $style_value ) {
				if ( ! empty( $style_value ) ) {
					printf(
						'%s: %s; ',
						esc_js( $style_key ),
						esc_js( $style_value )
					);
				}
			}
			$font_family     = terminal_customizer_font_family( $value );
			$font_stylesheet = terminal_customizer_font_stylesheet( $value );
			if ( ! empty( $font_stylesheet ) ) {
				$google_stylesheets[] = $font_stylesheet;
			}
			if ( ! empty( $font_family['family'] ) ) {
				printf(
					'font-family: %s; ',
					$font_family['family']
				);
			}
			echo ' } ';
		}
		?>

			a {
				color: <?php echo esc_attr( get_theme_mod( 'link_default_color_setting', '#333' ) ); ?>;
			}

			svg {
				fill: <?php echo esc_attr( get_theme_mod( 'link_default_color_setting', '#333' ) ); ?>;
			}

			#nav-bar, #nav-bar-inside-more {
				background-color: <?php echo esc_attr( get_theme_mod( 'nav_background_color_setting', 'inherit' ) ); ?> !important;
			}

			#header {
				background-color: <?php echo esc_attr( get_theme_mod( 'header_background_color_setting', '#9DC1FD' ) ); ?>;
			}

			#footer {
				background-color: <?php echo esc_attr( get_theme_mod( 'footer_background_color_setting', '#9DC1FD' ) ); ?>;
				<?php if ( ! empty( get_theme_mod( 'footer_accent_color_setting', '#9DC1FD' ) ) ) : ?>
					border-bottom: 2px solid <?php echo esc_attr( get_theme_mod( 'footer_accent_color_setting', 'inherit' ) ); ?>
				<?php endif; ?>
			}

			.post, .page {
				<?php
				$post_page_background = get_theme_mod( 'post_page_background_color_setting', false );
				if ( ! empty( $post_page_background ) ) {
					echo 'box-shadow: 0 1px 1px hsla( 0, 3%, 67%, 0.1); border: 1px solid rgba(0,0,0,.1);';
					printf( 'background-color: %s;', esc_attr( $post_page_background ) );
				}
				?>
			}

			.loop-header {
				<?php
				$loop_header_background_color = get_theme_mod( 'loop_header_background_color_setting', false );
				if ( ! empty( $loop_header_background_color ) ) {
					echo 'box-shadow: 0 1px 1px hsla( 0, 3%, 67%, 0.1);';
					printf( 'background-color: %s;', esc_attr( $loop_header_background_color ) );
				}
				?>
			}

			#footer-leaderboard {
				background-color: <?php echo esc_attr( get_theme_mod( 'footer_ad_background_color_setting', 'inherit' ) ); ?>;
			}

			.featured-section {
				<?php
				$featured_section = get_theme_mod( 'featured_section_background_color_setting', false );
				if ( ! empty( $featured_section ) ) {
					echo 'box-shadow: 0 1px 1px hsla( 0, 3%, 67%, 0.1); border: 1px solid rgba(0, 0, 0, 0.1); ';
					printf( 'background-color: %s;', esc_attr( $featured_section ) );
				}
				?>
			}

			.sidebar-section {
				<?php
				$sidebar_section = get_theme_mod( 'sidebar_section_background_color_setting', false );
				if ( ! empty( $sidebar_section ) ) {
					echo 'box-shadow: 0 1px 1px hsla( 0, 3%, 67%, 0.1); border: 1px solid rgba(0, 0, 0, 0.1); ';
					printf( 'background-color: %s;', esc_attr( $sidebar_section ) );
				}
				?>
			}

			.topbar {
				background-color: <?php echo esc_attr( get_theme_mod( 'byline_background_color_setting', 'inherit' ) ); ?>;
			}

			#header-leaderboard {
				background-color: <?php echo esc_attr( get_theme_mod( 'header_ad_background_color_setting', 'inherit' ) ); ?>;
			}
		</style>
		<?php
		if ( ! empty( $google_stylesheets ) ) {
			foreach ( $google_stylesheets as $google_stylesheet ) {
				printf(
					'<link href="%s" rel="stylesheet">',
					esc_url( $google_stylesheet )
				);
			}
		}
	}

	/**
	 * Don't print site icon meta tags in the admin.
	 *
	 * @param array $tags Tags.
	 *
	 * @return array Tags
	 */
	public function site_icon_meta_tags( $tags ) {
		if ( is_admin() ) {
			return array();
		}
		return $tags;
	}

	/**
	 * Enqueue customize scripts.
	 */
	public function enqueue_customize_scripts() {
		wp_enqueue_script( 'terminal-customize-preview', get_template_directory_uri() . '/client/build/customizerPreview.bundle.js', array(), TERMINAL_VERSION, true );
	}

	/**
	 * HEX Color sanitization callback.
	 *
	 * - Sanitization: hex_color
	 * - Control: text, WP_Customize_Color_Control
	 *
	 * Note: sanitize_hex_color_no_hash() can also be used here, depending on whether
	 * or not the hash prefix should be stored/retrieved with the hex color value.
	 *
	 * @see sanitize_hex_color() https://developer.wordpress.org/reference/functions/sanitize_hex_color/
	 * @link sanitize_hex_color_no_hash() https://developer.wordpress.org/reference/functions/sanitize_hex_color_no_hash/
	 *
	 * @param string               $hex_color HEX color to sanitize.
	 * @param WP_Customize_Setting $setting   Setting instance.
	 * @return string The sanitized hex color if not null; otherwise, the setting default.
	 */
	public function sanitize_hex_color( $hex_color, $setting ) {
		// Sanitize $inpt as a hex value.
		$hex_color = sanitize_hex_color( $hex_color );
		return ( ! is_null( $hex_color ) ? $hex_color : $setting->default );
	}

	/**
	 * Register customizer settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customize manager.
	 */
	public function customize_register( \WP_Customize_Manager $wp_customize ) {
		/**
		 * Add some fields.
		 */
		$wp_customize->add_setting(
			'header_background_color_setting',
			array(
				'default'           => '#9DC1FD',
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_setting(
			'footer_background_color_setting',
			array(
				'default'           => '#9DC1FD',
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_setting(
			'link_default_color_setting',
			array(
				'default'           => '#333',
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_setting( 'content_stories_header', array(
			'capability'        => 'edit_theme_options',
			'default'           => __( 'Latest Stories', 'terminal' ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'content_stories_header', array(
			'type'    => 'text',
			'section' => 'static_front_page',
			'label'   => __( 'Stories feed header text' ),
		) );

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'header_background_color_setting',
				array(
					'label'   => __( 'Header background color' ),
					'section' => 'colors',
				)
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'footer_background_color_setting',
				array(
					'label'   => __( 'Footer background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'footer_accent_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'footer_accent_color_setting',
				array(
					'label'   => __( 'Footer accent color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'link_default_color_setting',
				array(
					'label'   => __( 'Default link color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'nav_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'nav_background_color_setting',
				array(
					'label'   => __( 'Nav background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'header_ad_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'header_ad_background_color_setting',
				array(
					'label'   => __( 'Header ad background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'footer_ad_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'footer_ad_background_color_setting',
				array(
					'label'   => __( 'Footer ad background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'sidebar_section_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'sidebar_section_background_color_setting',
				array(
					'label'   => __( 'Sidebar background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'featured_section_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'featured_section_background_color_setting',
				array(
					'label'   => __( 'Featured background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'byline_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'byline_background_color_setting',
				array(
					'label'   => __( 'Byline background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'loop_header_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'loop_header_background_color_setting',
				array(
					'label'   => __( 'Loop header background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'post_page_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'post_page_background_color_setting',
				array(
					'label'   => __( 'Post/page background color' ),
					'section' => 'colors',
				)
			)
		);
		/**
		 * Opt some core fields into immediate update.
		 */
		$wp_customize->get_setting( 'blogname' )->transport            = 'postMessage';
		$wp_customize->get_setting( 'header_image' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'header_image_data' )->transport   = 'postMessage';
		$wp_customize->get_setting( 'header_image_data' )->transport   = 'postMessage';
		$wp_customize->get_section( 'static_front_page' )->description = '';

		/**
		 * Remove some core fields we aren't using.
		 */
		$wp_customize->remove_control( 'header_textcolor' );
		$wp_customize->remove_control( 'display_header_text' );
		$wp_customize->remove_control( 'blogdescription' );
		// $wp_customize->remove_control( 'page_for_posts' );
		// $wp_customize->remove_control( 'show_on_front' );
		// $wp_customize->remove_control( 'page_on_front' );
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Customizer', 'instance' ] );
