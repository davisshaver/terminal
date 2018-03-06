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
printf(
	'<article class="%s" id="terminal-post-%s">',
	implode( get_post_class( 'terminal-single-item' ), ' ' ),
	get_the_ID()
);
if ( 'top' === $single_data['single_meta_position'] ) :
	get_template_part( 'partials/byline', get_post_type( $post ) );
endif;
if ( function_exists( 'yoast_breadcrumb' ) ) {
	echo '<div class="terminal-breadcrumbs terminal-single-meta-font">';
	yoast_breadcrumb(
		'',
		''
	);
	echo '</div>';
}
printf(
	'<h1 class="terminal-headline-featured-font">%s</h1>',
	get_the_title()
);
if ( has_post_thumbnail() ) :
	echo '<div class="terminal-featured-image">';
	the_post_thumbnail( 'terminal-uncut-thumbnail-large' );
	echo '</div>';
	terminal_print_featured_image_caption();
endif;
if ( is_active_sidebar( 'terminal-before-article' ) ) {
	dynamic_sidebar( 'terminal-before-article' );
}
if ( 'middle' === $single_data['single_meta_position'] ) :
	get_template_part( 'partials/byline', get_post_type( $post ) );
endif;
printf(
	'<div class="terminal-story-text terminal-body-font">%s',
	apply_filters( 'the_content', get_the_content( '<p>Read the rest of this entry &raquo;</p>' ) )
);
wp_link_pages();
echo '</div>';
if ( 'bottom' === $single_data['single_meta_position'] ) :
	get_template_part( 'partials/byline', get_post_type( $post ) );
endif;
if ( is_active_sidebar( 'terminal-after-article' ) ) {
	terminal_print_after_article_header();
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
