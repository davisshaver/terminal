<?php
$featured_image = $this->get( 'featured_image' );

if ( empty( $featured_image ) ) {
	return;
}

$amp_html = $featured_image['amp_html'];
$caption = $featured_image['caption'];
?>
<figure class="amp-wp-article-featured-image wp-caption">
	<?php echo $amp_html; // amphtml content; no kses ?>
	<?php terminal_print_featured_image_caption(); ?>
</figure>
