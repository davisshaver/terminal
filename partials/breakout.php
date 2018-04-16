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
echo '<div class="terminal-breakout">';
dynamic_sidebar( 'terminal-breakout' );
echo '</div>';
echo '</div>';
