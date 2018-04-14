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


$result = wp_cache_get( 'terminal-analytics-' . $id );
if ( false === $result ) {
  $time_since_publish = current_time( 'timestamp' ) - get_the_time( 'U' );
  // If published more than a week ago, cache longer.
  if ( $time_since_publish > 6048000) {
    $cache_length = 864000;
  } elseif ( $time_since_publish > 604800) {
    $cache_length = 86400;
  } else {
    $cache_length = 8640;
  }
  $result = wp_remote_get(
    "https://api.parsely.com/v2/analytics/post/detail?apikey=${api_key}&secret=${api_secret}&url=${url}"
  );
  wp_cache_set( 'terminal-analytics-' . $id, $result, '', $cache_length );
}

if ( empty( $result ) ) {
  return;
}

$json = json_decode( wp_remote_retrieve_body( $result ) );

echo $json;
