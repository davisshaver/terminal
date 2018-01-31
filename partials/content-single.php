<?php
/**
 * Main single template.
 *
 * @package Terminal
 */

$single_data = terminal_get_layout_data( array(
	'single_meta_position' => 'top',
	'hide_bio_on_single' => false,
) );

if ( is_page() ) {
	$single_data['single_meta_position'] = false;
}
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div id="single">
		<?php
		if ( 'top' === $single_data['single_meta_position'] ) :
			get_template_part( 'partials/byline', get_post_type( $post ) );
		endif;
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb(
				'<p id="breadcrumbs" class="terminal-single-meta-font">',
				'</p>'
			);
		}
		?>
		<h1 class="terminal-headline-font"><?php the_title(); ?></h1>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image">
				<?php the_post_thumbnail( 'terminal-primary-thumbnail' ); ?>
			</div>
		<?php
		if ( function_exists( 'cc_featured_image_caption' ) ) {
			cc_featured_image_caption();
		}
		endif;
		?>
		<?php
		if ( 'middle' === $single_data['single_meta_position'] ) :
			get_template_part( 'partials/byline', get_post_type( $post ) );
		endif;
		?>
		<div class="story-text terminal-body-font">
			<?php the_content( '<p>Read the rest of this entry &raquo;</p>' ); ?>
			<?php wp_link_pages(); ?> 
		</div>
		<?php
		if ( 'bottom' === $single_data['single_meta_position'] ) :
			get_template_part( 'partials/byline', get_post_type( $post ) );
		endif;
		?>
	</div>
	<?php
	get_template_part( 'partials/share' );
	if ( ! is_page() && empty( $single_data['hide_bio_on_single'] ) ) :
		get_template_part( 'partials/author-snippet' );
	endif;
	?>
	<a name="respond"></a>
	<?php
	$default = ! post_password_required() && comments_open( get_the_ID() );
	$terminal_comments_open = apply_filters( 'terminal_comments_open', $default );
	if ( ! is_page() && $terminal_comments_open ) :
		comments_template();
	endif;
	?>
</article>
