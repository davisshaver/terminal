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
		add_filter( 'site_icon_meta_tags', [ $this, 'site_icon_meta_tags' ] );
		add_action( 'wp_head', [ $this, 'customizer_custom_css' ] );
		add_action( 'amp_post_template_css', [ $this, 'customizer_custom_css_amp' ] );
		add_filter( 'amp_post_template_data', function( $data ) {
			unset( $data['font_urls']['merriweather'] );
			$data['amp_component_scripts'] = array_merge(
				$data['amp_component_scripts'],
				array(
					'amp-ad' => 'https://cdn.ampproject.org/v0/amp-ad-latest.js',
				)
			);
			return $data;
		} );
		add_filter( 'amp_post_template_data', function( $data ) {
			$data['amp_component_scripts'] = array_merge(
				$data['amp_component_scripts'],
				array(
					'amp-ad' => 'https://cdn.ampproject.org/v0/amp-ad-latest.js',
				)
			);
			return $data;
		} );
		// Helllllo Fieldmanager!
		// require_once __DIR__ . '/widgets/class-broadstreet-widget.php' // @todo add this back.
		if ( defined( 'FM_BETA_CUSTOMIZE_VERSION' ) ) {
			require_once __DIR__ . '/customizer/class-fm-apps.php';
			require_once __DIR__ . '/customizer/class-fm-ads.php';
			require_once __DIR__ . '/customizer/class-fm-bylines.php';
			require_once __DIR__ . '/customizer/class-fm-layout.php';
			require_once __DIR__ . '/customizer/class-fm-fonts.php';
			require_once __DIR__ . '/customizer/class-fm-membership.php';
			require_once __DIR__ . '/customizer/class-fm-header.php';
			require_once __DIR__ . '/customizer/class-fm-footer.php';
		}
	}

	/**
	 * Prints CSS from customizer.
	 */
	public function customizer_custom_css_amp() {
		$this->customizer_custom_css( true );
	}

	/**
	 * Prints CSS from customizer.
	 *
	 * @param bool $amp Whether to print AMP tags.
	 */
	public function customizer_custom_css( $amp = false ) {
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
		 * Helper line height.
		 *
		 * @param string $key String.
		 * @return string value.
		 */
		function terminal_customizer_line_height( $key ) {
			return ( 'default' !== terminal_get_fm_theme_mod( 'typography', "${key}_line_height", 'default' ) ) ?
			terminal_get_fm_theme_mod( 'typography', "${key}_line_height", 'default' ) : false;
		}

		/**
		 * Helper font stylesheet.
		 *
		 * @param string $key String.
		 * @return string font/opt stylesheet.
		 */
		function terminal_customizer_font_stylesheet( $key ) {
			$font = terminal_get_fm_theme_mod( 'typography', "${key}_font", 'none' );
			if ( ! isset( $font['stylesheet'] ) || 'default' === $font['stylesheet'] ) {
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
			if ( ! isset( $font['family'] ) || 'default' === $font['family'] ) {
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
	$header_data = terminal_get_header_data( array(
		'signup_show_on_desktop'       => false,
		'signup_show_on_mobile'        => false,
	) );
		
		$google_stylesheets = array();
		$font_data = array(
			'targets' => array(
				'.terminal-body-font' => 'body',
				'.terminal-cta-button-font' => 'cta_button',
				'.terminal-cta-tagline-font' => 'cta_tagline',
				'.terminal-nav-font, .terminal-nav-font a	' => 'nav',
				'.terminal-headline-featured-font, .terminal-headline-featured-font a' => 'head_featured',
				'.terminal-headline-font, .terminal-headline-font a' => 'headline',
				'.terminal-index-meta-font' => 'index_meta',
				'.terminal-header-font, .terminal-header-font a, .terminal-header-font a:hover' => 'loop_header',
				'.terminal-share-button-font' => 'share',
				'.terminal-sidebar-body-font' => 'sidebar_body',
				'.terminal-sidebar-header-font' => 'sidebar_header',
				'.terminal-single-meta-font' => 'single_meta',
				'body, .terminal-utility-font' => 'utility',
			),
		);
		if ( ! $amp ) {
			echo '<style type="text/css">';
		}
		foreach ( $font_data['targets'] as $key => $value ) {
			$styles = array(
				'font-size' => terminal_customizer_font_size( $value ),
				'text-transform' => terminal_customizer_text_transform( $value ),
				'line-height' => terminal_customizer_line_height( $value ),
				'font-style' => terminal_customizer_font_style( $value ),
				'font-weight' => terminal_customizer_weight( $value ),
				'color' => terminal_customizer_color( $value ),
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
			if ( ! empty( $font_family ) ) {
				printf(
					'font-family: %s; ',
					esc_html( $font_family )
				);
			}
			echo ' } ';
			printf(
				'%s svg { fill: %s; } ',
				esc_js( $key),
				esc_js( terminal_customizer_color( $value ) )
			);
		}
		?>
			.terminal-nav-bar, .terminal-nav-bar-inside-more {
				background-color: <?php echo esc_attr( get_theme_mod( 'nav_background_color_setting', 'inherit' ) ); ?>;
			}
		<?php
			$ad_options = get_option( 'terminal_ad_options', array( 'adblock_nag' => 0 ) );
			if ( ! empty( $ad_options['adblock_nag'] ) && ! empty( $image_src = wp_get_attachment_image_src( $ad_options['adblock_nag'], 'terminal-thumbnail', false, array( 'scheme' => 'https' ) ) ) ) {
			?>
			body.uncovered .terminal-card.covered-target {
				background-image: url("<?php echo esc_attr( $image_src[0] ); ?>");
			}
			<?php
			}
			$header_accent = get_theme_mod( 'header_accent_color_setting', false );
			if ( ! empty( $header_accent ) ) {
				printf(
					'body { border-top: 3px solid %s; } .terminal-signup { background-color: %s; } @media (max-width: 800px) { body { border-top: %s solid %s; } } ',
					esc_attr( get_theme_mod( 'header_accent_color_setting', null ) ),
					esc_attr( get_theme_mod( 'header_accent_color_setting', null ) ),
					esc_attr( get_theme_mod( 'header_accent_color_setting', null ) ),
					! empty( $header_data['signup_show_on_mobile'] ) ? '30px' : '3px',
					esc_attr( get_theme_mod( 'header_accent_color_setting', null ) )
				);
			}
			?>
			.terminal-header-container {
				background-color: <?php echo esc_attr( get_theme_mod( 'header_background_color_setting', '#9DC1FD' ) ); ?>;
			}

			.terminal-footer {
				background-color: <?php echo esc_attr( get_theme_mod( 'footer_background_color_setting', '#9DC1FD' ) ); ?>;
				<?php if ( ! empty( get_theme_mod( 'footer_accent_color_setting', '#9DC1FD' ) ) ) : ?>
					border-bottom: 2px solid <?php echo esc_attr( get_theme_mod( 'footer_accent_color_setting', 'inherit' ) ); ?>
				<?php endif; ?>
			}

		.terminal-post-card, .terminal-card {
				<?php
				$post_page_background = get_theme_mod( 'post_page_background_color_setting', false );
				if ( ! empty( $post_page_background ) ) {
					printf( 'background-color: %s;', esc_attr( $post_page_background ) );
				}
				?>
			}

			.terminal-header {
				<?php
				$loop_header_background_color = get_theme_mod( 'loop_header_background_color_setting', false );
				if ( ! empty( $loop_header_background_color ) ) {
					printf( 'background-color: %s;', esc_attr( $loop_header_background_color ) );
				}
				?>
			}

			.terminal-footer-leaderboard {
				background-color: <?php echo esc_attr( get_theme_mod( 'footer_ad_background_color_setting', 'inherit' ) ); ?>;
			}

			.terminal-featured-section, .terminal-hero-container .terminal-hero-widget, .terminal-breakout-container .terminal-hero-widget, #terminal-search, .terminal-card-fade {
				<?php
				$featured_section = get_theme_mod( 'featured_section_background_color_setting', false );
				if ( ! empty( $featured_section ) ) {
					printf( 'background-color: %s;', esc_attr( $featured_section ) );
				}
				?>
			}

			.terminal-primary-sidebar .terminal-sidebar-card {
				<?php
				$sidebar_section = get_theme_mod( 'sidebar_section_background_color_setting', false );
				if ( ! empty( $sidebar_section ) ) {
					printf( 'background-color: %s;', esc_attr( $sidebar_section ) );
				}
				?>
			}

			.terminal-sidebar-card {
				<?php
				$sidebar_section = get_theme_mod( 'sidebar_section_background_color_setting', false );
				if ( ! empty( $sidebar_section ) ) {
					printf( 'background-color: %s;', esc_attr( $sidebar_section ) );
				}
				?>
			}

			.terminal-byline {
				background-color: <?php echo esc_attr( get_theme_mod( 'byline_background_color_setting', 'inherit' ) ); ?>;
				padding: 5px;
			}

			.terminal-header-leaderboard {
				background-color: <?php echo esc_attr( get_theme_mod( 'header_ad_background_color_setting', 'inherit' ) ); ?>;
			}
			figure.wp-caption, .terminal-breadcrumbs, .terminal-card-title {
				background-color: <?php echo esc_attr( get_theme_mod( 'card_title_background_color_setting', '#f2f2f2' ) ); ?>;
			}
		<?php
		if ( $amp ) {
			?>

			.terminal-credit, .terminal-featured-caption {
				max-width: calc(100vw - 30px);
				margin: auto;
			}
			.amp-wp-article-content .wpcnt {
					display: none;
				}
			.terminal-amp-ad {
				height: 50px;
				padding: 5px 0;
				text-align: center;
				background-color: <?php echo esc_attr( get_theme_mod( 'header_ad_background_color_setting', 'inherit' ) ); ?>
			}

			.terminal-amp-header {
				padding-top: 2px;
			}

			.terminal-amp-header-image amp-img {
				max-height: 200px;
				width: 100%;
				max-width: 400px;
				margin: 0 auto;
			}

			.terminal-amp-ad-center {
				text-align: center;
			}

			.terminal-amp-footer-ad {
				text-align: center;
				margin-bottom: 2px;
			}
			.amp-wp-byline amp-img {
				border: none;
				border-radius: unset;
			}
			.amp-wp-byline amp-img {
				border: none;
				border-radius: unset;
			}

			.terminal-amp-footer-ad {
				background-color: <?php echo esc_attr( get_theme_mod( 'footer_ad_background_color_setting', 'inherit' ) ); ?>;
			}

			svg {
				width: auto;
				max-width: 1em;
				height: auto;
				max-height: 1em;
			}

			.amp-wp-footer {
				text-align: center;
				background-color: <?php echo esc_attr( get_theme_mod( 'footer_background_color_setting', '#9DC1FD' ) ); ?>;
				<?php if ( ! empty( get_theme_mod( 'footer_accent_color_setting', '#9DC1FD' ) ) ) : ?>
					border-bottom: 2px solid <?php echo esc_attr( get_theme_mod( 'footer_accent_color_setting', 'inherit' ) ); ?>
				<?php endif; ?>
			}
			.amp-wp-footer p {
				width: 100%;
			}
			div.terminal-ppc-logo {
				text-align: center;
			}

			.terminal-featured-credit svg {
				width: 1rem;
				height: 1rem;
				fill: #ccc;
				top: 0.125rem;
				margin-right: 0.25rem;
				position: relative;
			}

			.terminal-featured-meta {
				margin: 10px auto;
				max-width: 600px;
				color: #999;
			}

			.terminal-featured-meta svg {
				fill: #999;
			}
		<?php
		}
		if ( ! $amp ) {
			echo '</style>';
		}
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
			)
		);
		$wp_customize->add_setting(
			'footer_background_color_setting',
			array(
				'default'           => '#9DC1FD',
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
			)
		);
		$wp_customize->add_setting( 'content_stories_header', array(
			'capability'        => 'edit_theme_options',
			'default'           => __( 'Latest Stories', 'terminal' ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
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
			'header_accent_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'header_accent_color_setting',
				array(
					'label'   => __( 'Header accent color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'footer_accent_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
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
			'sidebar_section_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'sidebar_section_background_color_setting',
				array(
					'label'   => __( 'Sidebar section background color' ),
					'section' => 'colors',
				)
			)
		);
		$wp_customize->add_setting(
			'loop_header_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
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
		$wp_customize->add_setting(
			'card_title_background_color_setting',
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => [ $this, 'sanitize_hex_color' ],
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'card_title_background_color_setting',
				array(
					'label'   => __( 'Card title/breadcrumbs/caption background color' ),
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
