<?php
/**
 * Terminal plugin.
 *
 * @package Terminal
 */

/*
Plugin Name: Terminal Dev Newspack
Text Domain: terminal
*/

namespace Terminal;

require_once __DIR__ . '/lib/singleton.php';

if ( getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-links.php';
}

if ( getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-obituaries.php';
}

if ( getenv( 'TERMINAL_ENABLE_RELEASES_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-releases.php';
}
