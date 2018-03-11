<?php
/**
 * What's next integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Upcoming Widget.
	 */
	class Upcoming_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-upcoming-widget',
				__( 'Upcoming Content', 'terminal' )
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
			$upcoming_query = new \WP_Query( array(
				'posts_per_page'      => 5,
				'ignore_sticky_posts' => true,
			) );
			if ( $upcoming_query->have_posts() ) :
				// phpcs:ignore
				echo $args['before_widget'];

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
				'title' => new \Fieldmanager_TextField( array(
					'label'         => __( 'Title', 'terminal' ),
					'default_value' => __( 'Upcoming Posts', 'terminal' ),
				) ),
				'status' => new \Fieldmanager_Select( array(
					'options' => get_post_stati()
				) ),
			];
		}
	}

	register_widget( '\Upcoming_Widget' );
}
