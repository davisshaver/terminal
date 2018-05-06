<?php
$parsely = \Terminal\Parsely::instance();
echo '<div class="terminal-single-analytics terminal-limit-max-content-width-add-margin">';
printf(
  '<small>%s %s.</small>',
  __( 'Analytics limited to past 90 days. Confidential to', 'terminal' ),
  esc_html( get_bloginfo( 'title' ) )
);
$parsely->print_analytics_for_post( get_the_id() );
$parsely->print_referrers_for_post( get_the_id() );
$parsely->print_social_for_post( get_the_id() );
echo '</div>';