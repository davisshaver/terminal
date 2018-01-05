<?php
/**
 * Template tags.
 *
 * @package Terminal
 */

/**
 * Template function for home link.
 *
 * @param string $type Type.
 *
 * @return string home link.
 */
function terminal_home_link( $type ) {
	switch ( $type ) {
		case 'latest':
			return home_url() . '#latest';
		default:
			return home_url();
	}
}
