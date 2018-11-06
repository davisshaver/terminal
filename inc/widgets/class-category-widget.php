<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */
if ( class_exists( '\FM_Widget' ) ) {
	/**
	 * Post Type Widget.
	 */
	class Category_Widget extends \FM_Widget {
		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-category',
				__( 'Category', 'terminal' )
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
			if ( empty( $instance['category'] ) || ! is_integer( $instance['category'] ) ) {
				return;
			}
			$widget_title = ! empty( $instance['custom_header'] ) ? $instance['custom_header'] : get_cat_name( $instance['category'] );
			$cat_query = new \WP_Query( array(
				'cat'                 => $instance['category'],
				'posts_per_page'      => $instance['number'],
				'ignore_sticky_posts' => true,
				'post__not_in'        => is_single() ? array( get_the_ID() ) : array(),
			) );
			if ( $cat_query->have_posts() ) :
				// phpcs:ignore
				echo $args['before_widget'];
				if ( ! empty( $widget_title ) && empty( $instance['disable_header'] ) ) {
					// phpcs:ignore
					echo $args['before_title'] . $widget_title . $args['after_title'];
				}
				while ( $cat_query->have_posts() ) :
					$cat_query->the_post();
					terminal_print_template_part( 'list-item' );
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
				'category'       => new \Fieldmanager_Select( array(
					'datasource' => new \Fieldmanager_Datasource_Term( array(
						'taxonomy' => 'category',
					) ),
				) ),
			];
		}
	}
	register_widget( '\Category_Widget' );
}