<?php
/**
 * Silence is golden.
 *
 * @package Terminal
 */

?>
<?php

if ( is_active_sidebar( 'above-comments' ) ) {
	echo '<div id="comments-sidebar">';
	dynamic_sidebar( 'above-comments' );
	echo '</div>';
}
?>

<iframe
	id="facebook-comments"
	src="https://www.facebook.com/plugins/comments.php?href=<?php echo esc_url_raw( get_the_permalink() ); ?>"
	scrolling="no"
	frameborder="0"
	style="border:none; overflow:hidden; width:100%;"
	allowTransparency="true"
></iframe>
