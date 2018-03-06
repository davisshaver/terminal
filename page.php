<?php
/**
 * Page template file
 *
 * @package Terminal
 */

get_header(); ?>
<div class="terminal-container">
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		get_template_part( 'partials/content-single', get_post_type( $post ) );
	endwhile;
else :
?>
	<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'terminal' ); ?></p>
<?php
endif;
get_template_part( 'partials/recirc' );
?>
</div>
<?php
	get_template_part( 'partials/sidebar' );
?>
<?php get_footer(); ?>
