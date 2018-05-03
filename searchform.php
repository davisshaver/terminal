<?php
/**
 * The search form for Terminal is slightly customized, so we use this template to display it.
 *
 * @package Terminal
 */

$header_data = terminal_get_header_data( array(
	'example_searches' => '',
) );

// Randomized ID in case there are multiple search forms on a single page.
$searchform_id = 'search-' . rand();
?>
<form id="<?php printf( 'search-form-%s', $searchform_id ); ?>" role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-form-field">
		<label for="<?php echo esc_attr( $searchform_id ); ?>"><?php echo esc_html_x( 'Search', 'label', 'terminal' ); ?></label>
		<input id="<?php echo esc_attr( $searchform_id ); ?>" type="search" results=5 autosave="terminal-search-recent" class="search-field" value="<?php the_search_query(); ?>" placeholder="<?php esc_html_e( 'Enter Search Here', 'terminal' ); ?>" name="s" />
		<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'terminal' ); ?>" />
	</div>
	<div class="terminal-hidden terminal-search-form-more-link terminal-alignment-center"><a href="#">
	<?php
	ob_start();
	get_template_part( 'partials/svg/down.svg' );
	$down = ob_get_contents();
	ob_end_clean();
	esc_html_e( 'Advanced', 'terminal' );
	echo $down;
	?>
	</a></div>
	<div class="terminal-hidden terminal-search-form-more">
	<label for="terminal-boost-<?php echo esc_attr( $searchform_id ); ?>"><?php esc_html_e( 'Sort:', 'terminal' ); ?></label>
	<select id="terminal-boost-<?php echo esc_attr( $searchform_id ); ?>" name="boost">
		<option value="default"><?php esc_html_e( 'Default', 'terminal' ); ?></option>
		<option value="recency"><?php esc_html_e( 'Recency', 'terminal' ); ?></option>
		<option value="social_referrals"><?php esc_html_e( 'Social Referrals', 'terminal' ); ?></option>
		<option value="engaged_minutes"><?php esc_html_e( 'Total Engagement', 'terminal' ); ?></option>
		<option value="fb_referrals"><?php esc_html_e( 'Facebook Referrals', 'terminal' ); ?></option>
		<option value="tw_referrals"><?php esc_html_e( 'Twitter Referrals', 'terminal' ); ?></option>
	</select>
	<label id="terminal-date-start-<?php echo esc_attr( $searchform_id ); ?>"><?php esc_html_e( 'Start:', 'terminal' ); ?></label>
	<input type="date" min="2001-01-01" max="<?php echo esc_attr( date( "Y-m-d" ) ); ?>" id="terminal-date-before-<?php echo esc_attr( $searchform_id ); ?>" class="terminal-input-validate" name="pub_date_start" pattern="\d{1,2}/\d{1,2}/\d{4}">
	<label id="terminal-date-end-<?php echo esc_attr( $searchform_id ); ?>"><?php esc_html_e( 'End:', 'terminal' ); ?></label>
	<input type="date" min="2001-01-01" max="<?php echo esc_attr( date( "Y-m-d" ) ); ?>" id="terminal-date-after-<?php echo esc_attr( $searchform_id ); ?>" class="terminal-input-validate" name="pub_date_end" pattern="\d{1,2}/\d{1,2}/\d{4}">
	<div class="terminal-search-form-reset-link terminal-alignment-center"><a href="#">
	<?php
	ob_start();
	get_template_part( 'partials/svg/reset.svg' );
	$reset = ob_get_contents();
	ob_end_clean();
	esc_html_e( 'Reset', 'terminal' );
	echo $reset;
	?>
	</a></div>
	</div>
</form>
