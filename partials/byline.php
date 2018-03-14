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
	'loop_hide_avatar'     => true,
	'loop_avatar_size'     => 25,
	'loop_hide_date'       => false,
	'loop_hide_author'     => false,
	'loop_hide_category'   => false,
	'loop_hide_comments'   => true,
	'loop_hide_edit'       => true,
	'single_hide_avatar'   => false,
	'single_avatar_size'   => 25,
	'single_hide_date'     => false,
	'single_hide_author'   => false,
	'single_hide_category' => false,
	'single_hide_comments' => true,
	'single_hide_edit'     => true,
) );

$avatar_size = intval(
	is_singular() ?
	$byline_data['single_avatar_size'] :
	$byline_data['loop_avatar_size']
);

$avatar_size_class = "terminal-flex-basis-$avatar_size";

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

$hide_by = boolval(
	! empty( $byline_data['hide_by'] )
);

$byline_style = is_singular() ? 'terminal-single-meta-font' : 'terminal-index-meta-font';
printf(
	'<div class="terminal-limit-max-content-width-add-margin %s %s"><div class="terminal-byline terminal-text-gray-light">',
	esc_attr( $byline_style ),
	$hide_byline_on_mobile ? esc_attr( 'terminal-mobile-hide' ) : ''
);
if ( ! $hide_avatar ) :
?>
	<div class="terminal-avatar <?php echo esc_attr( $avatar_size_class ); ?>">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>">
			<?php terminal_print_avatar( $avatar_size, $default_gravatar ); ?>
		</a>
	</div>
<?php
endif;
if ( ! $hide_author && 'photo' !== $post_type ) :
?>
	<div class="terminal-author">
		<?php
		if ( ! $hide_by ) {
			
		}
		printf(
			'<span>%s <a class="terminal-link-gray-light" href="%s">',
			! $hide_by ? esc_html( 'By ', 'terminal' ) : '',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) )
		);
		the_author();
		?>
		</a></span>
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
	<div class="terminal-date">
		<a class="terminal-link-gray-light" href="<?php echo esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ); ?>">
			<abbr class="terminal-date" title="<?php the_time( 'l, F j, Y \a\t g:ia' ); ?>"><?php echo esc_html( $time ); ?></abbr>
		</a>
	</div>
<?php
endif;
if ( ! $hide_category ) :
?>
	<div class="terminal-category terminal-link-gray-light"><?php the_category( ' · ' ); ?></div>
<?php
endif;
if (
	! $hide_comments &&
	apply_filters( 'terminal_comments_open', ( ! post_password_required() && comments_open( get_the_ID() ) ) )
) :
?>
	<div class="terminal-number-of-comments"><a class="terminal-link-gray-light" href="<?php comments_link(); ?>">Comments</a><span class="share-number">&nbsp;<?php terminal_print_comment_count_for_post(); ?></span></div>
<?php
endif;
?>
</div>
</div>