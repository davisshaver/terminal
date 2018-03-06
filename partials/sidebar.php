<?php
/**
 * Sidebar partial.
 *
 * @package Terminal
 */

if ( ! is_active_sidebar( 'terminal-primary-sidebar' ) ) {
	return;
}

dynamic_sidebar( 'terminal-primary-sidebar' );
