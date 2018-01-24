<?php
/**
 * Recirc partial.
 *
 * @package Terminal
 */

if ( ! is_active_sidebar( 'recirc' ) ) {
	return;
}

echo '<div id="recirc">';
terminal_print_recirc_header();
dynamic_sidebar( 'recirc' );
echo '</div>';

