<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Author Widget.
	 */
	class Author_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-author-widget',
				__( 'Author Posts (Hidden on Index Pages)', 'terminal' )
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
			if ( ! is_singular() ) {
				return;
			}
			$author_query = new \WP_Query( array(
				'author'              => get_the_author_meta( 'ID' ),
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => true,
			) );
			if ( $author_query->have_posts() ) :
				// phpcs:ignore
				echo $args['before_widget'];
				if ( ! empty( $widget_title ) && empty( $instance['disable_header'] ) ) {
					// phpcs:ignore
					echo $args['before_title'] . $widget_title . $args['after_title'];
				}
				echo '<div class="category-widget">';
				while ( $cat_query->have_posts() ) :
					$cat_query->the_post();
					if ( 0 === $cat_query->current_post ) {
						get_template_part( 'partials/post-widget-featured' );
					} else {
						get_template_part( 'partials/post-widget' );
					}
				endwhile;
				echo '</div>';
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
			return [];
		}
	}

	register_widget( '\Author_Widget' );
}
