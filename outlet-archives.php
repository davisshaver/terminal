<?php
/**
 * Template name: Outlet archive
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
				get_template_part( 'partials/content-archive', get_post_type( $post ) );
				?>
				<?php
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
	</div>
</div>
<?php get_footer(); ?>