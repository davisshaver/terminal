<?php


if ( ! is_active_sidebar( 'terminal-primary-sidebar' ) ) {
	return;
}

echo '<div class="terminal-primary-sidebar">';
  dynamic_sidebar( 'terminal-primary-sidebar' );
echo '</div>';