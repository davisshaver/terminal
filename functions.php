<?php
/**
 * Terminal functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Terminal only works if the REST API is available with the latest improvements to the Customizer in 4.9.
 */
if ( version_compare( strtok( $GLOBALS['wp_version'], '-' ), '4.9', '<' ) ) {
	require get_template_directory() . '/inc/warnings.php';
	return;
}

if ( ! defined( 'TERMINAL_VERSION' ) ) {
	define( 'TERMINAL_VERSION', '3.15' );
}

if ( ! defined( 'TERMINAL_APP' ) ) {
	define( 'TERMINAL_APP', 'terminal-app' );
}

if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
	show_admin_bar( true );
}

require_once __DIR__ . '/lib/singleton.php';

require_once __DIR__ . '/inc/class-theme.php';

// Remaining classes can be loaded independently.
require_once __DIR__ . '/inc/class-api.php';
require_once __DIR__ . '/inc/class-metaboxes.php';
require_once __DIR__ . '/inc/class-widgets.php';
require_once __DIR__ . '/inc/class-customizer.php';
require_once __DIR__ . '/inc/class-data.php';
require_once __DIR__ . '/inc/class-frontend.php';
require_once __DIR__ . '/inc/class-menu.php';
require_once __DIR__ . '/inc/class-parsely.php';
require_once __DIR__ . '/inc/class-memberpress.php';
require_once __DIR__ . '/inc/class-permalinks.php';
require_once __DIR__ . '/inc/class-settings.php';


add_filter( 'mepr-validate-signup', function( $errors ) {
	return Memberpress::instance()->check_email_domain( $errors );
} );

add_filter( 'mepr-validate-account', function( $errors ) {
	return Memberpress::instance()->check_email_domain( $errors );
} );

if(
	! function_exists('twitter_api_get') &&
	file_exists( dirname( __FILE__ ) . '/lib/wp-twitter-api/twitter-api.php' )
) {
	require dirname( __FILE__ ) . '/lib/wp-twitter-api/twitter-api.php';
}

// Features
if ( getenv( 'TERMINAL_ENABLE_LINK_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-links.php';
}

if ( getenv( 'TERMINAL_ENABLE_BOOK_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-books.php';
}

if ( getenv( 'TERMINAL_ENABLE_PHOTO_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-photos.php';
}

if ( getenv( 'TERMINAL_ENABLE_COMMUNITY_POST_TYPE' ) ) {
	require_once __DIR__ . '/inc/post-types/class-community.php';
}

// Define template tags last. They may need classes.
require_once __DIR__ . '/inc/template-tags.php';
