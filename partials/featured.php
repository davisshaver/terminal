<?php
/**
 * Featured partial.
 *
 * @package Terminal
 */

if ( ! is_active_sidebar( 'terminal-featured' ) ) {
	return;
}

dynamic_sidebar( 'terminal-featured' );

