<?php $categories = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'amp' ), '', $this->ID ); ?>
<?php if ( $categories ) : ?>
	<div class="amp-wp-meta amp-wp-tax-category">
		<?php printf( esc_html__( 'Categories: %s', 'amp' ), $categories ); ?>
	</div>
<?php endif; ?>
