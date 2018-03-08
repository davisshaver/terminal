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
			$cat_query = new \WP_Query( array(
				'posts_per_page'      => 2,
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
			if ( $cat_query->have_posts() ) :
				// phpcs:ignore
				echo '<div class="terminal-card terminal-card-double">';
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
			];
		}
	}

	register_widget( '\Hero_Secondary_Widget' );
}
