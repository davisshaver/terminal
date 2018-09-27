<?php
/**
 * Sidebar partial.
 *
 * @package Terminal
 */

 if ( empty( $sidebar ) || ! is_active_sidebar( $sidebar ) ) {
	return;
}

dynamic_sidebar( $sidebar );
