<?php
/**
 * Single template file
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
endif;
terminal_print_template_part(
	'main-sidebar'
);
get_template_part( 'partials/recirc' );
?>
</div>
<?php get_footer(); ?>
