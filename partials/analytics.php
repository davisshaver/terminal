<?php
$parsely = \Terminal\Parsely::instance();
echo '<div class="terminal-single-analytics terminal-limit-max-content-width-add-margin">';
$parsely->print_analytics_for_post( get_the_id() );
$parsely->print_referrers_for_post( get_the_id() );
$parsely->print_social_for_post( get_the_id() );
echo '</div>';