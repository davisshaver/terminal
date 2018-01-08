<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Terminal
 */

if ( is_active_sidebar( 'primary-sidebar' ) ) {
	dynamic_sidebar( 'primary-sidebar' );
}
