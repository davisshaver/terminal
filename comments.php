<?php
/**
 * Comments template.
 *
 * @package Terminal
 */

echo '<div class="terminal-comments-container">';
if ( is_active_sidebar( 'terminal-above-comments' ) ) {
	dynamic_sidebar( 'terminal-above-comments' );
}

if ( function_exists( 'coral_talk_comments_template' ) ) {
	terminal_print_comments_header();
	coral_talk_comments_template();
}
echo '</div>';
