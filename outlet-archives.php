<?php
/**
 * Single template file
 *
 * @package Terminal
 */

get_header();
get_template_part( 'partials/search' );
?>

<div class="terminal-container">
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		get_template_part( 'partials/content-archive', get_post_type( $post ) );
	endwhile;
endif;
terminal_print_template_part(
	'main-sidebar'
);
get_template_part( 'partials/recirc' );
?>
</div>
<?php get_footer(); ?>
