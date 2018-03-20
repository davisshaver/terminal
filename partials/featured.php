<?php
/**
 * Featured partial.
 *
 * @package Terminal
 */

if ( ! is_active_sidebar( 'terminal-featured' ) ) {
	return;
}
echo '<div class="terminal-hero-container">';
dynamic_sidebar( 'terminal-featured' );
echo '</div>';
