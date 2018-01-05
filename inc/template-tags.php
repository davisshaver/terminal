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
	if ( terminal_home_has_filter( $type ) ) {
		return '#';
	}
	if ( is_single() ) {
		// Bail if we're in single!
		$home_url = home_url();
	} else {
		global $wp;
		$home_url = home_url( $wp->request );
	}
	switch ( $type ) {
		case 'all':
			return $home_url;
		case 'staff':
			return $home_url . '?filter=staff';
		case 'community':
			return $home_url . '?filter=community';
		case 'links':
			return $home_url . '?filter=links';
		default:
			return $home_url;
	}
}

/**
 * Determine whether home has filter.
 *
 * @param string $type Type.
 * @return boolean Whether has filter.
 */
function terminal_home_has_filter( $type ) {
	$filter = get_query_var( 'filter', 'all' );
	switch ( $type ) {
		case 'all':
			if ( 'all' === $filter ) {
				return true;
			}
			break;
		case 'staff':
			if ( 'staff' === $filter ) {
				return true;
			}
			break;
		case 'community':
			if ( 'community' === $filter ) {
				return true;
			}
			break;
		case 'links':
			if ( 'links' === $filter ) {
				return true;
			}
			break;
		default:
			return false;
	}
}

/**
 * Template function to print class for active link.
 *
 * @param string $type Type.
 * @return string Class.
 */
function terminal_home_filter_class( $type ) {
	if ( terminal_home_has_filter( $type ) ) {
		return 'active-filter';
	}
	return 'inactive-filter';
}

/**
 * Template tag to get time ago for post in loop.
 *
 * @return string Time ago.
 */
function terminal_time_ago() {
	return human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ago';
}
