<?php
/**
 * Template tags.
 *
 * @package Terminal
 */

/**
 * Generate a Broadstreet ad unit.
 *
 * @param int $height Ad height.
 * @param int $width Ad width.
 * @param int $unit Ad unit.
 * @return string Ad unit markup.
 */
function terminal_broadstreet_ad( $height, $width, $regular_unit, $amp_unit, $header = false, $amp_disable = false, $keywords = '', $amp_height, $amp_width ) {
	if ( ! is_amp_endpoint() ) {
		if ( ! empty( $header ) ) {
			return sprintf(
				'<div class="terminal-inline-ad"><div class="terminal-ad-header">%s</div><broadstreet-zone zone-id="%s" keywords="%s"></broadstreet-zone></div>',
				esc_html( $header ),
				esc_attr( $regular_unit ),
				esc_attr( $keywords )
			);
		}
		return sprintf(
			'<broadstreet-zone zone-id="%s" keywords="%s"></broadstreet-zone>',
			esc_attr( $regular_unit ),
			esc_attr( $keywords )
		);
	}
	if ( $amp_disable ) {
		return;
	}
	if ( ! empty( $header ) ) {
		return sprintf(
			'<div class="terminal-inline-ad"><div class="terminal-ad-header">%s</div><amp-ad width="%s" height="%s" type="broadstreetads" data-network="5918" data-zone="%s" data-keywords="%s"></amp-ad></div>',
			esc_html( $header ),
			esc_attr( $amp_width ),
			esc_attr( $amp_height ),
			esc_attr( $amp_unit ),
			esc_attr( $keywords )
		);
	}
	return sprintf(
		'<amp-ad width="%s" height="%s" type="broadstreetads" data-network="5918" data-zone="%s" data-keywords="%s"></amp-ad>',
		esc_attr( $amp_width ),
		esc_attr( $amp_height ),
		esc_attr( $amp_unit ),
		esc_attr( $keywords )
	);
}