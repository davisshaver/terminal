<?php
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
		return 'inherit';
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
