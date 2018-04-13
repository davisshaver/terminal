<?php
$id = get_the_ID();
if ( empty( $id ) ) {
  return;
}

$api_key = getenv( 'TERMINAL_PARSELY_API_KEY' );
$api_secret = getenv( 'TERMINAL_PARSELY_API_SECRET' );

if ( empty( $api_key ) || empty( $api_secret ) ) {
  return;
}
$url = get_the_permalink();


$result = wp_cache_get( 'terminal-social-' . $id );
if ( false === $result ) {
  $result = wp_remote_get(
    "https://api.parsely.com/v2/shares/post/detail?apikey=${api_key}&secret=${api_secret}&url=${url}"
  );
  wp_cache_set( 'terminal-social-' . $id, $result, '', 3600 );
}

if ( empty( $result ) ) {
  return;
}

$json = json_decode( wp_remote_retrieve_body( $result ) );

ob_start();
get_template_part( 'partials/svg/facebook.svg' );
$facebook = ob_get_contents();
ob_end_clean();

ob_start();
get_template_part( 'partials/svg/twitter.svg' );
$twitter = ob_get_contents();
ob_end_clean();

ob_start();
get_template_part( 'partials/svg/linked-in.svg' );
$linked_in = ob_get_contents();
ob_end_clean();

ob_start();
get_template_part( 'partials/svg/pinterest.svg' );
$pinterest = ob_get_contents();
ob_end_clean();

echo '<div class="terminal-social-counts">';
echo '<ul>';
printf(
  '<li class="terminal-facebook"><a rel="nofollow" href="%s" target="_new">%s<span class="terminal-count %s">%s</span></a></li>',
  esc_url( 'http://www.facebook.com/sharer.php?u=' . get_the_permalink() . '&amp;t=' . urlencode( get_the_title() ) ),
  $facebook,
  ! empty( $json->data[0]->fb ) ? null : 'terminal-hidden',
  ! empty( $json->data[0]->fb ) ? esc_html( $json->data[0]->fb ) : null
);

printf(
  '<li class="terminal-twitter"><a rel="nofollow" href="%s" target="_new">%s<span class="terminal-count %s">%s</span></a></li>',
  esc_url(
    'http://twitter.com/home?status=' . urlencode( get_the_title() ) . ' ' . get_the_permalink()
  ),
  $twitter,
  ! empty( $json->data[0]->tw ) ? null : 'terminal-hidden',
  ! empty( $json->data[0]->tw ) ? esc_html( $json->data[0]->tw ) : null
);
printf(
  '<li class="terminal-linked"><a rel="nofollow" href="%s" target="_new">%s<span class="terminal-count %s">%s</span></a></li>',
  esc_url(
    'http://www.linkedin.com/shareArticle?mini=true&url=' . get_the_permalink() . '&title=' . urlencode( get_the_title() ) . '&summary=' . urlencode( get_the_excerpt() ) . '&source=' . str_replace( 'https://', '', get_home_url() )
  ),
  $linked_in,
  ! empty( $json->data[0]->li ) ? null : 'terminal-hidden',
  ! empty( $json->data[0]->li ) ? esc_html( $json->data[0]->li ) : null
);
printf(
  '<li class="terminal-pinterest"><a rel="nofollow" href="%s" target="_new">%s<span class="terminal-count %s">%s</span></a></li>',
  esc_url(
    'http://pinterest.com/pin/create/button/?url=' . get_the_permalink() . '&description=' . urlencode( get_the_title() )
  ),
  $pinterest,
  ! empty( $json->data[0]->pi ) ? null : 'terminal-hidden',
  ! empty( $json->data[0]->pi ) ? esc_html( $json->data[0]->pi ) : null
);
echo '</ul>';
echo '</div>';