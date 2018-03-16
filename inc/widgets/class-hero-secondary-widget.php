<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Hero Widget.
	 */
	class Hero_Secondary_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-hero-secondary-widget',
				__( 'Hero (Secondary)', 'terminal' )
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			if ( empty( $instance['category'] ) || ! is_int( $instance['category'] ) ) {
				return;
			}
			if (! isset( $instance['number'] ) || ! ( is_numeric( $instance['number'] ) && is_int( (int) $instance['number'] ) ) ) {
				$number = 2;
			} else {
				$number = $instance['number'];
			}
			$cat_query = new \WP_Query( array(
				'posts_per_page'      => $number,
				'ignore_sticky_posts' => true,
				'post__not_in'        => is_single() ? array( get_the_ID() ) : array(),
				'tax_query' => array(
					array(
						'taxonomy' => 'terminal-placement',
						'field'    => 'id',
						'terms'    => $instance['category'],
					),
				),
			) );
			$label = get_term_meta( $instance['category'], 'terminal_placement_options_label', true );
			if ( $cat_query->have_posts() ) :
				// phpcs:ignore
				printf(
					'<div class="terminal-card terminal-card-single terminal-hero-secondary-card-%s">',
					esc_attr( $number )
				);
				if ( ! empty( $label ) ) {
					printf(
					'	<div class="terminal-breadcrumbs">&Darr; %s</div>',
						esc_html( $label )
					);	
				}
				while ( $cat_query->have_posts() ) :
					$cat_query->the_post();
					get_template_part( 'partials/hero-secondary' );
				endwhile;
				// phpcs:ignore
				echo '</div>';
			endif;
			wp_reset_postdata();
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [
				'category'       => new \Fieldmanager_Select( array(
					'first_empty' => true,
					'datasource' => new \Fieldmanager_Datasource_Term( array(
						'taxonomy' => 'terminal-placement',
					) ),
				) ),
				'number'         => new \Fieldmanager_Select( 'Number to show', array(
					'default_value' => 2,
					'options'       => array(
						1,
						2,
						3,
						4,
						5,
					),
				) ),
			];
		}
	}

	register_widget( '\Hero_Secondary_Widget' );
}
