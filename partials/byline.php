<?php
/**
 * Post metadata/byline.
 *
 * @package Terminal
 */

$header_data = terminal_get_byline_options( array(
	'loop_hide_avatar'     => false,
	'loop_avatar_size'     => 25,
	'loop_hide_date'       => false,
	'loop_hide_author'     => false,
	'loop_hide_category'   => false,
	'loop_hide_comments'   => false,
	'loop_hide_edit'       => false,
	'single_hide_avatar'   => false,
	'single_avatar_size'   => 25,
	'single_hide_date'     => false,
	'single_hide_author'   => false,
	'single_hide_category' => false,
	'single_hide_comments' => false,
	'single_hide_edit'     => false,
) );

$avatar_size = intval(
	is_singular() ?
	$header_data['single_avatar_size'] :
	$header_data['loop_avatar_size']
);

$avatar_size_class = "flex-basis-$avatar_size";

$hide_avatar = boolval(
	is_singular() ?
	$header_data['single_hide_avatar'] :
	$header_data['loop_hide_avatar']
);

$hide_date = boolval(
	is_singular() ?
	$header_data['single_hide_date'] :
	$header_data['loop_hide_date']
);

$hide_author = boolval(
	is_singular() ?
	$header_data['single_hide_author'] :
	$header_data['loop_hide_author']
);

$hide_category = boolval(
	is_singular() ?
	$header_data['single_hide_category'] :
	$header_data['loop_hide_category']
);

$hide_comments = boolval(
	is_singular() ?
	$header_data['single_hide_comments'] :
	$header_data['loop_hide_comments']
);

$hide_edit = boolval(
	is_singular() ?
	$header_data['single_hide_edit'] :
	$header_data['loop_hide_edit']
);

$byline_style = is_singular() ? 'terminal-single-meta-font' : 'terminal-index-meta-font';
?>
<div class="topbar <?php echo esc_attr( $byline_style ); ?>">
	<?php
	if ( ! $hide_avatar ) :
	?>
		<div class="avatar <?php echo esc_attr( $avatar_size_class ); ?>">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>">
				<?php terminal_print_avatar( $avatar_size ); ?>
			</a>
		</div>
	<?php
	endif;
	?>
	<div class="author-and-date">
		<?php
		if ( ! $hide_author ) :
		?>
			<div class="author">
				<?php
					esc_html_e( 'By ', 'terminal' );
					the_author_posts_link();
				?>
			</div>
		<?php
		endif;
		if ( ! $hide_date ) :
		?>
			<abbr class="date" title="<?php the_time( 'l, F j, Y \a\t g:ia' ); ?>"><?php echo esc_html( terminal_time_ago() ); ?></abbr>
		<?php
		endif;
		if ( ! $hide_category ) :
		?>
			<div class="category"><?php the_category( ', ' ); ?></div>
		<?php
		endif;
		if ( ! $hide_comments && comments_open( get_the_ID() ) ) :
		?>
			<div class="numberofcomments"><a href="<?php comments_link(); ?>"><strong>Comments</strong></a><span class="share-number">&nbsp;<?php terminal_print_comment_count_for_post(); ?></span></div>
		<?php
		endif;
		if ( ! $hide_edit && is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
		?>
			<div>
				<a href="<?php echo esc_url( get_edit_post_link() ); ?>"><img  height="14" width="14" src="<?php echo esc_url( get_template_directory_uri() ); ?>/client/static/images/edit.png" alt="E" /></a>
			</div>
		<?php
		}
		?>
	</div>
</div>
