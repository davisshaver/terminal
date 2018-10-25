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

/**
 * Set root path.
 */
$root_path = realpath( __DIR__ );

/**
 * Include the Composer autoload
 */
if (
	file_exists( $root_path . '/vendor/autoload.php' )
) {
	require_once $root_path . '/vendor/autoload.php';
}

if ( ! defined( 'TERMINAL_VERSION' ) ) {
	define( 'TERMINAL_VERSION', '4.0.6' );
}

if ( ! defined( 'TERMINAL_APP' ) ) {
	define( 'TERMINAL_APP', 'terminal-app' );
}

require_once __DIR__ . '/lib/singleton.php';

require_once __DIR__ . '/inc/class-amp.php';
require_once __DIR__ . '/inc/class-customizer.php';
require_once __DIR__ . '/inc/class-data.php';
require_once __DIR__ . '/inc/class-frontend.php';
require_once __DIR__ . '/inc/class-memberpress.php';
require_once __DIR__ . '/inc/class-metaboxes.php';
require_once __DIR__ . '/inc/class-parsely.php';
require_once __DIR__ . '/inc/class-contact.php';
require_once __DIR__ . '/inc/class-theme.php';
require_once __DIR__ . '/inc/class-weather.php';
require_once __DIR__ . '/inc/class-widgets.php';
require_once __DIR__ . '/inc/data/class-ad-data.php';
require_once __DIR__ . '/inc/data/class-apps-data.php';
require_once __DIR__ . '/inc/template-tags.php';

add_filter( 'mepr-validate-signup', function( $errors ) {
	return Memberpress::instance()->check_email_domain( $errors );
} );

add_filter( 'mepr-validate-account', function( $errors ) {
	return Memberpress::instance()->check_email_domain( $errors );
} );

if ( getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-links.php';
}

if ( getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-books.php';
}

if ( getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-community.php';
}

if ( getenv( 'TERMINAL_ENABLE_HOUSING_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-housing.php';
}

if ( getenv( 'TERMINAL_ENABLE_OBITUARY_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-obituaries.php';
}
