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
		}
	}

	/**
	 * Prints CSS from customizer.
	 */
	public function customizer_custom_css() {
		if ( ! function_exists( 'terminal_get_fm_theme_mod' ) ) {
			return;
		}
		?>
		<style type="text/css">
			.terminal-utility-font {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'utility_size', '14px' ) ) : ?>
					font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'utility_size', '14px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'utility_font_family', 'Arial, Helvetica, sans-serif' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'utility_font', 'Arial, Helvetica, sans-serif' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'utility_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'utility_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'utility_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'utility_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'utility_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'utility_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'utility_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'utility_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			.terminal-headline-font, .terminal-headline-font a {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'headline_size', '30px' ) ) : ?>
				font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'headline_size', '30px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'headline_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'headline_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'headline_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'headline_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'headline_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'headline_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'headline_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'headline_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'headline_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'headline_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			.terminal-sidebar-header-font {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_header_size', '21px' ) ) : ?>
				font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_header_size', '21px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_header_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_header_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_header_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_header_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_header_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_header_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_header_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_header_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_header_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_header_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			.terminal-sidebar-body-font {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_body_size', '16px' ) ) : ?>
				font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_body_size', '16px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_body_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_body_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_body_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_body_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_body_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_body_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidebar_body_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidebar_body_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'sidbear_body_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'sidbear_body_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			.terminal-index-meta-font {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'index_meta_size', '14px' ) ) : ?>
				font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'index_meta_size', '14px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'index_meta_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'index_meta_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'index_meta_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'index_meta_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'index_meta_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'index_meta_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'index_meta_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'index_meta_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'index_meta_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'index_meta_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			.terminal-single-meta-font {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'single_meta_size', '14px' ) ) : ?>
				font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'single_meta_size', '14px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'single_meta_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'single_meta_font', 'Georgia, Cambria, Times New Roman, Times, serif' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'single_meta_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'single_meta_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'single_meta_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'single_meta_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'single_meta_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'single_meta_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'single_meta_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'single_meta_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			.terminal-body-font {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'body_size', '14px' ) ) : ?>
					font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'body_size', '14px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'body_font', 'initial' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'body_font', 'initial' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'body_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'body_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'body_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'body_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'body_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'body_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'body_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'body_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			.terminal-cta-tagline-font {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'tagline_size', '14px' ) ) : ?>
					font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'tagline_size', '14px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'tagline_font', 'initial' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'tagline_font', 'initial' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'tagline_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'tagline_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'tagline_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'tagline_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'tagline_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'tagline_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'tagline_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'tagline_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			.terminal-cta-button-font {
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'cta_button_size', '14px' ) ) : ?>
					font-size: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'cta_button_size', '14px' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'cta_button_font', 'initial' ) ) : ?>
					font-family: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'cta_button_font', 'initial' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'cta_button_transform', 'none' ) ) : ?>
					text-transform: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'cta_button_transform', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'cta_button_style', 'none' ) ) : ?>
					font-style: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'cta_button_style', 'none' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'cta_button_weight', '400' ) ) : ?>
					font-weight: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'cta_button_weight', '400' ) ); ?>;
				<?php endif; ?>
				<?php if ( 'default' !== terminal_get_fm_theme_mod( 'typography', 'cta_button_color', '' ) ) : ?>
					color: <?php echo esc_attr( terminal_get_fm_theme_mod( 'typography', 'cta_button_color', 'initial' ) ); ?>;
				<?php endif; ?>
			}

			a {
				color: <?php echo esc_attr( get_theme_mod( 'link_default_color_setting', '#333' ) ); ?>;
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

			#footer-leaderboard {
				background-color: <?php echo esc_attr( get_theme_mod( 'footer_ad_background_color_setting', 'inherit' ) ); ?>;
			}

			.sidebar-section {
				background-color: <?php echo esc_attr( get_theme_mod( 'sidebar_section_background_color_setting', 'initial' ) ); ?>;
			}

			.topbar {
				background-color: <?php echo esc_attr( get_theme_mod( 'byline_background_color_setting', 'inherit' ) ); ?>;
			}

			#header-leaderboard {
				background-color: <?php echo esc_attr( get_theme_mod( 'header_ad_background_color_setting', 'inherit' ) ); ?>;
			}
		</style>
		<?php
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
