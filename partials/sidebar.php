<?php
/**
 * Sidebar partial.
 *
 * @package Terminal
 */

if ( ! is_active_sidebar( 'primary-sidebar' ) ) {
	return;
}

$header_data = terminal_get_sidebar_data( array(
	'alignment' => 'left',
) );
$sidebar_alignment = ! empty( $header_data['alignment'] ) && 'left' !== $header_data['alignment'] ? $header_data['alignment'] : null;

printf(
	'<div id="sidebar" class="%s">',
	esc_attr( $sidebar_alignment )
);
dynamic_sidebar( 'primary-sidebar' );
echo '</div>';

