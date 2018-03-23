<?php
/**
 * The template for displaying the footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Terminal
 */

if ( ! is_home() && ! is_archive() ) {
	get_template_part( 'partials/footer' );
}
wp_footer();
?>
	</body>
</html>
