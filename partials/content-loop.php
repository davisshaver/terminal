<?php
/**
 * Main content template.
 *
 * @package Terminal
 */

$loop_data = terminal_get_layout_data( array(
	'loop_meta_position'     => 'middle',
	'hide_excerpt_on_mobile' => false,
) );

$hide_excerpt_on_mobile = ! empty( $loop_data['hide_excerpt_on_mobile'] ) ?
	true :
	false;
$data = Terminal\Data::instance();
$meta = $data->get_post_featured_meta();
if (
	! empty( $meta['add_featured_embed'] ) &&
	! empty( $meta['use_featured_embed_on_landing'] ) &&
	! empty( $meta['featured_embed'] )
) {
	$use_featured_embed = $meta['featured_embed'];
} else {
	$use_featured_embed = false;
}
$terminal_no_photo_class = ! has_post_thumbnail() ? 'terminal-no-photo' : '';
$terminal_card_title = false;
$terminal_card_title_meta = false;
$terminal_card_size = 'terminal-card-single';
if ( 'link' === $post_type ) {
	$terminal_card_title = __( '🔗 External Link', 'terminal' );
	$host = parse_url( get_the_permalink(), PHP_URL_HOST );
	$terminal_card_title_meta = __( 'via ', 'terminal' ) . $host;
} elseif ( 'housing' === $post_type ) {
	$terminal_card_title = __( '🏘️ Featured Housing', 'terminal' );
	$realtor = terminal_get_realtor( get_the_id() );
	$terminal_card_title_meta = __( 'via ', 'terminal' ) . $realtor;
}  elseif ( 'deal' === $post_type ) {
	$terminal_card_title = __( '🏷 Featured Deal', 'terminal' );
	$sponsor = terminal_get_sponsor( get_the_id() );
	$terminal_card_title_meta = __( 'via ', 'terminal' ) . $sponsor;
} elseif ( 'book' === $post_type ) {
	$terminal_card_title = __( '📖 Book Review', 'terminal' );
} elseif ( 'community' === $post_type ) {
	$terminal_card_title = __( 'Reader-Submitted Post', 'terminal' );
} elseif ( 'photo' === $post_type ) {
	$terminal_card_title = __( 'Featured Photo', 'terminal' );
} elseif ( 'post' === $post_type && class_exists( 'WPSEO_Primary_Term' ) ) {
	$wpseo_primary_term = new \WPSEO_Primary_Term( 'category', get_the_id() );
	$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
	$term = get_term( $wpseo_primary_term );
	if ( ! is_wp_error( $term ) ) { 
		$terminal_card_title = $term->name;
	}
}
$post_id = get_the_ID();
$post_has_thumbnail = has_post_thumbnail();
$post_author_nice_name = get_the_author_meta( 'user_nicename' );
$post_title_attribute = the_title_attribute( array( 'echo' => false ) );
$post_link = get_the_permalink();

printf(
  '<div
    id="%s"
    class="%s"
    data-terminal-post-id="%s"
    data-terminal-post-type="%s"
    data-terminal-has-image="%s"
    data-terminal-author="%s"
    data-terminal-photographer="%s"
    data-terminal-title="%s"
    data-terminal-view="loop"
  >',
  esc_attr( $post_type . '-' . $post_id ),
  esc_attr( implode( ' ', get_post_class(
    array(
      'terminal-post-tracking',
      'terminal-card',
	  'terminal-post-card',
	  'terminal-excerpt-font',
      $terminal_card_size,
      esc_html( $terminal_no_photo_class )
  ) ) ) ),
	esc_attr( $post_id ),
	esc_attr( $post_type ),
  esc_attr( $post_has_thumbnail ),
  esc_attr( $post_author_nice_name ),
  esc_attr( __return_false() ),
  esc_attr( $post_title_attribute )
);
	if ( ! empty( $terminal_card_title ) ) {
		printf(
			'<div class="terminal-card-title terminal-no-select">%s</div>',
				esc_html( $terminal_card_title )
		);
	}
	if ( ! empty( $terminal_card_title_meta ) ) {
		printf(
			'<div class="terminal-card-title-meta terminal-no-select">%s</div>',
				esc_html( $terminal_card_title_meta )
		);
	}
	if ( 'housing' !== $post_type && 'top' === $loop_data['loop_meta_position'] ) :
		terminal_print_template_part( 'byline', array(
			'post_type' => $post_type
		) );
	endif;
	if ( has_post_thumbnail() && empty( $use_featured_embed ) ) {
		$thumb = wp_get_attachment_image_src(
			get_post_thumbnail_id( $post_id ),
			'terminal-uncut-thumbnail-large'
		);
		printf(
			'<a
				href="%s"
				rel="bookmark"
				class="terminal-tracking terminal-card-image"
				title="%s"
				data-terminal-post-id="%s"
				data-terminal-post-type="%s"
				data-terminal-has-image="%s"
				data-terminal-author="%s"
				data-terminal-title="%s"
				data-terminal-view="loop-image"
			>',
			esc_attr( $post_link ),
			esc_attr( $post_title_attribute ),
			esc_attr( $post_id ),
			esc_attr( $post_type ),
			esc_attr( $post_has_thumbnail ),
			esc_attr( $post_author_nice_name ),
			esc_attr( $post_title_attribute )
		);
		the_post_thumbnail( 'terminal-uncut-thumbnail' );
		echo '</a>';
	} elseif ( ! empty( $use_featured_embed ) ) {
		echo '<div class="terminal-card-embed">';
		echo apply_filters( 'the_content', $use_featured_embed );
		echo '</div>';
	}
	echo '<div class="terminal-card-text">';
		if ( 'photo' === $post_type ) {
			echo '<p class="terminal-credit terminal-text-gray">';
			terminal_print_photo_caption();
			echo '</p>';
		}
		printf(
			'<h1 class="terminal-headline-font terminal-stream-headline terminal-limit-max-content-width-add-margin"><a
				id="%s"
				href="%s"
				class="terminal-tracking terminal-link-gray"
				data-terminal-post-id="%s"
				data-terminal-has-image="%s"
				data-terminal-author="%s"
				data-terminal-title="%s"
				data-terminal-view="loop-headline">%s</a></h1>',
			esc_attr( $post_type . '-headline-link-' . $post_id ),
			esc_attr( $post_link ),
			esc_attr( $post_id ),
			esc_attr( $post_has_thumbnail ),
			esc_attr( $post_author_nice_name ),
			esc_attr( $post_title_attribute ),
			esc_html( get_the_title() )
		);
		if ( 'housing' !== $post_type && 'middle' === $loop_data['loop_meta_position'] ) :
			terminal_print_template_part( 'byline', array(
				'post_type' => $post_type
			) );
		endif;
		printf(
			'<div class="terminal-card-text terminal-limit-max-content-width terminal-text-gray terminal-body-font %s">%s</div>',
			$hide_excerpt_on_mobile ? 'terminal-mobile-hide' : '',
			wp_kses_post( wpautop( get_the_excerpt() ) )
		);
		if ( 'housing' !== $post_type && 'bottom' === $loop_data['loop_meta_position'] ) :
			terminal_print_template_part( 'byline', array(
				'post_type' => $post_type
			) );
		endif;
	echo '</div>';
echo '</div>';
?>
