<?php
/**
 * Parse.ly integration
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Parsely integration.
 */
class Parsely {

	use Singleton;
  private $api_key;

  private $api_secret;

	/**
	 * Setup actions.
	 */
	public function setup() {
    $this->api_key = getenv( 'TERMINAL_PARSELY_API_KEY' );
    $this->api_secret = getenv( 'TERMINAL_PARSELY_API_SECRET' );
    add_action( 'retrieve_social_data', 'retrieve_social_data', 10, 1 );
    add_action( 'retrieve_analytics_data', 'retrieve_analytics_data', 10, 1 );
    add_action( 'retrieve_referral_data', 'retrieve_referral_data', 10, 1 );
  }

  private function set_cached( $key, $value, $post_id, $length ) {
    update_post_meta( $post_id, $key, json_encode( $value ) );
    update_post_meta( $post_id, $key . '_expiration', time() + $length );
  }

  private function get_cached( $key, $post_id ) {
    $value = get_post_meta( $post_id, $key, true );
    $expiration = get_post_meta( $post_id, $key . '_expiration', true );
    if ( empty( $value ) || empty( $expiration ) || time() > $expiration ) {
      return false;
    }
    return json_decode( $value );
  }

  private function get_cached_referrers_for_post( $post_id ) {
    if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			return;
    }
    $json = $this->get_cached( 'terminal_referrers', $post_id );
    if (
      false === $json &&
      false === wp_next_scheduled(
        'retrieve_referral_data',
        array( $post_id )
      )
    ) {
      wp_schedule_single_event(
        time(),
        'retrieve_referral_data',
        array( $post_id )
      );
      return;
    }
    return $json;
  }

  private function get_cached_analytics_for_post( $post_id ) {
    if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			return;
    }
    $json = $this->get_cached( 'terminal_analytics', $post_id );
    if (
      false === $json &&
      false === wp_next_scheduled(
        'retrieve_analytics_data',
        array( $post_id )
      )
    ) {
      wp_schedule_single_event(
        time(),
        'retrieve_analytics_data',
        array( $post_id )
      );
      return;
    }
    return $json;
  }

  private function parsely_metric_to_name( $metric ) {
    switch( $metric ) {
      case 'tw':
        return __( 'Twitter', 'terminal' );
      case 'fb':
        return __( 'Facebook', 'terminal' );
      case 'go':
        return __( 'Google', 'terminal' );
      case 'li':
        return __( 'LinkedIn', 'terminal' );
      case 'pi':
        return __( 'Pinterest', 'terminal' );
      default:
        return null;
    }
  }
  public function print_social_for_post( $post_id ) {
    if ( empty( $post_id ) ) {
      $post_id = get_the_ID();
    }
    if ( empty( $post_id ) ) {
      return;
    }
    $json = $this->get_cached_social_data( $post_id );
    if (
      ! empty( $json->data ) &&
      ! empty( $json->data[0] )
    ) {
      echo '<ul>';
      foreach( $json->data[0] as $metric => $value ) {
        if ( 'total' === $metric ) {
          break;
        }
        $name = $this->parsely_metric_to_name( $metric );
        printf(
          '<li><strong>%s %s:</strong> %s</li>',
          esc_html( $name ),
          esc_html( __( 'shares', 'terminal' ) ),
          esc_html( number_format( $value ) )
        );
      }
      echo '</ul>';
    }
  }
  public function print_analytics_for_post( $post_id ) {
    if ( empty( $post_id ) ) {
      $post_id = get_the_ID();
    }
    if ( empty( $post_id ) ) {
      return;
    }
    $json = $this->get_cached_analytics_for_post( $post_id );
    if (
      ! empty( $json->data ) &&
      ! empty( $json->data[0]->metrics ) &&
      ! empty( $json->data[0]->metrics )
    ){
      $metrics = $json->data[0]->metrics;
      echo '<ul>';
      foreach( $metrics as $metric => $value ) {
        printf(
          '<li><strong>%s:</strong> %s</li>',
          esc_html( ucfirst( $metric ) ),
          esc_html( number_format( $value ) )
        );
      }
      echo '</ul>';
    }
  }

  public function print_referrers_for_post( $post_id ) {
    if ( empty( $post_id ) ) {
      $post_id = get_the_ID();
    }
    if ( empty( $post_id ) ) {
      return;
    }
    $json = $this->get_cached_referrers_for_post( $post_id );
    if (
      ! empty( $json->data )
    ){
      echo '<ol>';
      foreach( $json->data as $key => $referrer ) {
        printf(
          '<li><strong>%s:</strong> %s</li>',
          esc_html( ucfirst( $referrer->type ) ),
          esc_html( number_format( $referrer->metrics->referrers_views ) )
        );
      }
      echo '</ol>';
    }
  }
  private function get_cache_length( $post_id ) {
    $time_since_publish = current_time( 'timestamp' ) - get_the_time( 'U', $post_id );
    if ( $time_since_publish > 6048000) {
      $cache_length = 864000;
    } elseif ( $time_since_publish > 604800) {
      $cache_length = 86400;
    } else {
      $cache_length = 8640;
    }
    return $cache_length;
  }

  public function store_referral_data( $post_id ) {
    $cache_length = $this->get_cache_length( $post_id );
    $url = get_the_permalink( $post_id );
    $rest_target = sprintf(
      'https://api.parsely.com/v2/referrers/post/detail?apikey=%s&secret=%s&url=%s&period_start=90d',
      $this->api_key,
      $this->api_secret,
      $url
    );
    $result = wp_remote_get( $rest_target, array( 'timeout' => 15 ) );
    if ( is_wp_error( $result ) ) {
      return;
    }
    if ( empty( $result ) ) {
      return;
    }
    $json = json_decode( wp_remote_retrieve_body( $result ) );
    if (
      ! empty( $json->data ) &&
      ! empty( $json->data[0] ) &&
      ! empty( $json->data[0] )
    ) {
      $this->set_cached( 'terminal_referrers', $json, $post_id, $cache_length );
      foreach( $json->data as $key => $referrer ) {
        update_post_meta( $post_id, 'terminal_parsely_referrer_' . $referrer->type, $referrer->metrics->referrers_views );
      }
    }
  }

  public function store_analytics_data( $post_id ) {
    $cache_length = $this->get_cache_length( $post_id );
    $url = get_the_permalink( $post_id );
    $rest_target = sprintf(
      'https://api.parsely.com/v2/analytics/post/detail?apikey=%s&secret=%s&url=%s',
      $this->api_key,
      $this->api_secret,
      $url
    );
    $result = wp_remote_get( $rest_target, array( 'timeout' => 15 ) );
    if ( is_wp_error( $result ) ) {
      return;
    }
    if ( empty( $result ) ) {
      return;
    }
    $json = json_decode( wp_remote_retrieve_body( $result ) );
    if (
      ! empty( $json->data ) &&
      ! empty( $json->data[0]->metrics ) &&
      ! empty( $json->data[0]->metrics )
    ) {
      foreach( $json->data[0]->metrics as $metric => $value ) {
        update_post_meta( $post_id, 'terminal_parsely_analytics_' . $metric, $value );
      }
      $this->set_cached( 'terminal_analytics', $json, $post_id, $cache_length );
    }
  }

  public function store_social_data( $post_id ) {
    $cache_length = $this->get_cache_length( $post_id );
    $url = get_the_permalink( $post_id );
    $rest_target = sprintf(
      'https://api.parsely.com/v2/shares/post/detail?apikey=%s&secret=%s&url=%s',
      $this->api_key,
      $this->api_secret,
      $url
    );
    $result = wp_remote_get( $rest_target, array( 'timeout' => 15 ) );
    if ( is_wp_error( $result ) ) {
      return;
    }
    if ( empty( $result ) ) {
      return;
    }
    $json = json_decode( wp_remote_retrieve_body( $result ) );
    $this->set_cached( 'terminal_social', $json, $post_id, $cache_length );
    if ( ! empty( $json->data[0]->fb ) ) {
      update_post_meta( $post_id, 'terminal_parsely_facebook_shares', $json->data[0]->fb );
    }
    if ( ! empty( $json->data[0]->tw ) ) {
      update_post_meta( $post_id, 'terminal_parsely_twitter_shares', $json->data[0]->tw );
    }
    if ( ! empty( $json->data[0]->pi ) ) {
      update_post_meta( $post_id, 'terminal_parsely_pinterest_shares', $json->data[0]->pi );
    }
    if ( ! empty( $json->data[0]->li ) ) {
      update_post_meta( $post_id, 'terminal_parsely_linked_in_shares', $json->data[0]->li );
    }
  }

  private function get_cached_social_data( $post_id ) {
		if ( empty( $this->api_key ) || empty( $this->api_secret ) ) {
			return;
    }
    $url = get_the_permalink( $post_id );
    $json = $this->get_cached( 'terminal_social', $post_id );
		if (
      false === $json &&
      false === wp_next_scheduled(
        'retrieve_social_data',
        array( $post_id )
      )
    ) {
      wp_schedule_single_event(
        time(),
        'retrieve_social_data',
        array( $post_id )
      );
      return;
    }
    return $json;
  }
  /**
   * Filter ESSB4 share counts.
   *
   * @param array $current Current counts.
   * @param int   $post_id Post ID (optional).
   * @return array Filtered counts.
   */
  public function filter_essb4_counters( $current, $post_id = null) {
		if ( empty( $post_id ) ) {
      $post_id = get_the_ID();
			return;
    }
    if ( empty( $post_id ) ) {
			return;
		}
    $json = $this->get_cached_social_data( $post_id );
		$updated = $current;
		$updated['facebook'] = ! empty( $json->data[0]->fb ) ? $json->data[0]->fb : $current['facebook'];
		$updated['twitter'] = ! empty( $json->data[0]->tw ) ? $json->data[0]->tw : $current['twitter'];
		$updated['linkedin'] = ! empty( $json->data[0]->li ) ? $json->data[0]->li : $current['linkedin'];
		$updated['pinterest'] = ! empty( $json->data[0]->pi ) ? $json->data[0]->pi : $current['pinterest'];
		$updated['total'] = ! empty( $json->data[0]->total ) ?
			$json->data[0]->total + $current['mail'] :
			$current['total'];
		return $updated;
  }
}

add_action( 'after_setup_theme', [ '\Terminal\Parsely', 'instance' ] );
