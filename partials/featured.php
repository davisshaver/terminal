<?php
/**
 * Featured partial.
 *
 * @package Terminal
 */

if ( ! is_active_sidebar( 'featured' ) ) {
	return;
}

echo '<div id="featured">';
dynamic_sidebar( 'featured' );
echo '</div>';

