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
	'desktop_alignment'            => 'center',
	'desktop_width'                => '50%',
	'desktop_max_height'           => '300px',
	'desktop_background_accent'    => '',
	'mobile_alignment'             => 'center',
	'mobile_width'                 => '60%',
	'mobile_max_height'            => '200px',
	'mobile_background_accent'     => '',
) );

/*
 * Some light transforms, might be better off moved later.
 */
$desktop_background_accent = ! empty( $header_data['desktop_background_accent'] ) ? $header_data['desktop_background_accent'] : 'initial';
$mobile_background_accent  = ! empty( $header_data['mobile_background_accent'] ) ? $header_data['mobile_background_accent'] : 'initial';

$desktop_width_class = '_' . str_replace( '%', '-percent-desktop-header-width', $header_data['desktop_width'] );
$mobile_width_class  = '_' . str_replace( '%', '-percent-mobile-header-width', $header_data['mobile_width'] );

$desktop_alignment_class = 'desktop-header-alignment-' . $header_data['desktop_alignment'];
$mobile_alignment_class  = 'mobile-header-alignment-' . $header_data['mobile_alignment'];

$desktop_optional_mobile_override_flag = ! empty( $header_data['mobile_header_image_override'] ) ? 'desktop-header-has-mobile-override' : '';
?>
<div id="header">
	<?php
	printf(
		'<div id="header-inside" style="background-color: %s;">',
		esc_attr( $desktop_background_accent )
	);
	?>
		<div id="header-inside-container">
			<div id="header-leaderboard">
				<?php
				if ( is_active_sidebar( 'header' ) ) {
					dynamic_sidebar( 'header' );
				}
				?>
			</div>
			<?php
				printf(
					'<div id="logo_bar" class="%s %s %s %s">',
					esc_attr( $desktop_width_class ),
					esc_attr( $desktop_alignment_class ),
					esc_attr( $mobile_width_class ),
					esc_attr( $mobile_alignment_class )
				);
			?>
				<div id="logos">
					<?php
					printf( '<div id="logo" style="max-height: %s;" class="%s">',
						esc_attr( $header_data['desktop_max_height'] ),
						esc_attr( $desktop_optional_mobile_override_flag )
					);
					?>
						<a title="<?php esc_attr_e( 'Home', 'terminal' ); ?>" href="<?php echo esc_url( home_url() ); ?>">
							<img id="logo-image" src="<?php header_image(); ?>" draggable="false" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>" />
						</a>
					</div>
					<?php
					if ( $header_data['mobile_header_image_override'] ) {
						printf(
							'<div id="logo-mobile" style="max-height: %s">',
							esc_attr( $header_data['mobile_max_height'] )
						);
						echo wp_get_attachment_image( $header_data['mobile_header_image_override'], 'full' );
						echo '</div>';
					}
					?>
				</div>
				<?php
				$classes = array();
				if ( $header_data['cta_show_on_desktop'] ) {
						$classes[] = 'cta-show-on-desktop';
				}
				if ( $header_data['cta_show_on_mobile'] ) {
					$classes[] = 'cta-show-on-mobile';
				}
				?>
				<div class="cta <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<?php
					if ( ! empty( $header_data['cta_tagline'] ) ) {
					?>
						<p><?php echo esc_html( $header_data['cta_tagline'] ); ?></p>
					<?php
					}
					?>
					<?php
					if ( ! empty( $header_data['cta_link'] ) ) {
						printf( '<a href="%s">', esc_url( $header_data['cta_link'] ) );
					}
					if ( ! empty( $header_data['cta_icon'] ) ) {
						echo wp_get_attachment_image( $header_data['cta_icon'], 'full' );
					}
					if ( ! empty( $header_data['cta_link'] ) ) {
						echo '</a>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
