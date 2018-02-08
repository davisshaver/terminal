<?php
/**
 * Post metadata/byline.
 *
 * @package Terminal
 */

$template_data = terminal_get_layout_data( array(
	'hide_byline_on_mobile' => false,
) );

$byline_data = terminal_get_byline_options( array(
	'default_gravatar'     => null,
	'time_ago_format'      => 'relative',
	'hide_by'              => false,
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
	$byline_data['single_avatar_size'] :
	$byline_data['loop_avatar_size']
);

$avatar_size_class = "flex-basis-$avatar_size";

$hide_byline_on_mobile = ! empty( $template_data['hide_byline_on_mobile'] ) && ! is_singular() ?
	'1' :
	'0';

$format = ! empty( $byline_data['time_ago_format'] ) ?
	strval( $byline_data['time_ago_format'] ) :
	'relative';

$default_gravatar = ! empty( $byline_data['default_gravatar'] ) ?
	intval( $byline_data['default_gravatar'] ) :
	false;

$hide_avatar = boolval(
	is_singular() ?
	$byline_data['single_hide_avatar'] :
	$byline_data['loop_hide_avatar']
);

$hide_date = boolval(
	is_singular() ?
	$byline_data['single_hide_date'] :
	$byline_data['loop_hide_date']
);

$hide_author = boolval(
	is_singular() ?
	$byline_data['single_hide_author'] :
	$byline_data['loop_hide_author']
);

$hide_category = boolval(
	is_singular() ?
	$byline_data['single_hide_category'] :
	$byline_data['loop_hide_category']
);

$hide_comments = boolval(
	is_singular() ?
	$byline_data['single_hide_comments'] :
	$byline_data['loop_hide_comments']
);

$hide_edit = boolval(
	is_singular() ?
	$byline_data['single_hide_edit'] :
	$byline_data['loop_hide_edit']
);

$hide_by = boolval(
	! empty( $byline_data['hide_by'] )
);

$byline_style = is_singular() ? 'terminal-single-meta-font' : 'terminal-index-meta-font';
printf(
	'<div class="topbar %s %s">',
	esc_attr( $byline_style ),
	esc_attr( "mobile-hide-$hide_byline_on_mobile" )
);
if ( ! $hide_avatar ) :
?>
	<div class="avatar <?php echo esc_attr( $avatar_size_class ); ?>">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>">
			<?php terminal_print_avatar( $avatar_size, $default_gravatar ); ?>
		</a>
	</div>
<?php
endif;
?>
	<div class="author-and-date">
		<?php
		if ( ! $hide_author ) :
		?>
			<div class="author text-gray-lighter">
				<?php
				if ( ! $hide_by ) {
					esc_html_e( 'By ', 'terminal' );
				}
				printf(
					'<a href="%s" class="link-gray-lighter">',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) )
				);
				the_author();
				?>
				</a>
			</div>
		<?php
		endif;
		if ( ! $hide_date ) :
			if ( 'relative' === $format ) {
				$time = terminal_time_ago();
			} else {
				$time = get_the_time( 'n/j/y g:i a' );
			}
			$archive_year  = get_the_time( 'Y' );
			$archive_month = get_the_time( 'm' );
			$archive_day   = get_the_time( 'd' );
			?>
			<div>
				<a href="<?php echo esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ); ?>"  class="link-gray-lighter">
					<abbr class="date link-gray-lighter" title="<?php the_time( 'l, F j, Y \a\t g:ia' ); ?>"><?php echo esc_html( $time ); ?></abbr>
				</a>
			</div>
		<?php
		endif;
		if ( ! $hide_category ) :
		?>
			<div class="category link-gray-lighter text-gray-lighter"><?php the_category( ', ' ); ?></div>
		<?php
		endif;
		if (
			! $hide_comments &&
			apply_filters( 'terminal_comments_open', ( ! post_password_required() && comments_open( get_the_ID() ) ) )
		) :
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
