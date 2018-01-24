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
dynamic_sidebar( 'recirc' );
echo '</div>';

