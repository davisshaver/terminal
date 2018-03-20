<?php
/**
 * Header partial.
 *
 * @package Terminal
 */

$header_data = terminal_get_header_data( array(
	'mobile_header_image_override' => null,
	'cta_show_on_desktop'          => false,
	'cta_show_on_mobile'           => false,
	'cta_link'                     => '',
	'cta_icon'                     => null,
	'cta_tagline'                  => '',
	'signup_show_on_desktop'       => false,
	'signup_show_on_mobile'        => false,
	'signup_button'                => '',
	'signup_icon'                  => null,
	'signup_link'                  => '',
) );

$desktop_optional_mobile_override_flag = ! empty( $header_data['mobile_header_image_override'] ) ? 'terminal-mobile-hide' : '';

if ( ! empty( $header_data['signup_show_on_desktop'] ) || ! empty( $header_data['signup_show_on_desktop'] ) ) {
	printf(
		'<div class="terminal-signup-container %s %s"><div class="terminal-signup"><a href="%s">%s %s</a></div></div>',
		! empty( $header_data['signup_show_on_desktop'] ) ? esc_attr( 'terminal-desktop-show' ) : '',
		! empty( $header_data['signup_show_on_mobile'] ) ? esc_attr( 'terminal-mobile-show' ) : '',
		! empty( $header_data['signup_link'] ) ? esc_url( $header_data['signup_link' ] ) : '',
		! empty( $header_data['signup_icon'] ) ? wp_kses_post( wp_get_attachment_image(
			$header_data['signup_icon'],
			'terminal-uncut-thumbnail-logo',
			false
		) ) : '',
		! empty( $header_data['signup_button'] ) ? esc_attr( $header_data['signup_button'] ) : ''
	);
}
?>
<div class="terminal-header-container">
	<?php
	echo '<div class="terminal-header-inside">';
		if ( is_active_sidebar( 'terminal-header' ) ) {
			dynamic_sidebar( 'terminal-header' );
		}
		// Start logos
		printf(
			'<div class="terminal-logos">'
		);
			// Main logo.
			printf( '<div class="terminal-logo %s">',
				esc_attr( $desktop_optional_mobile_override_flag )
			);
				printf(
					'<a title="%s" href="%s">',
					esc_attr( 'Home', 'terminal' ),
					esc_url( home_url() )
				);
					printf(
						'<img class="terminal-image" src="%s" draggable="false" alt="%s" />',
						get_header_image(),
						esc_attr( get_bloginfo( 'title' ) )
					);
				echo '</a>';
			echo '</div>';
			// Mobile logo (optional).
			if ( $header_data['mobile_header_image_override'] ) {
				printf(
					'<a class="terminal-desktop-hide" title="%s" href="%s">',
					esc_attr__( 'Home', 'terminal' ),
					esc_url( home_url() )
				);
				echo wp_get_attachment_image(
					$header_data['mobile_header_image_override'],
					'terminal-uncut-thumbnail-logo',
					false
				);
				echo '</a>';
			}
			$classes = array();
			if ( $header_data['cta_show_on_desktop'] ) {
					$classes[] = 'terminal-desktop-show';
			}
			if ( $header_data['cta_show_on_mobile'] ) {
				$classes[] = 'terminal-mobile-show';
			}
			printf(
				'<div class="terminal-cta %s">',
				esc_attr( implode( ' ', $classes ) )
			);
				if ( ! empty( $header_data['cta_tagline'] ) ) {
					printf(
						'<div class="terminal-cta-tagline terminal-cta-tagline-font">%s</div>',
						wp_kses_post( wpautop( $header_data['cta_tagline'] ) )
					);
				}
				echo '<div class="terminal-cta-link terminal-cta-button-font">';
					if ( ! empty( $header_data['cta_icon'] ) ) {
						if ( ! empty( $header_data['cta_link'] ) ) {
							printf( '<a href="%s">', esc_url( $header_data['cta_link'] ) );
						}
						$image = wp_get_attachment_image_src( $header_data['cta_icon'], 'terminal-uncut-thumbnail-small' );
						if ( ! empty( $image ) ) {
							printf(
								'<img class="terminal-cta-image" src="%s" />',
								esc_url( $image[0] )
							);
						}
						if ( ! empty( $header_data['cta_link'] ) ) {
							echo '</a>';
						}
					}
					if ( ! empty( $header_data['cta_button'] ) ) {
						if ( ! empty( $header_data['cta_link'] ) ) {
							printf( '<a href="%s" class="terminal-action">', esc_url( $header_data['cta_link'] ) );
						}
						echo esc_html( $header_data['cta_button'] );
						if ( ! empty( $header_data['cta_link'] ) ) {
							echo '</a>';
						}
					}
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';
?>

