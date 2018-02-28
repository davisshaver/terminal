<?php
/**
 * Silence is golden.
 *
 * @package Terminal
 */

?>
<div class="terminal-comments">
	<?php

	if ( is_active_sidebar( 'above-comments' ) ) {
		echo '<div id="comments-sidebar">';
		dynamic_sidebar( 'above-comments' );
		echo '</div>';
	}

	if ( function_exists( 'coral_talk_comments_template' ) ) {
		terminal_print_comments_header();
		coral_talk_comments_template();
	}

	$facebook_comments = getenv( 'TERMINAL_ENABLE_FACEBOOK_COMMENTS' );
	if ( $facebook_comments ) {
		terminal_print_facebook_comments_header();
		?>
		<iframe
			id="facebook-comments"
			src="https://www.facebook.com/plugins/comments.php?href=<?php echo esc_url_raw( get_the_permalink() ); ?>"
			scrolling="no"
			frameborder="0"
			style="border:none; overflow:hidden; width:100%;"
			allowTransparency="true"
		></iframe>
	<?php
	}
	?>
</div>
