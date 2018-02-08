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
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
if ( 'top' === $loop_data['loop_meta_position'] ) :
	get_template_part( 'partials/byline', get_post_type( $post ) );
endif;
?>
	<div class="post-flex-box">
	<?php
	if ( has_post_thumbnail() ) :
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'terminal-thumbnail' );
	?>
		<div class="image lazyload" data-bg="<?php echo esc_url( $thumb['0'] ); ?>')">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"></a>
		</div>
	<?php
	endif;
	?>
		<div class="post-row">
			<h3 class="headline terminal-headline-font">
				<a href="<?php the_permalink(); ?>" class="link-gray">
					<?php the_title(); ?>
				</a>
			</h3>
			<?php
			if ( 'middle' === $loop_data['loop_meta_position'] ) :
				get_template_part( 'partials/byline', get_post_type( $post ) );
			endif;
			?>
			<div class="story-text terminal-body-font text-gray-lighter <?php echo esc_attr( "mobile-hide-$hide_excerpt_on_mobile" ); ?>">
				<?php
					the_excerpt();
				?>
			</div>
			<?php
			if ( 'bottom' === $loop_data['loop_meta_position'] ) :
				get_template_part( 'partials/byline', get_post_type( $post ) );
			endif;
			?>
		</div>
	</div>
</div>
