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
	class Hero_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-hero-widget',
				__( 'Hero Post', 'terminal' )
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
				'posts_per_page'      => ! empty( $instance['number'] ) ? $instance['number'] : 1,
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
				while ( $cat_query->have_posts() ) :
					$cat_query->the_post();
					terminal_print_template_part(
						'hero',
						array(
							'size' => isset( $instance['size'] ) ? $instance['size'] : 'double',
						)
					);
				endwhile;
				// phpcs:ignore
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
				'size'         => new \Fieldmanager_Select( 'Size', array(
					'default_value' => 'double',
					'options'       => array(
						'single',
						'double',
						'triple',
					),
				) ),
				'number'         => new \Fieldmanager_Select( 'Number to show', array(
					'default_value' => 1,
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

	register_widget( '\Hero_Widget' );
}
