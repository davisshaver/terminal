<?php
/**
 * Terminal plugin.
 *
 * @package Terminal
 */

/*
Plugin Name: Terminal Dev
Text Domain: terminal
*/

namespace Terminal;

if ( ! defined( 'TERMINAL_APP' ) ) {
    define( 'TERMINAL_APP', 'terminal-app' );
}

if ( ! defined( 'TERMINAL_VERSION' ) ) {
	define( 'TERMINAL_VERSION', '4. 5' );
}

require_once __DIR__ . '/lib/singleton.php';

require_once __DIR__ . '/inc/class-contact.php';
require_once __DIR__ . '/inc/class-metaboxes.php';
require_once __DIR__ . '/inc/class-newspack.php';
require_once __DIR__ . '/inc/class-widgets.php';
require_once __DIR__ . '/inc/template-tags.php';

if ( getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-links.php';
}

if ( getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-obituaries.php';
}

if ( getenv( 'TERMINAL_ENABLE_RELEASES_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-releases.php';
}