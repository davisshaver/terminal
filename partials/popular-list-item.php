<?php
/**
 * Popular post partial.
 *
 * @package Terminal
 */

if ( empty( $url ) || empty( $title ) ) {
  return;
}

if ( empty( $rank ) ) {
  $rank = null;
}

if ( ! empty( $image_url ) ) {
  $image_class = '';
} else {
  $image_url = false;
  $image_class = 'terminal-no-photo';
}

printf(
  '<div class="terminal-popular-list-item %s" data-rank="%s">',
  esc_attr( $image_class ),
  esc_attr( $rank )
);
if ( $image_url ) {
  printf(
    '<a href="%s" class="terminal-popular-list-item-image terminal-card-image"><img alt="%s" src="%s" /></a>',
    esc_url( $url ),
    esc_attr( $title ),
    esc_url( $image_url )
  );
}
?>
  <h4 class="terminal-headline-font">
    <?php
      printf(
        '<a href="%s" class="terminal-link-gray">%s</a>',
        esc_url( $url ),
        $title
      );
    ?>
  </h4>
  <div class="terminal-byline terminal-text-gray">
    <div class="terminal-author terminal-no-select">
    <?php
    printf(
      '%s %s',
      esc_html( __( 'By', 'terminal' ) ),
      esc_html( implode( ', ', $authors ) )
    );
    ?>
	</div>
</div>
</div>