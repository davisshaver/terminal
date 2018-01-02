<?php
/**
 * The search form for Terminal is slightly customized, so we use this template to display it.
 *
 * @package Terminal
 */

// Randomized ID in case there are multiple search forms on a single page.
$searchform_id = 'search-' . rand();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-form-field">
		<label for="<?php echo esc_attr( $searchform_id ); ?>"><?php echo esc_html_x( 'Search', 'label', 'terminal' ); ?></label>
		<input id="<?php echo esc_attr( $searchform_id ); ?>" type="search" class="search-field" value="<?php the_search_query(); ?>" name="s" />
		<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Go', 'submit button', 'terminal' ); ?>" />
	</div>
</form>
