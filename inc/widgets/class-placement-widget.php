<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Placement Widget.
	 */
	class Placement_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-placement-widget',
				__( 'Placement Posts', 'terminal' )
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
			if ( empty( $instance['placement'] ) || ! is_int( $instance['placement'] ) ) {
				return;
			}
			$placement = get_term_by( 'id', $instance['placement'], 'terminal-placement' );
			$widget_title = ! empty( $instance['custom_header'] ) ? $instance['custom_header'] : $placement->name;
			$cat_query = new \WP_Query( array(
				'tax_query' => array(
					array (
							'taxonomy' => 'terminal-placement',
							'field' => 'id',
							'terms' => $instance['placement'],
					)
				),
				'posts_per_page'      => $instance['number'],
				'ignore_sticky_posts' => true,
				'post__not_in'        => is_single() ? array( get_the_ID() ) : array(),
			) );
			if ( $cat_query->have_posts() ) :
				// phpcs:ignore
				$size = isset( $instance['size'] ) ? $instance['size'] : 'single';
				$before_widget = str_replace(
					'terminal-card-single',
					'terminal-card-' . $size,
					$args['before_widget']
				);
				echo $before_widget;
				if ( ! empty( $widget_title ) && empty( $instance['disable_header'] ) ) {
					// phpcs:ignore
					echo $args['before_title'] . $widget_title . $args['after_title'];
				}
				while ( $cat_query->have_posts() ) :
					$cat_query->the_post();
					if ( ! empty( $instance['first_featured'] ) && 0 === $cat_query->current_post ) {
						get_template_part( 'partials/post-widget-featured' );
					} else {
						get_template_part( 'partials/post-widget' );
					}
				endwhile;
				// phpcs:ignore
				echo $args['after_widget'];
			endif;
			wp_reset_postdata();
			// phpcs:ignore
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [
				'disable_header' => new \Fieldmanager_Checkbox( 'Disable category header' ),
				'custom_header'  => new \Fieldmanager_Textfield( 'Optional custom header' ),
				'first_featured' => new \Fieldmanager_Checkbox( 'Use featured template for first post' ),
				'number'         => new \Fieldmanager_Select( 'Number to show', array(
					'default_value' => 3,
					'options'       => array(
						1,
						2,
						3,
						4,
						5,
					),
				) ),
				'placement'       => new \Fieldmanager_Select( array(
					'datasource' => new \Fieldmanager_Datasource_Term( array(
						'taxonomy' => 'terminal-placement',
					) ),
				) ),
				'size'         => new \Fieldmanager_Select( 'Size', array(
					'default_value' => 'single',
					'options'       => array(
						'single',
						'double',
						'triple',
					),
				) ),
			];
		}
	}

	register_widget( '\Placement_Widget' );
}
