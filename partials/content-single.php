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

$data = Terminal\Data::instance();
$meta = $data->get_post_featured_meta();

if (
	! empty( $meta['add_featured_embed'] ) &&
	! empty( $meta['use_featured_embed_on_single'] ) &&
	! empty( $meta['featured_embed'] )
) {
	$use_featured_embed = $meta['featured_embed'];
} else {
	$use_featured_embed = false;
}
if ( is_page() ) {
	$single_data['single_meta_position'] = false;
}
printf(
	'<article class="%s" id="terminal-post-%s">',
	implode( get_post_class( array( 'terminal-card', 'terminal-post-card', 'terminal-card-double' ) ), ' ' ),
	get_the_ID()
);
if ( 'top' === $single_data['single_meta_position'] ) :
	terminal_print_template_part( 'byline', array(
		'post_type' => $post_type
	) );
endif;
echo '<div class="terminal-meta terminal-no-select">';
if ( function_exists( 'yoast_breadcrumb' ) ) {
	yoast_breadcrumb(
		'<div class="terminal-breadcrumbs terminal-single-meta-font terminal-text-gray terminal-link-gray-light">',
		'</div>'
	);
}
echo '</div>';
if ( current_user_can( 'edit_others_posts' ) ) {
	terminal_print_template_part( 'analytics' );
}
printf(
	'<h1 class="terminal-header terminal-header-no-background terminal-headline-featured-font">%s</h1>',
	get_the_title()
);
if ( has_post_thumbnail() && empty( $meta['hide_featured_image'] ) && empty( $use_featured_embed ) ) {
	echo '<div class="terminal-card-image">';
	the_post_thumbnail( 'terminal-uncut-thumbnail-large' );
	echo '</div>';
	terminal_print_featured_image_caption();
} elseif ( ! empty( $use_featured_embed ) ) {
	echo '<div class="terminal-card-embed">';
	echo apply_filters( 'the_content', $use_featured_embed );
	echo '</div>';
}
if ( 'middle' === $single_data['single_meta_position'] ) :
	terminal_print_template_part( 'byline', array(
		'post_type' => $post_type
	) );
endif;
if ( is_active_sidebar( 'terminal-before-article' ) ) {
	dynamic_sidebar( 'terminal-before-article' );
}
printf(
	'<div class="terminal-card-text terminal-body-font terminal-limit-max-content-width">%s',
	apply_filters( 'the_content', get_the_content( '<p>Read the rest of this entry &raquo;</p>' ) )
);
wp_link_pages();
echo '</div>';
if ( 'bottom' === $single_data['single_meta_position'] ) :
	terminal_print_template_part( 'byline', array(
		'post_type' => $post_type
	) );
endif;
if ( is_active_sidebar( 'terminal-after-article' ) ) {
	dynamic_sidebar( 'terminal-after-article' );
}
if ( ! is_page() && empty( $single_data['hide_bio_on_single'] ) ) :
	get_template_part( 'partials/author-snippet' );
endif;
$default = ! post_password_required() && comments_open( get_the_ID() );
$terminal_comments_open = apply_filters( 'terminal_comments_open', $default );
if ( ! is_page() && $terminal_comments_open ) :
	get_template_part( 'comments' );
endif;
echo '</article>';
