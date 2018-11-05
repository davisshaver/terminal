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
	class Post_Type_Widget extends \FM_Widget {
		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-post-type',
				__( 'Post Type', 'terminal' )
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
			if ( empty( $instance['post_type'] ) || ! is_string( $instance['post_type'] ) ) {
				return;
			}
			$post_types = terminal_get_post_types( false );
			$post_type_object = get_post_type_object( $post_types[ $instance[ 'post_type' ] ] );
			if ( empty( $post_type_object ) ) {
				return;
			}
			$widget_title = ! empty( $instance['custom_header'] ) ? $instance['custom_header'] : $post_type_object->labels->singular_name;
			$post_type_query = new \WP_Query( array(
				'post_type'           => $post_type_object->name,
				'posts_per_page'      => $instance['number'],
				'ignore_sticky_posts' => true,
				'post__not_in'        => is_single() ? array( get_the_ID() ) : array(),
			) );
			if ( $post_type_query->have_posts() ) :
				// phpcs:ignore
				echo $args['before_widget'];
				if ( ! empty( $widget_title ) && empty( $instance['disable_header'] ) ) {
					// phpcs:ignore
					echo $args['before_title'] . $widget_title . $args['after_title'];
				}
				while ( $post_type_query->have_posts() ) :
					$post_type_query->the_post();
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
				'post_type'      => new \Fieldmanager_Select( 'Post type', array(
					'default_value' => 'post',
					'options'       => array_keys( terminal_get_post_types( false ) ),
				) ),
			];
		}
	}
	register_widget( '\Post_Type_Widget' );
}