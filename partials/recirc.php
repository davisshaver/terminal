
<?php
/**
 * Recirc partial.
 *
 * @package Terminal
 */
if ( ! is_active_sidebar( 'terminal-recirc' ) ) {
	return;
}
terminal_print_recirc_header();
dynamic_sidebar( 'terminal-recirc' );
