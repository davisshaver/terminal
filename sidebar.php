<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Terminal
 */

if ( ! is_active_sidebar( 'primary-sidebar' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area sidebar-widgets" role="complementary">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Sidebar', 'terminal' ); ?></h2>
	<?php dynamic_sidebar( 'primary-sidebar' ); ?>
</div><!-- #secondary -->
