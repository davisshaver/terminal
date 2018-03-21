<?php
/**
 * Breakout partial.
 *
 * @package Terminal
 */

if ( ! is_active_sidebar( 'terminal-breakout' ) ) {
	return;
}
echo '<div class="terminal-breakout-container">';
dynamic_sidebar( 'terminal-breakout' );
echo '</div>';
