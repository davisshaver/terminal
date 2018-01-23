<?php
/**
 * Single template file
 *
 * @package Terminal
 */

get_header(); ?>
<div id="container">
	<div id="body">
		<div class="content">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'partials/content-single', get_post_type( $post ) );
			endwhile;
			get_template_part( 'partials/share' );
		else :
		?>
			<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'terminal' ); ?></p>
		<?php
		endif;
		?>
		</div>
		<?php
			get_template_part( 'partials/sidebar' );
		?>
	</div>
</div>
<?php get_footer(); ?>
