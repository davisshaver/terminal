<?php
$api_key = getenv( 'TERMINAL_PARSELY_API_KEY' );
$api_secret = getenv( 'TERMINAL_PARSELY_API_SECRET' );

if ( empty( $api_key ) || empty( $api_secret ) ) {
  return;
}
?>

<div id="terminal-popular" class="terminal-popular-container terminal-hidden-no-js">
  <?php terminal_print_popular_header(); ?>
  <div class="terminal-card terminal-card-featured terminal-card-single terminal-alignment-center">
    <div class="terminal-card-title terminal-card-title-popular terminal-sidebar-header-font">
      <select class="terminal-popular-select-filter" name="filter">
        <option value="past-day"><?php esc_html_e( 'Past Day', 'terminal' ); ?></option>
        <option value="past-two-days"><?php esc_html_e( 'Past 48 Hours', 'terminal' ); ?></option>
        <option value="past-week"><?php esc_html_e( 'Past Week', 'terminal' ); ?></option>
        <option value="past-month"><?php esc_html_e( 'Past Month', 'terminal' ); ?></option>
      </select>
    </div>
    <?php
    $result = wp_cache_get( 'terminal-parsely-popular-last-day' );
    if ( false === $result ) {
      $result = wp_remote_get(
        "https://api.parsely.com/v2/analytics/posts?apikey=${api_key}&secret=${api_secret}&period_start=24h&limit=4"
      );
      wp_cache_set( 'terminal-parsely-popular-last-day', $result, '', 3600 );
    }
    if ( ! empty( $result ) ) {
      $json = json_decode( wp_remote_retrieve_body( $result ) );
      if ( ! empty( $json->data ) ) {
        echo '<div data-value="past-day" class="terminal-card-text terminal-popular-list terminal-popular-list-day">';
        foreach( $json->data as $popular_post ) {
          terminal_print_template_part(
            'popular-list-item',
            array(
              'url' => $popular_post->url,
              'image_url' => $popular_post->image_url,
              'authors' => $popular_post->authors,
              'views' => $popular_post->metrics->views,
              'title' => $popular_post->title,  
            )
          );
        }
        echo '</div>';
      }
    }
    $result = wp_cache_get( 'terminal-parsely-popular-last-two-days' );
    if ( false === $result ) {
      $result = wp_remote_get(
        "https://api.parsely.com/v2/analytics/posts?apikey=${api_key}&secret=${api_secret}&period_start=48h&limit=4"
      );
      wp_cache_set( 'terminal-parsely-popular-last-two-days', $result, '', 3600 );
    }
    if ( ! empty( $result ) ) {
      $json = json_decode( wp_remote_retrieve_body( $result ) );
      if ( ! empty( $json->data ) ) {
        echo '<div data-value="past-two-days" class="terminal-card-text terminal-popular-list terminal-hidden terminal-popular-list-two-days">';
        foreach( $json->data as $popular_post ) {
          terminal_print_template_part(
            'popular-list-item',
            array(
              'url' => $popular_post->url,
              'image_url' => $popular_post->image_url,
              'authors' => $popular_post->authors,
              'views' => $popular_post->metrics->views,
              'title' => $popular_post->title,  
            )
          );
        }
        echo '</div>';
      }
    }
    $result = wp_cache_get( 'terminal-parsely-popular-last-week' );
    if ( false === $result ) {
      $result = wp_remote_get(
        "https://api.parsely.com/v2/analytics/posts?apikey=${api_key}&secret=${api_secret}&period_start=7d&limit=4"
      );
      wp_cache_set( 'terminal-parsely-popular-last-week', $result, '', 3600 );
    }
    if ( ! empty( $result ) ) {
      $json = json_decode( wp_remote_retrieve_body( $result ) );
      if ( ! empty( $json->data ) ) {
        echo '<div data-value="past-week" class="terminal-card-text terminal-popular-list terminal-popular-list-week terminal-hidden">';
        foreach( $json->data as $popular_post ) {
          terminal_print_template_part(
            'popular-list-item',
            array(
              'url' => $popular_post->url,
              'image_url' => $popular_post->image_url,
              'authors' => $popular_post->authors,
              'views' => $popular_post->metrics->views,
              'title' => $popular_post->title,  
            )
          );
        }
        echo '</div>';
      }
    }
    $result = wp_cache_get( 'terminal-parsely-popular-last-four-weeks' );
    if ( false === $result ) {
      $result = wp_remote_get(
        "https://api.parsely.com/v2/analytics/posts?apikey=${api_key}&secret=${api_secret}&period_start=4w&limit=4"
      );
      wp_cache_set( 'terminal-parsely-popular-last-four-weeks', $result, '', 3600 );
    }
    if ( ! empty( $result ) ) {
      $json = json_decode( wp_remote_retrieve_body( $result ) );
      if ( ! empty( $json->data ) ) {
        echo '<div data-value="past-month" class="terminal-card-text terminal-popular-list terminal-popular-list-month terminal-hidden">';
        foreach( $json->data as $popular_post ) {
          terminal_print_template_part(
            'popular-list-item',
            array(
              'url' => $popular_post->url,
              'image_url' => $popular_post->image_url,
              'authors' => $popular_post->authors,
              'views' => $popular_post->metrics->views,
              'title' => $popular_post->title,  
            )
          );
        }
        echo '</div>';
      }
    }
    ?>
  </div>
</div>